<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;
use Statamic\Facades\Site;
use Livewire\Component;

class ShowPeople extends Component
{
  public $siteHandle;

  public $collectiontax = [];
  public $loader = '';
  public $searchQuery = '';

  public $selectedSlugs = [
    'role' => [],
    'offices'   => [],
  ];


    /**
     * Get sticky entries
     * This method retrieves entries that are marked as sticky in the 'people' collection.
     *
     * @return object
     */
    private function stickyEntries() {
      //Log::info("Projects get entries");
      //Log::info('Current site handle: ' . $this->siteHandle);

      // Sticky entries
      $stickyBuilder = Entry::query()
      ->where('collection', 'people')
      ->where('site', $this->siteHandle)
      ->where('status', 'published')
      ->where('sticky_person', true);

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
   * Get news sorted by the collection structure tree and filtered only those that have full project page
   *
   * As stated in the documentation, to sort manually, use sort="order", and make sure you set the maximum depth to 1 for your collection:
   *
   * @see https://statamic.dev/tags/collection#parameters,
   *
   * @return object
   */
  public function entries() {
      //Log::info("News get entries");
      $stickyEntries = $this->stickyEntries();

      $entries = Entry::query()
          ->where('collection', 'people') //AND query
          ->where('site', $this->siteHandle) // Locale query
          ->where('sticky_person', '!=', true) // Exclude sticky entries
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

      // $entries = $entries->orderBy('date', 'desc')->get();
      $entries = $entries->orderBy('title', 'asc')->get();
      //To sort by "order" make sure you set the maximum depth to 1 for your collection
      //$entries = $entries->orderBy('order')->get();

      // Merge sticky and normal entries
      $allEntries = $stickyEntries->merge($entries);

      // Add search filtering
      if (!empty($this->searchQuery)) {
        $allEntries = $this->searchQuery($this->searchQuery, $allEntries);
      }

      return $allEntries;
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

      //Log::info(request()->path());
      $currentEntry = Entry::query()
         ->where('collection', 'pages') //AND query
         ->where('site', $this->siteHandle) // Locale query
         ->where('slug', 'people')
         ->first();

      //Log::info($currentEntry->get('show_filters'));

      // Assign the 'show_filters' value or a default
      //$this->collectiontax = $currentEntry ? $currentEntry->get('show_filters') : ['offices', 'positions'];
      $this->collectiontax =  ['role', 'offices'];
  }


  public function render() {
      //return view('livewire.show-projects');
      //Log::info("Load Livewire ShowProjects: ". print_r($this->entries(),true));
      return view(
          'livewire.show-people',
          [
              'people' => $this->entries(),
              'collectiontax' => $this->collectiontax,
              'loader' => $this->loader,
              'sitehandle' => $this->siteHandle,
          ]
      );
  }
}
