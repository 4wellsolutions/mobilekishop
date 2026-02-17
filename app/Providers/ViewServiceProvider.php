<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Filter;
use App\Models\CategoryPriceRange;
use App\Models\Country;
use Request;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(
            ['includes.*', 'frontend.*', 'layouts.*'],
            \App\Http\View\Composers\SidebarComposer::class
        );
    }
}