<?php

use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;
use Tooling\Rector\Rules\AddInterfaceByClass;
use Tooling\Rector\Rules\AddTraitByInterface;

return [
    AddInterfaceByClass::class => [
        Builder::class => Filterable::class,
    ],
    AddTraitByInterface::class => [
        Filterable::class => HasFilters::class,
    ],
];
