<?php

use Illuminate\Support\Facades\Route;

// Projects routes
Route::statamic('projects', 'projects.index', [
    'title' => __('strings.projects', [], 'en_UK'),
    'locale' => 'en_UK'
]);
Route::statamic('projects/{slug}', 'projects.index', [
    'title' => __('strings.projects', [], 'en_UK'),
    'locale' => 'en_UK'
]);

Route::statamic('zh/projects', 'projects.index', [
    'title' => __('strings.projects', [], 'zh'),
    'locale' => 'zh'
]);
Route::statamic('zh/projects/{slug}', 'projects.index', [
    'title' => __('strings.projects', [], 'zh'),
    'locale' => 'zh'
]);

// Route::statamic('es/projects', 'projects.index', [
//   'title' => __('strings.projects', [], 'es'),
//   'locale' => 'es_ES'
// ]);

// Ideas routes
Route::statamic('ideas', 'ideas.index', [
    'title' => __('strings.ideas', [], 'en_UK')
]);

Route::statamic('zh/ideas', 'ideas.index', [
    'title' => __('strings.ideas', [], 'zh')
]);

// Route::statamic('es/ideas', 'ideas.index', [
//     'title' => __('strings.ideas', [], 'es')
// ]);

// News routes
Route::statamic('news', 'news.index', [
    'title' => __('strings.news', [], 'en_UK')
]);

Route::statamic('zh/news', 'news.index', [
  'title' => __('strings.news', [], 'zh')
]);

// Route::statamic('es/news', 'news.index', [
//   'title' => __('strings.news', [], 'es')
// ]);


// Search routes
Route::statamic('search', 'search.index', [
    'title' => __('strings.results', [], 'en_UK'),
    'search' => true
]);

Route::statamic('zh/search', 'search.index', [
    'title' => __('strings.results', [], 'zh'),
    'search' => true
]);

// Route::statamic('es/search', 'search.index', [
//   'title' => __('strings.results', [], 'es'),
//   'search' => true
// ]);