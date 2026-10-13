<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Asset;
use Statamic\Facades\Site;
use Livewire\Component;

class ShowNews extends Component
{
  public $currentItemsNumber = 12; //Default number of items on first load
  public $perPage = 4; // Number of items to load in each pagination

  public $siteHandle;

  public $collectiontax = [];
  public $loader = '';
  public $searchQuery = '';

  public $selectedSlugs = [
      'categories'   => [],
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
   * Get news sorted by the collection structure tree and filtered only those that have full project page
   *
   * As stated in the documentation, to sort manually, use sort="order", and make sure you set the maximum depth to 1 for your collection:
   *
   * @see https://statamic.dev/tags/collection#parameters,
   *
   * @return object
   */
  private function entries() {
      //Log::info("News get entries for ". $this->siteHandle);

      $entries = Entry::query()
          ->where('collection', 'news') //AND query
          ->where('site', $this->siteHandle) // Locale query
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

      $entries = $entries->orderBy('date', 'desc')->get();
      //To sort by "order" make sure you set the maximum depth to 1 for your collection
        //$entries = $entries->orderBy('order')->get();

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
    //Log::info("ShowNews setOfEntries for ". $this->siteHandle);
    $allEntries = $this->entries();
    // Paginate manually
    $paged = $allEntries->forPage(1, $this->currentItemsNumber);

    return $paged;
  }

  public function loadMore() {
    //Log::info("ShowNews loadMore for ". $this->siteHandle);
    $this->currentItemsNumber += $this->perPage;
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

  public function getHasActiveFiltersProperty(){
    $hasSearch = !empty($this->searchQuery);
    $hasFilters = collect($this->selectedSlugs)->flatten()->isNotEmpty();

    return $hasSearch || $hasFilters;
  }


    public function mount()
    {
      $this->siteHandle = Site::current()->handle();

      $currentEntry = Entry::query()
        ->where('collection', 'pages') //AND query
        ->where('site', $this->siteHandle) // Locale query
        ->where('slug', 'news')
        ->first();

      //Log::info($currentEntry->get('show_filters'));

      // Assign the 'show_filters' value or a default
      $this->collectiontax = $currentEntry ? $currentEntry->get('show_filters') : ['categories'];
    }


    public function render()
    {
        //return view('livewire.show-projects');
        //Log::info("Load Livewire ShowProjects: ". print_r($this->entries(),true));
        return view(
            'livewire.show-news',
            [
                'news' => $this->setOfEntries(),
                'hasMore' => $this->hasMoreEntries(),
                'collectiontax' => $this->collectiontax,
                'loader' => $this->loader,
                'hasActiveFilters' => $this->hasActiveFilters,
                'sitehandle' => $this->siteHandle,
            ]
        );
    }
}
