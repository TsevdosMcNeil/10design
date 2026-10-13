<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Asset;
use Statamic\Facades\Site;
use Livewire\Component;

class ShowIdeas extends Component {

  public $currentItemsNumber = 5; //Default number of items on first load
  public $perPage = 4; // Number of items to load in each pagination

  public $siteHandle;

  public $collectiontax = [];
  public $loader = '';
  public $searchQuery = '';

  public $selectedSlugs = [
    'categories' => [],
  ];

  /**
   * Get news sorted by the collection structure tree and filtered only those that have full project page
   *
   * As stated in the documentation, to sort manually, use sort="order", and make sure you set the maximum depth to 1 for your collection:
   *
   * @see https://statamic.dev/tags/collection#parameters,
   *
   * @return object
   */
  private function entries() {
    $entries = Entry::query()
      ->where('collection', 'ideas') //AND query
      ->where('site', $this->siteHandle) // Locale query
      ->where('status', 'published'); //AND query

    //AND query (filtering)
    foreach ($this->selectedSlugs as $key  => $selectedSlug) {
        //Log::info("Taxonomy ".$key.': ');
        if(count($selectedSlug)) {
            $inTax = [];

            //Log::info( print_r($selectedSlug,true));
            foreach ($selectedSlug as $slug) {
                $filter = $key.'::'.$slug;

                if (in_array($filter, $inTax)) {
                    continue;
                }

                $inTax[] = $filter;
            }
            // OR query
            $entries = $entries->whereTaxonomyIn($inTax);
            //$entries = $entries->whereTaxonomy('specialisms::'.$this->selectedspecialismslug);
        }
    }

    $entries = $entries->orderBy('order', 'asc')->get();
    //To sort by "order" make sure you set the maximum depth to 1 for your collection
    //$allEntries = $builder->orderBy('order')->get();

    // Add search filtering
    if (!empty($this->searchQuery)) {
        $entries = $this->searchQuery($this->searchQuery, $entries);
    }

    return $entries;
  }

  /**
   * Get entries grouped by page
   *
   * @return object
   */
  public function setOfEntries() {
    $allEntries = $this->entries();
    // Paginate manually
    $paged = $allEntries->forPage(1, $this->currentItemsNumber);

    return $paged;
  }

  public function loadMore() {
    $this->currentItemsNumber += $this->perPage;
    $this->dispatch('ideas-loaded'); // Livewire v3 uses `dispatch()`
  }

  public function hasMoreEntries() {

    $allEntries = $this->entries();

    return $allEntries->count() > $this->currentItemsNumber;
  }

  private function searchQuery($searchQuery, $entries) {
      $query = strtolower($searchQuery);

      return $entries->filter(function ($entry) use ($query) {
          $searchable = collect([
              $entry->get('title'),
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
  }

  public function mount() {

    $this->siteHandle = Site::current()->handle(); // capture the current site

    $currentEntry = Entry::query()
      ->where('collection', 'pages') //AND query
      ->where('site', $this->siteHandle) // Locale query
      ->where('slug', 'ideas')
      ->first();

    // Assign the 'show_filters' value or a default
    // $this->collectiontax = $currentEntry ? $currentEntry->get('show_filters') : ['categories'];
    //Debug, force the collectiontax for now
    $this->collectiontax = ['categories'];
  }


  public function render() {
      //return view('livewire.show-projects');
      return view(
          'livewire.show-ideas',
          [
              'ideas' => $this->setOfEntries(),
              'hasMore' => $this->hasMoreEntries(),
              'collectiontax' => $this->collectiontax,
              'loader' => $this->loader,
              'sitehandle' => $this->siteHandle,
          ]
      );
  }

}
