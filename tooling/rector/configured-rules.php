<?php

use Support\Search\Database\Contracts\Filterable;
use Support\Search\Database\Contracts\Sortable;
use Support\Search\Database\Provides\HasFilters;
use Support\Search\Database\Provides\HasSort;
use Tooling\Rector\Rules\AddInterfaceByTrait;
use Tooling\Rector\Rules\AddTraitByInterface;

return [
    AddTraitByInterface::class => [
        Filterable::class => HasFilters::class,
        Sortable::class => HasSort::class,
    ],
    AddInterfaceByTrait::class => [
        HasFilters::class => Filterable::class,
        HasSort::class => Sortable::class,
    ],
];
