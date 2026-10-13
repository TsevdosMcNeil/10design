<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Asset;
use Statamic\Facades\Term;
use \Statamic\Facades\Taxonomy;
use Statamic\Facades\Site;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProjects extends Component
{

    use WithPagination;

    public $currentItemsNumber = 12; //Default number of items on first load
    public $perPage = 8; // Number of items to load in each pagination

    public $siteHandle;

    public $projectax = [];
    public $loader = '';
    public $searchQuery = '';

    public $selectedSlugs = [
        'specialisms' => [],
        'themes'      => [],
        'locations'   => [],
    ];

    /*
     Important!!! To sort by date, the collection yaml file should have

      date: true

      This is the same as enablig "Publish Dates" in the CMS

      If this is not set, the orderby date will not work.
    */
    public $listOrder = [
        'order'     => 'date',
        'direction' => 'desc'
    ];



    /**
     * Get sticky entries
     * This method retrieves entries that are marked as sticky in the 'projects' collection.
     *
     * @return object
     */
    private function stickyEntries() {
      //Log::info("stickyEntries");
      //Log::info('Current site handle: ' . $this->site);

      // Sticky entries
      $stickyBuilder = Entry::query()
      ->where('collection', 'projects')
      ->where('site', $this->siteHandle)
      ->where('status', 'published')
      ->where('sticky_project', true);

      // Apply taxonomy filters
      foreach ($this->selectedSlugs as $key => $selectedSlug) {
          if (count($selectedSlug)) {
              $inTax = [];
              foreach ($selectedSlug as $slug) {
                  $inTax[] = $key . '::' . $slug;
              }
              $stickyBuilder = $stickyBuilder->whereTaxonomyIn($inTax);
          }
      }

      $stickyEntries = $stickyBuilder
          ->orderBy('sticky_position', 'asc')
          ->get();

      return $stickyEntries;
    }

    /**
     * Get projects sorted by the collection structure tree and filtered only those that have full project page
     *
     * As stated in the documentation, to sort manually, use sort="order", and make sure you set the maximum depth to 1 for your collection:
     *
     * @see https://statamic.dev/tags/collection#parameters,
     *
     * @return object
     */
    private function entries() {
        //Log::info("Projects get entries");
        //Log::info('Current site handle: ' . $this->site);

        $stickyEntries = $this->stickyEntries();

        // Non-sticky entries
        $normalBuilder = Entry::query()
            ->where('collection', 'projects')
            ->where('site', $this->siteHandle)
            ->where('status', 'published')
            ->where('sticky_project', '!=', true);

        // Same taxonomy filters
        foreach ($this->selectedSlugs as $key => $selectedSlug) {
            if (count($selectedSlug)) {
                $inTax = [];
                foreach ($selectedSlug as $slug) {
                    $inTax[] = $key . '::' . $slug;
                }
                $normalBuilder = $normalBuilder->whereTaxonomyIn($inTax);
            }
        }

        $normalEntries = $normalBuilder
            ->orderBy('date', 'desc')
            ->get();

        // Merge sticky and normal entries
        $allEntries = $stickyEntries->merge($normalEntries);
        //$allEntries = $builder->orderBy('date', 'desc')->get();
        //To sort by "order" make sure you set the maximum depth to 1 for your collection
        //$allEntries = $builder->orderBy('order')->get();

        // filter by search
        if (!empty($this->searchQuery)) {
          //Log::info("Applying search query: " . $this->searchQuery);
          $allEntries = $this->searchQuery($this->searchQuery, $allEntries);
        }

        return $allEntries;
    }

    /**
     * Get the total number of entries in the 'projects' collection.
     *
     * @return int
     */
    public function getEntriesCount() {
        //Log::info("Projects get entries count");
        $allEntries = $this->entries();
        return $allEntries->count();
    }


    /**
     * Get entries grouped by page
     *
     * @return object
     */
    public function setOfEntries(
      $resetSearch = null
    ) {
      //Log::info("setOfEntries");

      if ($resetSearch) {
          // Reset search query if requested
          $this->searchQuery = '';
      }

      $allEntries = $this->entries();

      // Paginate manually (DISABLED: use Javascript for infinite scroll)
      //$paged = $allEntries->forPage(1, $this->currentItemsNumber);
      //return $paged;

      return $allEntries;
    }

    public function loadMore() {
      $this->currentItemsNumber += $this->perPage;
    }

    /**
     * Check if there are more entries to load
     *
     * @return bool
     */
    public function hasMoreProjects() {

      // Disable this for now, use Javascript for infinite scroll
      //$allEntries = $this->entries();
      //return $allEntries->count() > $this->currentItemsNumber;

      return false;
    }


    public function list() {
        //Log::info("Projects get entries");


        // Fetch the global set for 'loader'
        $globalSet = GlobalSet::findByHandle('loading_animation');

        // Check if the global set exists and has the 'loading_gif' field
        $loadingGifPath = $globalSet && $globalSet->inDefaultSite()->has('loading_gif')
            ? $globalSet->inDefaultSite()->get('loading_gif')
            : null;

        if ($loadingGifPath) {
            $asset = Asset::all()->firstWhere('path', $loadingGifPath);
            //Log::info('Resolved Asset:', ['url' => $asset?->url()]);
            $this->loader = $asset?->url();
        } else {
            $this->loader = '';
        }

        //Log::info("Livewire loader");
        //Log::info( print_r($loadingGifPath,true));
        //Log::info( print_r($this->loader,true));


        $entries = Entry::query()
            ->where('collection', 'projects') //AND query
            ->where('site', $this->siteHandle)
            ->where('status', 'published'); //AND query

        //AND query
        foreach ($this->selectedSlugs as $key  => $selectedSlug) {
            //Log::info("Taxonomy ".$key.': ');
            if(count($selectedSlug)) {
                //Log::info( print_r($selectedSlug,true));
                $inTax = [];
                foreach ($selectedSlug as $slug) {
                    $inTax[] = $key.'::'.$slug;
                }
                // OR query
                $entries = $entries->whereTaxonomyIn($inTax);
                //$entries = $entries->whereTaxonomy('specialisms::'.$this->selectedspecialismslug);
            }
        }

        $entries = $entries->orderBy(
            $this->listOrder['order'],
            $this->listOrder['direction']
        )->get();


        // Add search filtering
        if (!empty($this->searchQuery)) {
            $entries = $this->searchQuery($this->searchQuery, $entries);
        }

        return $entries;
    }

    private function searchQuery($searchQuery, $entries) {
        //Log::info("Search query: " . $searchQuery);
        $query = strtolower($searchQuery);

        $entries = $entries->filter(function ($entry) use ($query) {
            $searchable = collect([
                $entry->get('title'),
                $entry->get('subtitle'),
                $entry->get('project_team'),
                $entry->get('project_photography'),
                $entry->get('location_area'),
            ]);

            // 🔍 Add page_builder content blocks (flattened)
            $pageBuilder = $entry->get('page_builder') ?? [];

            foreach ($pageBuilder as $section) {
                if (!isset($section['columns'])) continue;

                foreach ($section['columns'] as $column) {
                    if (!isset($column['column_article'])) continue;

                    foreach ($column['column_article'] as $block) {
                        if (isset($block['content'])) {
                            foreach ($block['content'] as $bit) {
                                if (isset($bit['text'])) {
                                    $searchable->push($bit['text']);
                                }
                            }
                        }

                        // Paragraphs inside scrollcard_card_title, etc.
                        if (isset($block['attrs']['values']['scrollcard_card_title'])) {
                            foreach ($block['attrs']['values']['scrollcard_card_title'] as $paragraph) {
                                if (isset($paragraph['content'])) {
                                    foreach ($paragraph['content'] as $textItem) {
                                        if (isset($textItem['text'])) {
                                            $searchable->push($textItem['text']);
                                        }
                                    }
                                }
                            }
                        }

                        // Paragraph content directly
                        if (isset($block['content'])) {
                            foreach ($block['content'] as $item) {
                                if (isset($item['text'])) {
                                    $searchable->push($item['text']);
                                }
                            }
                        }

                        // Fallback to heading content
                        if (isset($block['type']) && $block['type'] === 'heading' && isset($block['content'])) {
                            foreach ($block['content'] as $textItem) {
                                if (isset($textItem['text'])) {
                                    $searchable->push($textItem['text']);
                                }
                            }
                        }
                    }
                }
            }

            $haystack = strtolower($searchable->implode(' '));
            return str_contains($haystack, $query);
        });

        return $entries;

    }

    public function mount() {
        //Log::info(request()->path());

        $this->siteHandle = Site::current()->handle(); // capture the current site

        $currentEntry = Entry::query()
          ->where('collection', 'pages') //AND query
          ->where('site', $this->siteHandle) // Locale query
          ->where('slug', 'projects')
          ->first();

       //Log::info("show_filters");
        //Log::info(print_r($currentEntry ? $currentEntry->get('show_filters') : [], true));

        // Assign the 'show_filters' value or a default
        $this->projectax = $currentEntry ? $currentEntry->get('show_filters') : ['specialisms', 'themes', 'locations'];

        //Log::info('projectax: ' . print_r($this->projectax,true));

    }


    public function render() {
        //return view('livewire.show-projects');
        //Log::info("Load Livewire ShowProjects: ". print_r($this->entries(),true));
        return view(
            'livewire.show-projects',
            [
                'projects' => $this->setOfEntries(),
                'hasMore' => $this->hasMoreProjects(),
                'list' => $this->list(),
                'projectax' => $this->projectax,
                'loader' => $this->loader,
                'sitehandle' => $this->siteHandle,
                'totalEntries' => $this->getEntriesCount(),
                'currentItemsNumber' => $this->currentItemsNumber,
                'perPage' => $this->perPage,
            ]
        );
    }
}
