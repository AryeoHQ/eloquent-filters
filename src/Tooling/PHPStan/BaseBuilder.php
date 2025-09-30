<?php

declare(strict_types=1);

namespace Tooling\PHPStan;

use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\FiltersRequests;

/**
 * Not extendable. Example class for static analysis only.
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
final class BaseBuilder extends Builder implements Filterable
{
    use FiltersRequests;
}
