# 10design — Deploying to production

Production runs Statamic directly on the server (LEMP: Nginx + PHP-FPM), with
no Docker. Code goes from the local Docker setup to the server with
`deploy.py`, over SSH.

```
 Local (Docker)                                   Production server
 ─────────────────────────────                    ──────────────────────────────
 1. checks: git clean, SSH, server app ─────────▶ artisan, .env, composer, rsync
 2. npm ci && npm run build (node container)
 3. preview changes (rsync --dry-run) ──────────▶ compares file contents
 4. you confirm
 5.                                     ────────▶ backup: tar of the current code
 6. rsync code + public/build ──────────────────▶ content, users, uploads untouched
 7.                                     ────────▶ composer install --no-dev
                                                   artisan optimize, stache:warm
                                                   static:clear, chown to www-data
 8. health check ───────────────────────────────▶ https://10design.co
```

---

## 1. What is deployed and what is not

Editors change content in the Statamic Control Panel on production, so
**production owns the content**. The script only deploys code.

| Deployed (local → server)                         | Never uploaded or deleted (production owns it)          |
|---------------------------------------------------|---------------------------------------------------------|
| `app/`, `config/`, `routes/`, `lang/`             | `.env` — server-specific settings                        |
| `resources/views/`, `resources/css/`, `resources/js/` | `content/` — entries, globals, navigation, trees     |
| `resources/blueprints/`, `resources/fieldsets/`   | `users/`, `resources/users/` — accounts, roles, groups  |
| `public/build/` — assets built locally            | `resources/forms/` — form definitions                    |
|                                                   | everything else in `public/` — media uploads, `visuals/`, fonts, favicon, `index.php` (see 2.5) |
| `composer.json`, `composer.lock`                  | `storage/` — logs, sessions, form submissions, caches   |
|                                                   | `vendor/` — installed on the server by composer          |
|                                                   | `public/vendor/` — published by Statamic on the server   |
|                                                   | `public/img/`, `public/static/` — Glide and static cache |
|                                                   | `bootstrap/cache/*.php` — rebuilt by `artisan optimize`  |
|                                                   | `public/hot` — Vite dev marker, would break the site     |

The built-in list is `PROTECTED_PATHS` at the top of `deploy.py`. On top of
that, `deploy.ini` excludes `/public/*` with one exception, `/public/build/`
(`extra_excludes` / `extra_includes`).

> **Blueprints and fieldsets are deployed.** They are treated as code. If
> someone edits a blueprint in the Control Panel on production, the next deploy
> overwrites it (the backup still has it). Either make blueprint changes only
> locally, or pull production's blueprints before you work (see
> [section 7](#7-pulling-production-data-to-local)). If editors need to change
> blueprints on production, add `/resources/blueprints/` and
> `/resources/fieldsets/` to `extra_excludes`.

> **Content changes made locally are not deployed.** A new collection, page
> or global set created in your local Control Panel only exists locally. Create
> it on production through the Control Panel, or copy the specific files by hand.

---

## 2. Server setup

Deploys connect as **root**, through the `DigitalOcean10DesignRoot` alias in
`~/.ssh/config` (IP, user and key come from there):

```
Host DigitalOcean10DesignRoot
    HostName 161.35.164.126
    User root
    IdentityFile ~/.ssh/design10_ed25519
```

Check it works: `ssh DigitalOcean10DesignRoot 'ls /data/webs/10Design'`.

| Item          | Value                                            |
|---------------|--------------------------------------------------|
| App root      | `/data/webs/10Design` (Nginx serves `public/`)   |
| PHP           | 8.3 FPM, runs as `www-data`                      |
| Static cache  | on (`public/static/`, cleared on every deploy)   |

### 2.1 Software on the server

`php` (same major/minor version as local), `composer` and `rsync`. The script
checks all three before deploying. Node is **not** needed: assets are built locally.

### 2.2 The server's `.env`

`.env` is never uploaded, so it must already exist on the server, with at least:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://10design.co
APP_KEY=base64:...        # keep the existing key, never regenerate it on production
```

### 2.3 File ownership after each deploy

Files uploaded by root, and caches rebuilt by `artisan` running as root, are
owned by root. PHP-FPM runs as `www-data` and must be able to write (Control
Panel, uploads, caches). So the last command of every deploy (`reload_command`
in `deploy.ini`) hands everything back to `www-data`:

```bash
chown -R www-data:www-data /data/webs/10Design
find /data/webs/10Design -type d -exec chmod 755 {} +
find /data/webs/10Design -type f -exec chmod 644 {} +
```

For the few seconds between the cache rebuild and that `chown`, a request could
hit a root-owned cache file. If that matters, set `maintenance = true`.

### 2.4 PHP-FPM reload: not needed

The server uses PHP's default `opcache.validate_timestamps=1`, so PHP picks up
changed files by itself within a couple of seconds. Reloading Nginx would not
help either way: Nginx only passes requests to PHP-FPM. Only if someone sets
`opcache.validate_timestamps=0` would you need to add
`&& systemctl reload php8.3-fpm` to the end of `reload_command`. You'd notice
because changes stop appearing after deploys. Check with:

```bash
grep -rn "validate_timestamps" /etc/php/8.3/fpm/
```

The script prints an opcache warning only when `reload_command` is empty.

### 2.5 Files in `public/`

`public/` mixes code (compiled assets, logos, fonts) with production data (media
uploaded in the Control Panel). To make sure a deploy can never touch the media,
`deploy.ini` excludes everything in `public/` except the compiled assets:

```ini
extra_excludes =
    /public/*
extra_includes =
    /public/build/
```

So `public/build/` deploys automatically, and nothing else in `public/` is ever
uploaded or deleted, even with `--delete`. When another public file changes
(a logo in `visuals/`, a font, the favicon, `robots.txt`), upload it by hand
and give it back to `www-data`:

```bash
cd ~/Dockers/10design
rsync -avz statamic/public/visuals/io-logo.svg DigitalOcean10DesignRoot:/data/webs/10Design/public/visuals/
ssh DigitalOcean10DesignRoot 'chown -R www-data:www-data /data/webs/10Design/public/visuals'
```

Use `-n` (`rsync -avzn ...`) first to see what would be copied. For a whole
folder, end both paths with `/`.

---

## 3. One-time local setup

```bash
cd ~/Dockers/10design

# 1. A modern rsync (macOS ships an old/limited one)
brew install rsync

# 2. Config
cp deploy.ini.example deploy.ini
vi deploy.ini          # host alias, path, site_url, reload_command, extra_excludes

# 3. Keep the local config out of git
echo "deploy.ini" >> .gitignore
```

`deploy.py` uses only the Python standard library, so there is nothing to
`pip install`. It expects to live next to `docker-compose.yml`, with the app in
`./statamic` (change `local_path` if not).

---

## 4. Deploying

### 4.1 Before every deploy

1. Commit your changes. The script refuses to deploy uncommitted work
   (override with `--allow-dirty`).
2. Make sure the Docker stack is running (`docker compose up -d`). The asset
   build runs in the node container.
3. Test locally. If you changed config, check that
   `docker compose exec statamic php artisan optimize` still works. Production
   caches config, so a closure in a config file breaks the deploy
   (see DOCKER.md, troubleshooting 6.4).

### 4.2 Preview first

```bash
python3 deploy.py --dry-run
```

This runs every check and lists exactly what would change, without building or
touching the server:

```
<fc........ resources/views/home.antlers.html     # changed file
<f+++++++++ app/SearchFilters/ExcludeFromSearch.php   # new file
*deleting   resources/views/old.antlers.html       # only with --delete
```

### 4.3 Deploy

```bash
python3 deploy.py
```

The script builds the assets, shows the preview, and asks `Deploy <commit> to
production? [y/N]`. After you confirm, it backs up the server, uploads, runs the
post-deploy commands and checks the site. It ends with the command to roll back.

Every deploy writes a `.deployed` file in the app root on the server, recording
the commit, branch, date and who deployed:

```bash
ssh DigitalOcean10DesignRoot 'cat /data/webs/10Design/.deployed'
```

### 4.4 Options

| Option            | What it does                                                                 |
|-------------------|------------------------------------------------------------------------------|
| `--dry-run`       | Checks and change preview only. Nothing is built or changed.                 |
| `-y`, `--yes`     | Don't ask for confirmation.                                                  |
| `--delete`        | Also delete server files that no longer exist locally (protected paths are never deleted). Use it when you removed or renamed templates/classes. Always run `--dry-run --delete` first. |
| `--search`        | Rebuild the search indexes (`php please search:update --all`).               |
| `--skip-build`    | Upload the existing `public/build` without rebuilding.                       |
| `--skip-backup`   | Don't back up first. Not recommended.                                        |
| `--allow-dirty`   | Deploy with uncommitted changes.                                             |
| `--env staging`   | Use another section of `deploy.ini`.                                         |
| `--list-backups`  | List the backups stored on the server.                                       |
| `--rollback [NAME]` | Restore a backup (the latest if no name is given).                         |

### 4.5 What runs on the server

```bash
php artisan down --retry=15                # only if maintenance = true
rm -f public/hot
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
php artisan optimize:clear
php artisan optimize                       # config, routes, views, events
php please stache:warm
php please static:clear                    # if clear_static_cache = true
php please search:update --all             # only with --search
chown -R www-data:www-data ... + chmod     # reload_command
php artisan up                             # always, even if a step fails
```

`composer install` also runs Statamic's `statamic:install` hook, which
republishes the Control Panel assets in `public/vendor/`.

---

## 5. Rolling back

```bash
python3 deploy.py --list-backups
python3 deploy.py --rollback                                    # latest backup
python3 deploy.py --rollback 10design.co-20261013-184034-a1b2c3d.tar.gz
```

A rollback extracts the code from the backup over the current files, then runs
the same post-deploy commands (composer install from the restored
`composer.lock`, caches, ownership fix).

Things to know:

- Backups contain **code only** (no `vendor/`, `storage/`, content or uploads),
  because deploys never touch those. Content needs its own server backups.
- Files **added** by the bad deploy stay in place. They're usually harmless
  (an extra template or class); remove them by hand if needed.
- The last `keep_backups` backups are kept (5 by default) in `backup_dir`.

---

## 6. Troubleshooting

**"Uncommitted changes"**: commit, or use `--allow-dirty` for a quick fix you'll commit afterwards.

**"Asset build failed. Is the Docker stack running?"**: run `docker compose up -d`,
then `docker compose exec node npm run build` to see the actual error.

**"No .env in ..."**: the server app folder has no `.env`. Create it on the
server (section 2.3). It is never uploaded.

**"'composer' not found on the server"**: set `composer` in `deploy.ini` to its
full path, e.g. `/usr/local/bin/composer`, or `php8.3 /usr/local/bin/composer`
if the default `php` is a different version.

**`composer install` fails on the server with a PHP version error**: the
server's PHP doesn't match the version `composer.lock` was resolved with. Use
the same PHP version locally (in `docker/php/Dockerfile`) and on the server.

**Permission errors in the Control Panel after a deploy**: the final `chown`
didn't run or failed. Run the commands in section 2.3 by hand.

**`artisan optimize` fails with "Your configuration files are not serializable"**:
a closure was added to a config file. See DOCKER.md, troubleshooting 6.4.

**The site still shows old code after a successful deploy**: possibly opcache
(section 2.4). Template changes can also be held by Statamic's
static cache. Keep `clear_static_cache = true`.

**The site shows Vite dev URLs (`localhost:5178`) or unstyled pages**: a
`public/hot` file exists on the server. The deploy removes it; to fix by hand:
`ssh DigitalOcean10DesignRoot 'rm -f /data/webs/10Design/public/hot'`.

**Health check failed**: open the site and check
`storage/logs/laravel.log` on the server. If needed, roll back
(`python3 deploy.py --rollback`) and investigate locally.

**Every file appears as changed in the preview**: shouldn't happen, since
files are compared by content. If it does, check that `rsync_chmod` and the
line endings of the files (CRLF vs LF) are the same as on the server.

---

## 7. Pulling production data to local

Deploys only go one way. To work locally with production's current content,
copy it down with rsync (read-only on the server):

```bash
cd ~/Dockers/10design
rsync -avz DigitalOcean10DesignRoot:/data/webs/10Design/content/        statamic/content/
rsync -avz DigitalOcean10DesignRoot:/data/webs/10Design/resources/blueprints/ statamic/resources/blueprints/
rsync -avz DigitalOcean10DesignRoot:/data/webs/10Design/resources/fieldsets/  statamic/resources/fieldsets/
rsync -avz DigitalOcean10DesignRoot:/data/webs/10Design/public/assets/  statamic/public/assets/

docker compose exec statamic php please stache:refresh
```

Commit pulled blueprint/fieldset changes before your next deploy, so they
aren't overwritten.

---

## 8. First deployment

The first deployment needs a few extra checks: the server's code may have
drifted from the repo over the past months, and production has never cached
its config before.

### Step 1. Put the files in place (on your Mac)

```bash
cd ~/Dockers/10design
# deploy.py, deploy.ini.example and DEPLOY.md live here, next to docker-compose.yml
cp deploy.ini.example deploy.ini
echo "deploy.ini" >> .gitignore
brew install rsync          # macOS ships an old rsync
```

In `deploy.ini`, check that `host`, `path` and `site_url` are right, and that
`public/` is protected (section 2.5):

```ini
extra_excludes =
    /public/*
extra_includes =
    /public/build/
```

### Step 2. Take a full snapshot of production

The script's backups contain code only. For the first deploy, also keep a copy
of everything, content and uploads included. Check there is enough free disk
space first: the archive can be large because of the media.

```bash
ssh DigitalOcean10DesignRoot 

du -sh /data/webs/10Design
	30G	/data/webs/10Design
	
df -h /root
	Filesystem      Size  Used Avail Use% Mounted on
	/dev/vda1       232G  158G   75G  68% /
	
cd /data/webs
tar -cvzf $(date +"%Y%m%d")-10design.tar.gz --exclude='10Design/storage/debugbar' --exclude='10Design/storage/statamic/glide' --exclude='10Design/vendor' --exclude='10Design/node_modules' --exclude='10Design/.git' 10Design

ls -lh /data/webs/$(date +"%Y%m%d")-10design.tar.gz
-rw-r--r-- 1 root root 2.7G Oct 13 19:28 /data/webs/20261013-10design.tar.gz
```

A DigitalOcean droplet snapshot from the control panel is an alternative.

### Step 3. Check the server matches the local setup

The PHP versions must match at least on major and minor version (8.3 and 8.3).
If they differ, `composer install` on the server may refuse `composer.lock`.
Resolve that before continuing.


In remote:


```bash
ssh DigitalOcean10DesignRoot 

php -v | head -1 
	PHP 8.3.11 (cli) (built: Mar 18 2025 19:13:26) (NTS)
	
composer --version
	Composer version 2.8.9 2025-05-13 14:01:37
	PHP version 8.3.11 (/usr/bin/php8.3)
	Run the "diagnose" command to get more detailed diagnostics output.
	
rsync --version | head -1
	rsync  version 3.3.0  protocol version 31
```

In local

```bash
docker compose exec statamic php -v | head -1
	PHP 8.4.26 (cli) (built: Oct  6 2026 01:25:30) (NTS)
```

Local is `PHP 8.4.26`, remote is `PHP 8.3.11`  
Check whether the current lock file is safe for 8.3

In local

```bash
docker compose exec statamic composer why-not php 8.3.11
	There is no installed package depending on "php" in versions not matching 8.3.11
```

That’s the good result. Every package in your current composer.lock works on PHP 8.3.11, so composer install on the server won’t fail because of the version difference. You can carry on with the first deployment.

#### Stop Composer from picking 8.4-only packages in future

Add this to statamic/composer.json:

```json
"config": {
    "platform": {
        "php": "8.3.11"
    }
}
```

### Step 4. Check for `env()` calls outside config files

Each deploy runs `artisan optimize`, which caches the config. With a cached
config, `env()` returns `null` everywhere **except inside `config/` files**, so a
direct `env()` call anywhere else would silently break:

```bash
grep -rn "env(" statamic/app statamic/routes statamic/resources --include=*.php
	zsh: no matches found: --include=*.php
```

No output means it's fine. Otherwise, move each value into a config file
(`'key' => env('X')` in `config/...`) and read it with `config('...')`.

### Step 5. Commit everything

```bash
git status          # must be clean; commit if not
```

### Step 6. Dry run and review the preview

```bash
python3 deploy.py --dry-run
```

Don't use `--delete` on the first deploy. Go through the list:

| Line                                                    | Meaning                                       |
|---------------------------------------------------------|-----------------------------------------------|
| `<f+++++++++ path`                                      | New file, not on the server yet               |
| `<fc........ path`                                      | File differs from the server's version        |
| Anything in `content/`, `.env`, or `public/` outside `public/build/` | **Stop.** These must never appear. Check `deploy.ini` |

The changed files (`<fc`) matter most on the first run. Compare any you don't
recognise with the server's version:

```bash
diff <(ssh DigitalOcean10DesignRoot cat /data/webs/10Design/resources/blueprints/collections/pages/page.yaml) \
     statamic/resources/blueprints/collections/pages/page.yaml
```

Pay particular attention to:

- **Blueprints and fieldsets.** If someone edited them in the Control Panel on
  production, the local version would overwrite those edits. Pull the server's
  version first (section 7), commit it, and run the dry run again.
- **Templates or code edited directly on the server.** Same approach: bring the
  changes into the repo first, or they'll be lost.

Repeat the dry run until every listed change is intended.

### Step 7. Upload changed files in `public/` by hand

Files in `public/` other than `public/build/` are never deployed (section 2.5).
Upload any that changed since the last deploy, for example the new logo:

```bash
rsync -avz statamic/public/visuals/io-logo.svg DigitalOcean10DesignRoot:/data/webs/10Design/public/visuals/
ssh DigitalOcean10DesignRoot 'chown www-data:www-data /data/webs/10Design/public/visuals/io-logo.svg'
```

To see which public files differ from the server, without copying anything:

```bash
rsync -avzn --checksum --exclude=/build/ --exclude=/vendor/ --exclude=/static/ --exclude=/img/ --exclude=/hot \
  statamic/public/ DigitalOcean10DesignRoot:/data/webs/10Design/public/
```

Media folders will show up in that list if your local copy differs from
production. Only upload the files you actually changed.

### Step 8. Deploy

Pick a quiet time. For extra safety on the first run, set `maintenance = true`
in `deploy.ini` (and back to `false` afterwards, if you prefer).

```bash
python3 deploy.py
```

It builds the assets, shows the same preview and asks
`Deploy ... to production? [y/N]`. After `y` it shows the backup, the upload,
each server command and the health check. Note the rollback command printed at
the end.

### Step 9. Check the live site

- Load the home page and a few other pages, including the news and projects
  listings and a page with images (Glide).
- Log in to the Control Panel, save an entry, upload an image and create a media
  folder. This confirms the permissions are right.
- Try the search and the contact form.
- Look for errors in the log:

  ```bash
  ssh DigitalOcean10DesignRoot 'tail -50 /data/webs/10Design/storage/logs/laravel.log'
  ```

### If something goes wrong

Restore the code from the backup the script made just before deploying:

```bash
python3 deploy.py --rollback
```

The full snapshot from step 2 is a last resort:

```bash
ssh DigitalOcean10DesignRoot 'cd /data/webs && tar -xzf /root/10design-before-first-deploy.tar.gz && chown -R www-data:www-data 10Design'
```

It brings **everything** back to the moment of the snapshot, so content edits
and uploads made since then are overwritten. Files created since then are not
removed. Use it only if the rollback isn't enough.
