<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\NewsSourceInterface;
use App\Services\NewsSources\NewsAPISource;
use App\Services\NewsSources\BBCNewsSource;
use App\Services\NewsSources\OpenNewsSource;


class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('NewsAPISource', NewsAPISource::class);
        $this->app->bind('BBCNewsSource', BBCNewsSource::class);
        $this->app->bind('OpenNewsSource', OpenNewsSource::class);
        $this->app->bind('NewYorkTimesSource', NewYorkTimesSource::class);
        $this->app->bind('ReutersSource', ReutersSource::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
