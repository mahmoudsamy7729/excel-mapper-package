<?php

namespace Sam\ExcelMapper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

class ExcelMapperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/excel-import.php', 'excel-import');

    }

    public function boot()
    {
        // Load routes
        Route::middleware('web')
        ->group(__DIR__.'/../routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'excel-mapper');


        // Publish configuration file
        $this->publishes([
            __DIR__.'/../config/excel-import.php' => config_path('excel-import.php'),
        ], 'excel-import-config');

        // Register Livewire components
        Livewire::component('file-upload', \Sam\ExcelMapper\Http\Livewire\FileUpload::class);

    }
}