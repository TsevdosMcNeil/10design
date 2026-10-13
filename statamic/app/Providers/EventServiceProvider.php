<?php
/*
app/Listeners/SyncStickyProjectsFromTerm.php is not longer in use, since the information about taxonomy sticky projects is now stored in the term's YAML.

That mean the protected $listen variable is also no longer needed. however I'm keeping it here for reference, mayber in the future we need it again.
*/

namespace App\Providers;

//use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
//use Statamic\Events\TermSaved;
//use App\Listeners\SyncStickyProjectsFromTerm;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Statamic\Events\EntryDeleted;
use Statamic\Events\TermDeleted;
use Statamic\StaticCaching\StaticCacheManager;

class EventServiceProvider extends ServiceProvider
{
    // protected $listen = [
    //     TermSaved::class => [
    //       SyncStickyProjectsFromTerm::class,
    //     ],
    // ];

    public function boot()
    {
      // Entries (collections)
      Event::listen(EntryDeleted::class, function (EntryDeleted $event) {
        $handle = $event->entry->collectionHandle();
        $urls = data_get(config('statamic.static_caching.invalidation.rules'), "collections.$handle.urls", []);
        if (! empty($urls)) {
            app(StaticCacheManager::class)->invalidateUrls($urls);
        }
      });

      // Terms (taxonomies)
      Event::listen(TermDeleted::class, function (TermDeleted $event) {
        $handle = $event->term->taxonomyHandle();
        $urls = data_get(config('statamic.static_caching.invalidation.rules'), "taxonomies.$handle.urls", []);
        if (! empty($urls)) {
            app(StaticCacheManager::class)->invalidateUrls($urls);
        }
      });
    }


}
