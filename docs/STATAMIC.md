
## Create Collections

To add a **"Projects"** link to your **navigation menu** in Statamic, follow these steps:

### 1. Create a new collection "Projects"

 - Create the **content** file `content/collections/projects.yaml` or use the CMS to create a new Collection

 - Create the blueprint file `resources/blueprints/collections/projects/projects.yaml` or use the CMS to create a new Blueprint

 - Create taxonomies if needed

### 2. Create an Index Page for "Projects"

Since a **Collection** doesn't automatically generate an index page, you need to create one:

1. **Go to** `resources/views/` and create a new template:
   ```
   resources/views/projects/index.antlers.html
   ```
2. **Add basic code** to display your projects:
   ```antlers
   {{ collection:projects }}
       <h2><a href="{{ url }}">{{ title }}</a></h2>
   {{ /collection }}
   ```

3. **Ensure a route exists** in `routes/web.php`:
   ```php
   Route::statamic('projects', 'projects.index', ['title' => 'Projects']);
   ```
   - This makes `/projects` load the `projects/index.antlers.html` template.



### 3. Add "Projects" to Navigation

#### **Option 1: Using Statamic’s Navigation UI**
1. **Go to** Statamic Control Panel → **Navigation**.
2. Click on the **Primary Navigation (or your nav set)**.
3. Click **"Add Item"** → Select **"Entry"**.
4. Choose **"Manual URL"** and enter `/projects` as the URL.
5. Set the **label** to `"Projects"`, then **Save**.

#### **Option 2: Add It Manually in Your Template**
If you're manually rendering the navigation, modify your nav template (`resources/views/partials/navigation.antlers.html`):

```antlers
<ul>
    {{ nav from="main_navigation" }}
        <li><a href="{{ url }}">{{ title }}</a></li>
    {{ /nav }}
    <li><a href="/projects">Projects</a></li>  {# Manually add the Projects link #}
</ul>
```

---

## Livewire

- See [LIVEWIRE.md](docs/LIVEWIRE.md)

---

### Hierarchical Taxonomies in Statamic (Workaround Guide)

#### The Problem

Statamic does **not natively support hierarchical taxonomies**.

Even though you can define a `structure:` in a taxonomy's YAML file (e.g., `locations.yaml`), **this has no effect**. The Control Panel will not display a drag-and-drop UI for taxonomy terms like it does for structured collections (such as "Pages").

This is a known limitation — even with Statamic Pro.

#### The Workaround

To simulate hierarchy in a taxonomy (e.g., `Locations`), you can create a **manual parent-child relationship** using a `Taxonomy Terms` field.

##### 1. Update the Taxonomy Blueprint

1. Go to the `Locations` taxonomy blueprint (in the CP or `resources/blueprints/taxonomies/locations.yaml`).
2. Add a new field:

   - **Field Name:** `parent_location`
   - **Field Type:** `Taxonomy Terms`
   - **Taxonomy to Reference:** `locations` (yes, self-referencing)

This allows each term to optionally point to another term as its "parent".


##### 2. Organize Terms Manually

When editing a term (e.g., “London”), you can now select another term (e.g., “United Kingdom”) as its parent.

This manually builds a hierarchy.


##### 3. Displaying the Hierarchy in Templates

You can use Antlers logic to loop through parent terms and their children.

**Example (2 levels deep):**

```antlers
{{ taxonomy:locations }}
    {{ if no_parent_location }}
        <h2>{{ title }}</h2>

        {{ taxonomy:locations where="parent_location:id == {id}" }}
            <h3>– {{ title }}</h3>
        {{ /taxonomy:locations }}

    {{ /if }}
{{ /taxonomy:locations }}
```

You can extend this logic to support deeper nesting as needed.

---

## Add Addons

### Example: Install Statamic Tabs

```sh
composer require eminos/statamic-tabs
```

### Current Addons

- [Tabs](https://statamic.com/addons/kiwikiwi/tabs)
- [Bard Text Color](https://statamic.com/addons/xndbogdan/Statamic-3-Bard-Text-Color)

---

## Additional commands

### Clear cache and static files

```bash
php please stache:clear
php please static:clear
php please cache:clear
php artisan cache:clear
```
Reference to [Statamic Cache](https://statamic.dev/static-caching#by-force)



## Deploy script Ploi

```bash
if [[ {COMMIT_MESSAGE} =~ "[BOT]" ]] && [[ {DEPLOYMENT_SOURCE} == "quick-deploy" ]]; then
    echo "Automatically committed on production. Nothing to deploy."
    {DO_NOT_NOTIFY}
    exit 0
fi

cd {SITE_DIRECTORY}
git pull origin {BRANCH}
{SITE_COMPOSER} install --no-interaction --prefer-dist --optimize-autoloader --no-dev

npm ci
npm run build

{RELOAD_PHP_FPM}

{SITE_PHP} artisan cache:clear
{SITE_PHP} artisan config:cache
{SITE_PHP} artisan route:cache
{SITE_PHP} artisan statamic:stache:warm
{SITE_PHP} artisan queue:restart
{SITE_PHP} artisan statamic:search:update --all
{SITE_PHP} artisan statamic:static:clear
{SITE_PHP} artisan statamic:static:warm --queue

echo "🚀 Application deployed!"
```

---

## Deploy script Forge

```bash
if [[ $FORGE_QUICK_DEPLOY == 1 ]]; then
    if [[ $FORGE_DEPLOY_MESSAGE =~ "[BOT]" ]]; then
        echo "Automatically committed on production. Nothing to deploy."
        exit 0
    fi
fi

cd $FORGE_SITE_PATH
git pull origin $FORGE_SITE_BRANCH
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev

npm ci
npm run build

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

$FORGE_PHP artisan cache:clear
$FORGE_PHP artisan config:cache
$FORGE_PHP artisan route:cache
$FORGE_PHP artisan statamic:stache:warm
$FORGE_PHP artisan queue:restart
$FORGE_PHP artisan statamic:search:update --all
$FORGE_PHP artisan statamic:static:clear
$FORGE_PHP artisan statamic:static:warm --queue
```
