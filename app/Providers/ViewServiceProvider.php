<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Pengaturanumum;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Kirim data ke semua view
        View::composer('*', function ($view) {
            $setting = Pengaturanumum::where('id', 1)->first();
            $view->with('setting', $setting);
        });
    }

    public function register()
    {
        //
    }
}
