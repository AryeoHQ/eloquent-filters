<?php

declare(strict_types=1);

use Tooling\Rector\Rules\AddInterfaceByClass;
use Tooling\Rector\Rules\AddInterfaceByTrait;
use Tooling\Rector\Rules\AddTraitByInterface;
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;

return [
    AddInterfaceByClass::class => [
        Builder::class => Filterable::class,
    ],
    AddInterfaceByTrait::class => [
        HasFilters::class => Filterable::class,
    ],
    AddTraitByInterface::class => [
        Filterable::class => HasFilters::class,
    ],
];