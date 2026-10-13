
## SCSS

###  1. Install Sass & Dependencies

```sh
npm install -D sass
```

### 2. Update `vite.config.js`

Modify your `vite.config.js` file to include SCSS support.

```js
...
        plugins: [
            laravel({
                refresh: true,
                input: [
                    'resources/css/site.css',
                    'resources/scss/app.scss', // Add SCSS file here
                    'resources/js/site.js',
                ]
            })
        ],
...
```


### 3. Create a SCSS File

Make sure you have an SCSS file in `resources/scss/`. If it doesn’t exist, create it:

```sh
mkdir -p resources/scss
touch resources/scss/app.scss
```

Then, open `resources/scss/app.scss` and add:

```scss
@use "child_files/*";

/* Your custom SCSS styles */
body {
    background-color: #f8f9fa;
}
```

### **4. Restart Vite**

```sh
npm run dev
```

