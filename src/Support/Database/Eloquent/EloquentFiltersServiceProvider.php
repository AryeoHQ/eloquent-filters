<?php

declare(strict_types=1);

namespace Support\Database\Eloquent;

use Illuminate\Support\ServiceProvider;

class EloquentFiltersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootViews();
    }

    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../../../resources/views/rector/rules', 'eloquent-filters.rector.rules.samples');
    }
}
