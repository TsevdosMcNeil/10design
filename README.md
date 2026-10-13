# 10 Design

A Statamic website for [10Design](https://10design.co/) based in [Statamic Starter Kit](https://github.com/pablorica/10sb) 


[![version](https://img.shields.io/badge/version-1.2.2-green.svg)](https://semver.org)


## Production

[https://10design.co/](https://10design.co/)

- https://10design.co/
- https://10sb.co/




## Staging Version (DEPRECATED)

[https://10sb.codigo.co.uk/](https://10sb.codigo.co.uk/)

- login: 10design
- pwd: tmstudio

### Staging `public` folder

[https://10sb.codigo.co.uk/public.tar.gz](https://10sb.codigo.co.uk/public.tar.gz)

```bash
wget --user 10design --password tmstudio https://10sb.codigo.co.uk/public.tar.gz
```

### Staging `content` folder

[https://10sb.codigo.co.uk/content.tar.gz](https://10sb.codigo.co.uk/content.tar.gz)

```bash
wget --user 10design --password tmstudio https://10sb.codigo.co.uk/content.tar.gz
```


## Installation instructions

- See [DOCKER.md](docs/DOCKER.md)

1. run `composer install`
2. run `php please make:user`
3. run `npm i` && `npm run dev`


### Design

#### Breeakpoints

 - `sm` -> 640px    
 - `md` -> 768px
 - `lg` -> 1024px
 - `xl` -> 1280px
 - `2xl` -> 1536px
 - `3xl` -> 1920px


---

## Compilation errors

### Vite Error

If there is a Vite Error when running
```bash
➜  npm run build

> build
> vite build

failed to load config from /data/webs/artform.localhost/vite.config.js
error during build:
TypeError: Cannot read properties of undefined (reading 'startsWith')
```

**Solution**

It can be due a wrong value in .env
`APP_URL="http://wrongsite.co.uk"`

Or a wrong hot file in public, in that case delete it
```bash
rm public/hot
```

### Valet Error

```bash
➜ npm run watch

> watch
> vite

error when starting dev server:
Error: Unable to find Valet certificate files for your host [artform.localhost]. Ensure you have run "valet secure".
```

**Solution**

This is because the local server has a SSL certificate not recognizable by Laravel. Probably the server is managed by MAMP and Laravel was expecting Valet.

In that case simply remove the SSL from youyr local server and use HTTP instead of HTTPS.

---

## Tailwind

- See [TAILWIND.md](docs/TAILWIND.md)

---

## SCSS

- See [SCSS.md](docs/SCSS.md)

---

## Add JS Libraries

### 1. Example: Install Tiny Slider

```sh
npm install tiny-slider
```

### 2. Add custom JS

Edit `resources/js/site.js`

```js
...
import tinyslider from './modules/tinyslider';
...

// Call custom modules.
const CDG = {

    onreadyFunctions: function() {
        //consoleHello('CDG is ready');
        //preloader();
        tinyslider();

        window.addEventListener("resize", function(){
            //consoleHello('window has resized');
            if(window.innerWidth < 768){
                //consoleHello('narrow');
            }
            else{
                //consoleHello('wide');
            }
         });
    },

    onloadFunctions: function() {
        //consoleHello('CDG is loaded');
    }
};

CDG.onreadyFunctions();

window.onload = function(event) {
    CDG.onloadFunctions();
};

```

---

## Statamic

- See [STATAMIC.md](docs/STATAMIC.md)

---

## Livewire

- See [LIVEWIRE.md](docs/LIVEWIRE.md)

---

## Environment file contents


### Local

```env
Dump your .env values here with sensitive data removed. The following is a production example that uses full static caching:
APP_NAME="10 Design"
APP_ENV=local
APP_KEY="base64:NOmJ6oP6y2BQ6Pf93ObOHMC0B/Tfws+m6oYAFNTX6Kc="
APP_DEBUG=true
DEBUGBAR_ENABLED=true
APP_TIMEZONE="Europe/London"
APP_URL="http://10sb.localhost"

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME="${APP_NAME}"
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"

STATAMIC_LICENSE_KEY=""
STATAMIC_THEME=business
STATAMIC_PRO_ENABLED=false
STATAMIC_STACHE_WATCHER=auto
STATAMIC_STATIC_CACHING_STRATEGY=null
STATAMIC_CACHE_TAGS_ENABLED=false
STATAMIC_REVISIONS_ENABLED=false
STATAMIC_GRAPHQL_ENABLED=false
STATAMIC_API_ENABLED=false
STATAMIC_GIT_ENABLED=false
STATAMIC_GIT_PUSH=false
STATAMIC_GIT_DISPATCH_DELAY=5

IMAGE_MANIPULATION_DRIVER=imagick

#STATAMIC_CUSTOM_CMS_NAME=
#STATAMIC_CUSTOM_LOGO_NAV_URL=
#STATAMIC_CUSTOM_DARK_LOGO_URL=
STATAMIC_CUSTOM_LOGO_OUTSIDE_URL="/visuals/io-logo.svg"
#STATAMIC_CUSTOM_FAVICON_URL=
#STATAMIC_CUSTOM_CSS_URL=

GOOGLE_MAPS_API_KEY=
```

## NGINX config

Add the following to your NGINX config __inside the server block__ enable static resource caching:
```
expires $expires;
```

And this __outside the server block__:
```
map $sent_http_content_type $expires {
    default    off;
    text/css    max;
    ~image/    max;
    application/javascript    max;
    application/octet-stream    max;
}
```

---

## INSTAGRAMs

- See [INSTAGRAM.md](docs/INSTAGRAM.md)

---

## NGINX config

If you have a NGINX server, use these instructions for the configuration file

Add the following to your NGINX config __inside the server block__ to enable static resource caching:
```
expires $expires;
```

And this __outside the server block__:
```
map $sent_http_content_type $expires {
	default off;
	text/css max;
	~image/ max;
	application/javascript max;
	application/octet-stream max;

}
```

---

##  Uploading Changes to the Production Server

Follow these steps to deploy updates to the production environment for **10Design**.


### 1. Connect to the Server via SSH

> **Note:** Before connecting via SSH, make sure your **public key** has been uploaded to the remote server.

```bash
ssh -i <path-to-your-private-key> root@161.35.164.126
```


### 2. Navigate to the Project Directory

```bash
cd /data/webs/10DigitalOcean.codigo.co.uk
```


### 3. Pull the Latest Changes from GitHub (main branch)

Replace `<your-git-user>` and `<your-git-token>` with your actual GitHub username and personal access token:

```bash
git pull 'https://<your-git-user>:<your-git-token>@github.com/pablorica/10sb.git' main
```


### 4. Clear Caches

```bash
php please cache:clear
php please stache:clear
php please static:clear
php artisan cache:clear
```


### 5. Rebuild Assets

```bash
npm run build
```

### 6. Set Permissions

```bash
chown -R www-data:www-data /data/webs/10DigitalOcean.codigo.co.uk
chmod -R 755 /data/webs/10DigitalOcean.codigo.co.uk
```

### 7. Check Git Status (Optional)

To verify that everything is clean and up to date:

```bash
cd /data/webs/10DigitalOcean.codigo.co.uk/
git status
```


### 8. Rebuild Static Cache (Optional)

It's a good idea to pre-warm the static cache for the most frequently visited pages. This prevents the site from slowing down when those pages are accessed for the first time after a deployment. To do this, simply trigger the following URLs—each one will be rebuilt and cached automatically:

```bash
curl -s https://10design.co/ > /dev/null
curl -s https://10design.co/studio > /dev/null
curl -s https://10design.co/studio/our-story > /dev/null
curl -s https://10design.co/studio/sustainability > /dev/null
curl -s https://10design.co/studio/culture > /dev/null
curl -s https://10design.co/studio/design-approach > /dev/null
curl -s https://10design.co/projects > /dev/null
curl -s https://10design.co/people > /dev/null
curl -s https://10design.co/expertise > /dev/null
curl -s https://10design.co/ideas > /dev/null
curl -s https://10design.co/news > /dev/null
curl -s https://10design.co/contact > /dev/null

# Specialisms
curl -s https://10design.co/projects/specialisms/civic-cultural > /dev/null
curl -s https://10design.co/projects/specialisms/commercial > /dev/null
curl -s https://10design.co/projects/specialisms/education-science > /dev/null
curl -s https://10design.co/projects/specialisms/hospitality-wellness > /dev/null
curl -s https://10design.co/projects/specialisms/masterplanning-urban-design > /dev/null
curl -s https://10design.co/projects/specialisms/mixed-use > /dev/null
curl -s https://10design.co/projects/specialisms/residential > /dev/null
curl -s https://10design.co/projects/specialisms/retail > /dev/null

# Themes
curl -s https://10design.co/theme/adaptive-re-use-retrofit > /dev/null
curl -s https://10design.co/theme/branded-residential > /dev/null
curl -s https://10design.co/theme/data-driven-design > /dev/null
curl -s https://10design.co/theme/waterfront-development > /dev/null
curl -s https://10design.co/theme/shed > /dev/null
```

---

## Masilchimp Integration


[MAILCHIMP.md](https://github.com/pablorica/10sb/blob/main/MAILCHIMP.md)

---

## Updating Statamic


[UPDATING.md](https://github.com/pablorica/10sb/blob/main/UPDATING.md)


## Acknowledgements

* [Statamic Starter Kit](https://github.com/pablorica/10sb)

## Copyright and License

Copyright 2025 10Design website released under the [MIT](https://github.com/pablorica/10sb/blob/main/LICENSE) license.

## Versioning

We use [SemVer](https://semver.org/) for versioning.

### Changelog

[CHANGELOG.md](https://github.com/pablorica/10sb/blob/main/CHANGELOG.md)
