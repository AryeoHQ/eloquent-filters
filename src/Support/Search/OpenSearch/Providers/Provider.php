<?php

declare(strict_types=1);

namespace Support\Search\OpenSearch\Providers;

use DirectoryTree\OpenSearchClient\OpenSearchClientServiceProvider;
use DirectoryTree\OpenSearchMigrations\OpenSearchMigrationsServiceProvider;
use DirectoryTree\OpenSearchScoutDriver\OpenSearchScoutServiceProvider;
use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(OpenSearchClientServiceProvider::class);
        $this->app->register(OpenSearchScoutServiceProvider::class);
        $this->app->register(OpenSearchMigrationsServiceProvider::class);
    }
}
