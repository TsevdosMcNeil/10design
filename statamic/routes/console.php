<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('clone-collection-en-to-zh', function () {
  $collection = $this->ask('Enter the collection name (e.g., projects, news, ideas, offices, pages, people, themes):');
  $basePath = base_path("content/collections/{$collection}");

  $enPath = "{$basePath}/en";
  $zhPath = "{$basePath}/中文";
  $backupPath = "{$basePath}/.zh_back";

  if (!is_dir($enPath)) {
      $this->error("English folder not found at: {$enPath}");
      return;
  }

  if (!is_dir($zhPath)) {
      $this->warn("Chinese folder not found. Creating an empty one.");
      mkdir($zhPath, 0777, true);
  }

  // Backup the current zh folder
  if (is_dir($backupPath)) {
      $this->warn("Backup already exists at {$backupPath}. Deleting it to create a fresh one.");
      \Illuminate\Support\Facades\File::deleteDirectory($backupPath);
  }

  rename($zhPath, $backupPath);
  $this->info("Chinese folder backed up to: {$backupPath}");

  // Copy English folder to Chinese folder
  \Illuminate\Support\Facades\File::copyDirectory($enPath, $zhPath);
  $this->info("English content cloned to: {$zhPath}");

  Artisan::call('cache:clear');
  $this->info("English cloning completed for the '{$collection}' collection.");
});



Artisan::command('identify-duplicates', function () {
    $duplicates = [];
    $shouldFixDuplicates = $this->confirm('Should Duplicate IDs be replaced with fresh IDs?');

    $items = collect(\Illuminate\Support\Facades\File::allFiles(__DIR__.'/../content'))
        ->filter(function ($file) {
            return $file->isFile();
        })
        ->map(function (SplFileInfo $file) {
            $data = \Statamic\Facades\YAML::parse(file_get_contents($file->getRealPath()));

            return [
                'path' => $file->getRealPath(),
                'stache_id' => isset($data['id']) ? $data['id'] : null,
            ];
        })
        ->reject(function ($item) {
            return is_null($item['stache_id']);
        });

    $items->each(function ($item) use (&$duplicates, $items) {
        $itemsWithCurrentId = $items->where('stache_id', $item['stache_id']);

        if ($itemsWithCurrentId->count() > 1) {
            if (array_key_exists($item['stache_id'], $duplicates)) {
                $duplicates[$item['stache_id']] = array_merge($duplicates[$item['stache_id']], [
                    $item['path'],
                ]);
            } else {
                $duplicates[$item['stache_id']] = [
                    $item['path'],
                ];
            }
        }
    });

    foreach ($duplicates as $id => $items) {
        $this->error("Duplicate ID Found: {$id}");

        foreach ($items as $item) {
            $this->line($item);

            if ($shouldFixDuplicates) {
                $contents = file_get_contents($item);
                $contents = str_replace($id, \Statamic\Facades\Stache::generateId(), $contents);
                file_put_contents($item, $contents);
            }
        }

        $this->line('');
    }

    if ($shouldFixDuplicates) {
        Artisan::call('cache:clear');
        $this->info('Duplicate IDs have been replaced and your Cache has been cleared. Have a good day!');
    }
});


Artisan::command('set-origins', function () {
  $collection = $this->ask('Enter the collection name (e.g., projects, news, ideas, offices, pages, people, themes):');

  $this->info("Mapping Chinese entries to English originals in the '{$collection}' collection...");

  $items = collect(\Illuminate\Support\Facades\File::allFiles(__DIR__.'/../content/collections/' . $collection))
      ->filter(fn($file) => $file->isFile())
      ->map(function (SplFileInfo $file) {
          $data = \Statamic\Facades\YAML::parse(file_get_contents($file->getRealPath()));
          return [
              'path' => $file->getRealPath(),
              'filename' => $file->getFilename(),
              'stache_id' => $data['id'] ?? null,
              'data' => $data,
          ];
      })
      ->reject(fn($item) => is_null($item['stache_id']));

  $enItems = $items->filter(fn($item) => str_contains($item['path'], "/$collection/en/"))
                   ->keyBy('filename');

  $zhItems = $items->filter(fn($item) => str_contains($item['path'], "/$collection/中文/"));

  foreach ($zhItems as $zhItem) {
      $filename = $zhItem['filename'];
      if (!isset($enItems[$filename])) {
          $this->warn("No English match found for: {$filename}");
          continue;
      }

      $originId = $enItems[$filename]['stache_id'];
      $lines = file($zhItem['path']);
      $idIndex = null;

      foreach ($lines as $i => $line) {
          if (str_starts_with(trim($line), 'id:')) {
              $idIndex = $i;
              break;
          }
      }

      if (!is_null($idIndex)) {
          $alreadyHasOrigin = collect($lines)->contains(fn($line) => str_starts_with(trim($line), 'origin:'));
          if ($alreadyHasOrigin) {
              $this->line("Origin already exists for: {$filename}");
              continue;
          }

          array_splice($lines, $idIndex + 1, 0, "origin: {$originId}\n");
          file_put_contents($zhItem['path'], implode('', $lines));
          $this->info("Added origin to: {$filename}");
      } else {
          $this->error("No 'id:' field found in: {$filename}");
      }
  }

  Artisan::call('cache:clear');
  $this->info("Origin mapping completed for the '{$collection}' collection.");
});
