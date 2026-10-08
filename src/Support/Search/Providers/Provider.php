<?php

declare(strict_types=1);

namespace Support\Search\Providers;

use Illuminate\Support\ServiceProvider;
use Support\Search\Database;
use Support\Search\OpenSearch;
use Support\Search\Scout;

class Provider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(Database\Providers\Provider::class);
        $this->app->register(OpenSearch\Providers\Provider::class);
        $this->app->register(Scout\Providers\Provider::class);
    }
}
