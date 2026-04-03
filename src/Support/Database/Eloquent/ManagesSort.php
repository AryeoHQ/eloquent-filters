<?php

declare(strict_types=1);

namespace Support\Database\Eloquent;

use Support\Primitives\Sort;

trait ManagesSort
{
    final public function sort(null|Sort|string $sort): static
    {
        $sort = $sort === null ? $sort : Sort::make($sort);

        $this->when(
            $sort,
            fn (self $query) => $query->orderBy($sort->field->toString(), $sort->direction->value) // @phpstan-ignore-line staticMethod.dynamicCall
                ->when(
                    $sort->field->toString() !== $query->defaultKeyName(),
                    // Apply tie-breaker sort
                    fn (self $tiebreakerQuery) => $tiebreakerQuery->orderBy($tiebreakerQuery->defaultKeyName(), $sort->direction->value) // @phpstan-ignore-line staticMethod.dynamicCall
                ),
        );

        return $this;
    }
}
