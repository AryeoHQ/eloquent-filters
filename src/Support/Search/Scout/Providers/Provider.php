<?php

declare(strict_types=1);

namespace Support\Search\Scout\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Scout\ScoutServiceProvider;

class Provider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(ScoutServiceProvider::class);
    }
}
