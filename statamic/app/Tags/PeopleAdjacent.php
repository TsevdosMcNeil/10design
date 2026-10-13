<?php

namespace App\Tags;

use Illuminate\Support\Facades\Log;
use Statamic\Entries\Entry;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;

class PeopleAdjacent extends Tags
{
  public $siteHandle;

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

    $stickyEntries = $stickyBuilder
        ->orderBy('sticky_position', 'asc')
        ->get();

    return $stickyEntries;
  }


  /**
   * The {{ people_adjacent }} tag.
   *
   * @return string|array
   */
  public function index()
  {
    $this->siteHandle = Site::current()->handle(); // capture the current site
    // Current entry context

    // @var \Statamic\Entries\Entry $current
    // In Statamic templates, the current entry is commonly 'page'
    // and/or 'entry' may be an augmented array, not an Entry object.
    $entryContext = $this->context->get('entry') ?? $this->context->get('page');

    $currentId = null;

    // 1) If it's a real Entry object
    if ($entryContext instanceof \Statamic\Entries\Entry) {
        $currentId = $entryContext->id();
        //Log::info("option 1 - currentId: " . $currentId);
    }

    // 2) If it's an augmented array (common in Antlers)
    if (! $currentId && is_array($entryContext) && isset($entryContext['id'])) {
        $currentId = $entryContext['id'];
        //Log::info("option 2 - currentId: " . $currentId);
    }

    // 3) If Statamic put id directly in context
    if (! $currentId && $this->context->get('id')) {
        $currentId = $this->context->get('id');
        //Log::info("option 3 - currentId: " . $currentId);
    }

    // 4) As a fallback: resolve by slug in this collection + site
    if (! $currentId && $this->context->get('slug')) {
      $slug = $this->context->get('slug');

      $resolved = Entry::query()
          ->where('collection', 'people')
          ->where('site', $this->siteHandle)
          ->where('slug', $slug)
          ->first();

      if ($resolved) {
          $currentId = $resolved->id();
          //Log::info("option 4 - currentId: " . $currentId);
      }
    }

    // Test: $currentId = "91370215-3e23-40db-a4a4-21ee3a5207ae";

    // If still no current entry, don't render buttons
    if (! $currentId) {
      return $this->parse(['prev' => null, 'next' => null]);
    }

    $stickyEntries = $this->stickyEntries();

    $entries = Entry::query()
      ->where('collection', 'people') //AND query
      ->where('site', $this->siteHandle) // Locale query
      ->where('sticky_person', '!=', true) // Exclude sticky entries
      ->where('status', 'published') //AND query
      ->orderBy('title', 'asc')
      ->get();

    // Merge sticky and normal entries
    $allEntries = $stickyEntries->merge($entries)->values();


    $i = $allEntries->search(fn($e) => $e->id() === $currentId);

    $prev = ($i !== false) ? $allEntries->get($i - 1) : null;
    $next = ($i !== false) ? $allEntries->get($i + 1) : null;

    return $this->parse([
      'prev' => $prev ? ['url' => $prev->url(), 'title' => $prev->get('title')] : null,
      'next' => $next ? ['url' => $next->url(), 'title' => $next->get('title')] : null,
    ]);
  }

  /**
   * The {{ people_adjacent:example }} tag.
   *
   * @return string|array
   */
  public function example()
  {
    return true;
  }
}
