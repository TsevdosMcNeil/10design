<?php

namespace App\SearchFilters;

class ExcludeFromSearch
{
    public function handle($item)
    {
        return ! $item->exclude_from_search;
    }
}