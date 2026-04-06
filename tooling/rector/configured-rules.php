<?php

use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\Contracts\Sortable;
use Support\Database\Eloquent\HasFilters;
use Support\Database\Eloquent\HasSort;
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
