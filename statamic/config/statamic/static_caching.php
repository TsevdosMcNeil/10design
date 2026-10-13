<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active Static Caching Strategy
    |--------------------------------------------------------------------------
    |
    | To enable Static Caching, you should choose a strategy from the ones
    | you have defined below. Leave this null to disable static caching.
    |
    */

    'strategy' => env('STATAMIC_STATIC_CACHING_STRATEGY', null),

    /*
    |--------------------------------------------------------------------------
    | Caching Strategies
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the static caching strategies for your
    | application as well as their drivers.
    |
    | Supported drivers: "application", "file"
    |
    */

    'strategies' => [

        'half' => [
            'driver' => 'application',
            'expiry' => null,
        ],

        'full' => [
            'driver' => 'file',
            'path' => public_path('static'),
            'lock_hold_length' => 0,
            'warm_concurrency' => 10,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Exclusions
    |--------------------------------------------------------------------------
    |
    | Here you may define a list of URLs to be excluded from static
    | caching. You may want to exclude URLs containing dynamic
    | elements like contact forms, or shopping carts.
    |
    */

    'exclude' => [

        'class' => null,

        'urls' => [
            //'/projects?*', // We exclude the projects filtering page (for now) because it contains content from Livewire
            '/site.webmanifest',
            '/sitemap.xml',
            '/sitemaps.xml',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Invalidation Rules
    |--------------------------------------------------------------------------
    |
    | Here you may define the rules that trigger when and how content would be
    | flushed from the static cache. See the documentation for more details.
    | If a custom class is not defined, the default invalidator is used.
    |
    | https://statamic.dev/static-caching
    |
    */

    'invalidation' => [
      'class' => null,
      //'rules' => 'all', <-- All cache is invalidated when a new content is uploaded
      'rules' => [
          'collections' => [
              'projects' => [ //“when an entry in theprojects collection is saved, we should invalidate the /projects page, the taxonomies pages and the home page.”
                  'urls' => [
                      '/projects',
                      '/projects/*',
                      '/projects/*/*',
                      '/',
                      '/zh/projects',
                      '/zh/projects/*',
                      '/zh/projects/*/*',
                      '/zh'

                  ]
              ],
              'ideas' => [ //“when an entry in the ideas collection is saved, we should invalidate the /ideas page, and the home page.”
                  'urls' => [
                      '/ideas',
                      '/',
                      '/zh/ideas',
                      '/zh',
                  ]
              ],
              'news' => [ //“when an entry in the news collection is saved, we should invalidate the /news page, and the home page.”
                  'urls' => [
                      '/news',
                      '/',
                      '/zh/news',
                      '/zh',
                  ]
              ],
              'people' => [ //“when an entry in the people collection is saved, we should invalidate the /people page, and the home page.”
                  'urls' => [
                      '/people',
                      '/',
                      '/zh/people',
                      '/zh',
                  ]
              ],
              'offices' => [ //“when an entry in the offices collection is saved, we should invalidate the /contact ”
                  'urls' => [
                      '/contact',
                      '/zh/contact',
                  ]
              ],
          ],
          'taxonomies' => [
              'specialisms' => [ //“when a term in specialisms taxonomy is updated, we should invalidate the projects page and the taxonomies pages”
                  'urls' => [
                      '/projects',
                      '/projects/*',
                      '/projects/*/*',
                      '/zh/projects',
                      '/zh/projects/*',
                      '/zh/projects/*/*',
                  ]
              ],
              'themes' => [ //“when a term in themes taxonomy is updated, we should invalidate the projects page and the taxonomies pages”
                  'urls' => [
                      '/projects',
                      '/projects/*',
                      '/projects/*/*',
                      '/zh/projects',
                      '/zh/projects/*',
                      '/zh/projects/*/*',
                  ]
              ],
              'locations' => [ //“when a term in locations taxonomy is updated, we should invalidate the projects page and the taxonomies pages”
                  'urls' => [
                      '/projects',
                      '/projects/*',
                      '/projects/*/*',
                      '/zh/projects',
                      '/zh/projects/*',
                      '/zh/projects/*/*',
                  ]
              ],
            ],
          'globals' => [ // “when the settings global set is saved, we invalidate all urls”
              'settings' => [
                  'urls' => [
                      '/*'
                  ]
              ]
          ],
          'navigation' => [ //when the links navigation is saved, we invalidate all urls”
              'links' => [
                  'urls' => [
                      '/*'
                  ]
              ]
          ]
      ]
    ],



    /*
    |--------------------------------------------------------------------------
    | Ignoring Query Strings
    |--------------------------------------------------------------------------
    |
    | Statamic will cache pages of the same URL but with different query
    | parameters separately. This is useful for pages with pagination.
    | If you'd like to ignore the query strings, you may do so.
    |
    */

    'ignore_query_strings' => false,

    'allowed_query_strings' => [
        //
    ],

    'disallowed_query_strings' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Nocache
    |--------------------------------------------------------------------------
    |
    | Here you may define where the nocache data is stored.
    |
    | https://statamic.dev/tags/nocache#database
    |
    | Supported drivers: "cache", "database"
    |
    */

    'nocache' => 'cache',

    'nocache_js_position' => 'body',

    /*
    |--------------------------------------------------------------------------
    | Replacers
    |--------------------------------------------------------------------------
    |
    | Here you may define replacers that dynamically replace content within
    | the response. Each replacer must implement the Replacer interface.
    |
    */

    'replacers' => [
        \Statamic\StaticCaching\Replacers\CsrfTokenReplacer::class,
        \Statamic\StaticCaching\Replacers\NoCacheReplacer::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Warm Queue
    |--------------------------------------------------------------------------
    |
    | Here you may define the queue name and connection
    | that will be used when warming the static cache.
    |
    */

    'warm_queue' => env('STATAMIC_STATIC_WARM_QUEUE'),

    'warm_queue_connection' => env('STATAMIC_STATIC_WARM_QUEUE_CONNECTION'),

];
