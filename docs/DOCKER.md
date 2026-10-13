# 10design — Local Docker setup (Statamic)

Local development stack for the 10design Statamic site.

| Service    | Container           | Purpose                                   | Host port            |
|------------|---------------------|-------------------------------------------|----------------------|
| `statamic` | `statamic_10design` | PHP-FPM running the Statamic/Laravel app  | — (internal `9000`)  |
| `caddy`    | `caddy_10design`    | HTTPS web server, proxies PHP and Vite    | `8464`               |
| `node`     | `node_10design`     | Node/Vite for building front-end assets   | `127.0.0.1:5178`     |

`caddy` and `node` use the `local-only` profile, so they only run locally (enabled by `COMPOSE_PROFILES=local-only` in `.env`).

Local URL: **https://10design.localhost:8464**

---

## 1. Requirements

- Docker Desktop
- [mkcert](https://github.com/FiloSottile/mkcert) (for trusted local HTTPS certificates)
- Access to the `TsevdosMcNeil/10design` GitHub repository
- The `~/Dockers/default` model project (templates for compose, Caddy and Docker files)

---

## 2. Create the project

### 2.1 Project folder

```bash
mkdir ~/Dockers/10design
cd ~/Dockers/10design
```

Or clone the repository instead:

```bash
cd ~/Dockers/
git clone https://github.com/TsevdosMcNeil/10design
```

> **Never put a GitHub token in the clone URL or in these notes.** Use SSH
> (`git clone git@github.com:TsevdosMcNeil/10design.git`) or let Git's
> credential helper / `gh auth login` store your credentials.

### 2.2 SSL certificates

```bash
cd ~/Dockers/10design
mkdir -p ./certs
mkcert -cert-file ./certs/local.pem -key-file ./certs/local-key.pem \
  10design.localhost pma.10design.localhost
```

### 2.3 (Optional) PHP Dockerfile and ini files

Only needed if you are not copying them from the model project:

```bash
mkdir -p ./docker/php/conf.d
touch ./docker/php/Dockerfile
touch ./docker/php/conf.d/custom.ini
touch ./docker/php/conf.d/opcache.ini
```

### 2.4 Copy files from the model project

```bash
cd ~/Dockers/10design

cp ~/Dockers/default/env.statamic.example ./
cp ~/Dockers/default/docker-compose.statamic.example.yml ./
cp ~/Dockers/default/docker-compose.statamic.remote.yml ./
cp ~/Dockers/default/Caddyfile.statamic.example ./
cp -R ~/Dockers/default/statamic ./statamic
cp -R ~/Dockers/default/docker_statamic ./docker

cp env.statamic.example .env
cp docker-compose.statamic.example.yml docker-compose.yml
cp Caddyfile.statamic.example Caddyfile
cp statamic/env.example statamic/.env
```

> **Do not copy `docker-compose.statamic.override.yml` to
> `docker-compose.override.yml`** unless you remove the `ports:` of the
> `node` service from `docker-compose.yml`. See
> [Troubleshooting → Port "already in use"](#61-node-container-fails-address-already-in-use).
> In this project the loopback port lives directly in `docker-compose.yml`
> and there is no override file.

### 2.5 Edit `.env`

```bash
vi .env
```

Replace `default` with `10design` and set unique ports for this project:

```env
APP_NAME="10design"

STATAMIC_CONTAINER=statamic_10design
STATAMIC_HOST=10design.localhost
# SSL port locally; FPM port on the remote server
STATAMIC_SSL_PORT=8464

NODE_CONTAINER=node_10design
NODE_PORT=5178

CADDY_CONTAINER=caddy_10design

# Local only — comment out on the remote server
COMPOSE_PROFILES=local-only
```

> Put comments on their own line. Inline comments after a value
> (`NODE_PORT=5178 # 5173`) work in Compose but can leak into the value in
> other `.env` readers.

**Choosing free ports.** Every local project needs its own `STATAMIC_SSL_PORT` and `NODE_PORT`:

```bash
# Ports this project will use (as resolved by Compose)
docker compose config | grep -A3 ports

# Is any container (running or stopped) using the port?
docker ps -a --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" | grep -E "NAMES|5178"

# Is anything on the Mac listening on it?
sudo lsof -nP -iTCP:5178 -sTCP:LISTEN
```

### 2.6 Files that normally need no changes

- `./docker/php/Dockerfile`
- `./docker/php/conf.d/custom.ini`
- `./docker/php/conf.d/opcache.ini`
- `./docker/node/Dockerfile`
- `docker-compose.yml`
- `Caddyfile`

---

## 3. Start the stack

```bash
cd ~/Dockers/10design

docker compose pull
docker compose up -d --build
```

If `COMPOSE_PROFILES=local-only` is not set in `.env`, add the profile flag:

```bash
docker compose --profile local-only up -d --build
```

Check it's running:

```bash
docker compose ps
```

```
NAME                ...  PORTS
caddy_10design      ...  0.0.0.0:8464->8464/tcp, [::]:8464->8464/tcp
node_10design       ...  127.0.0.1:5178->5178/tcp
statamic_10design   ...  9000/tcp
```

### 3.1 Smoke test (before installing Statamic)

```bash
mkdir -p statamic/public
printf '<?php\necho "Hello, World!";\n' > statamic/public/index.php

curl -k https://10design.localhost:8464   # -k: curl may not trust the local cert
# Hello, World!
```

Then open https://10design.localhost:8464 in the browser.

If it fails, check the logs:

```bash
docker compose logs caddy
docker compose logs statamic
```

---

## 4. Install the Statamic app

The repository must include `composer.lock` and `package-lock.json`.

```bash
cd ~/Dockers/10design

# Clone the repository if not done yet
git clone https://github.com/TsevdosMcNeil/10design
```

### 4.1 PHP dependencies

```bash
docker compose exec statamic composer install
```

### 4.2 App key

Only if `APP_KEY` in `statamic/.env` is empty:

```bash
docker compose exec statamic php artisan key:generate
```

### 4.3 Front-end assets

```bash
docker compose exec node npm ci        # or `npm i` if there is no package-lock.json yet
docker compose exec node npm run build
```

### 4.4 Clear caches and optimize

```bash
docker compose exec statamic php artisan config:clear
docker compose exec statamic php artisan cache:clear
docker compose exec statamic php artisan view:clear
docker compose exec statamic php artisan route:clear
docker compose exec statamic php artisan optimize
```

### 4.5 Build the search indexes

```bash
docker compose exec statamic php please search:update --all
```

---

## 5. Day-to-day commands

**Recreate after minor changes**

```bash
docker compose down
docker compose up -d --force-recreate
```

**Quick reload after Dockerfile / compose changes**

```bash
docker compose down
docker compose up -d --build
docker compose ps
```

**Rebuild from scratch**

```bash
# Option 1
docker compose down
docker compose pull
docker compose build --no-cache
docker compose up -d

# Option 2 (also clears the Docker build cache)
docker compose down
docker builder prune -af
docker compose build --no-cache statamic
docker compose up -d
docker compose ps
```

> `docker compose down -v` also deletes the project's **named volumes**
> (e.g. `composer_cache`, or a database/Redis volume if one is added later).
> The app code in `./statamic` is a bind mount and is not affected. Only use
> `-v` when you actually want to wipe that data.

---

## 6. Troubleshooting

### 6.1 Node container fails: "address already in use"

```
Error response from daemon: failed to set up container networking: driver failed
programming external connectivity on endpoint node_10design (...): failed to listen
on TCP socket: address already in use
```

**Cause:** Compose loads `docker-compose.override.yml` automatically and
**appends** its `ports:` to the ones in `docker-compose.yml` instead of
replacing them. With the port defined in both files, the node service tried
to publish the same host port twice (`0.0.0.0:5178` and `127.0.0.1:5178`)
and collided with itself, whatever port number was used.

**How to spot it:**

```bash
ls docker-compose*.yml          # is there an override file?
docker compose config node      # are there two entries under `ports:`?
```

**Fix:** define the node port in one place only. In this project it lives in
`docker-compose.yml` (loopback only) and the override file was deleted:

```yaml
node:
  profiles: ["local-only"]   # Node/Vite only runs locally
  ports:
    - "127.0.0.1:${NODE_PORT:-5173}:${NODE_PORT:-5173}"
```

Alternatively, keep the override and make it replace the list:

```yaml
node:
  ports: !override
    - "127.0.0.1:${NODE_PORT:-5173}:${NODE_PORT:-5173}"
```

**Not the cause (red herrings):** stopped containers listed with
`Exited (255)` and a port still shown in `docker ps -a` are leftovers from a
Docker crash. They're worth cleaning up with `docker rm <name>`, but they
were not the problem here.

### 6.2 `artisan optimize` fails: class file not found in `vendor/`

```
include(/var/www/html/vendor/composer/../statamic/cms/src/Data/ContainsSupplementalData.php):
Failed to open stream: No such file or directory
```

**Cause:** an incomplete `vendor/` folder (copied or committed with missing
files). `composer install` trusts `vendor/composer/installed.json`, assumes
everything is installed and skips it, so the missing files are never restored.

**Fix:** reinstall `vendor/` from scratch. Run the `rm` inside the
container's shell, otherwise zsh tries to expand `*` on the Mac and aborts
the whole command (`zsh: no matches found`):

```bash
docker compose exec statamic sh -c 'rm -rf vendor bootstrap/cache/*.php'
docker compose exec statamic composer install
```

**Prevention:** keep `vendor/` and `node_modules/` out of the repository
(`.gitignore`) so they are always built from the lock files.

### 6.3 `composer install` fails: "Unable to write file at location: to://build/assets/..."

```
League\Flysystem\UnableToWriteFile
Unable to write file at location: to://build/assets/expand-bold-9d6e3af4.js.
Script @php artisan statamic:install --ansi handling the post-autoload-dump event returned with error code 1
```

**Cause:** `statamic:install` (run after `composer install`) republishes the
Control Panel assets into `public/vendor/statamic`. The existing files were
owned by a different user than the one PHP runs as in the container, so they
couldn't be overwritten.

**Fix:** delete the published assets as root (they are generated) and
publish them again:

```bash
docker compose exec -u root statamic rm -rf public/vendor/statamic
docker compose exec statamic php artisan statamic:install
```

If the container user can't write to `public/` at all, fix ownership
(use the user printed by `docker compose exec statamic whoami`):

```bash
docker compose exec -u root statamic chown -R www-data:www-data public storage bootstrap/cache
```

**Prevention:** enable the `UID`/`GID` build args in `docker/php/Dockerfile`
and `docker-compose.yml` so the containers write files as your Mac user.

### 6.4 `artisan optimize` fails: "Your configuration files are not serializable"

```
LogicException
Your configuration files are not serializable.
bootstrap/cache/config.php:1235
Error::("Call to undefined method Closure::__set_state()")
```

**Cause:** `config:cache` writes all config into one PHP file, and closures
(anonymous functions) can't be written out. The search `filter` options in
`config/statamic/search.php` were closures. This never showed up on the
remote server because config wasn't being cached there. Config read live on
each request handles closures fine.

**How to find closures in config:**

```bash
docker compose exec statamic sh -c 'grep -rnE "function\s*\(|fn\s*\(" config/'
```

**Fix:** move the logic into a class and reference the class from config.

`statamic/app/SearchFilters/ExcludeFromSearch.php`:

```php
<?php

namespace App\SearchFilters;

class ExcludeFromSearch
{
    public function handle($item)
    {
        return ! $item->exclude_from_search;
    }
}
```

`config/statamic/search.php` (both the `exact` and `default` indexes):

```php
'filter' => \App\SearchFilters\ExcludeFromSearch::class,
```

Then:

```bash
docker compose exec statamic php artisan optimize
docker compose exec statamic php please search:update --all
```

> **Local tip:** a cached config makes Laravel ignore `.env` changes until
> `php artisan config:clear` is run. Locally you can skip `optimize`; on the
> remote server it should be used.