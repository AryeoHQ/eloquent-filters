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
            function (self $query) use ($sort) {
                $sortField = $sort->field->toString();
                $model = $query->getModel();
                $keyName = $model->getKeyName();
                $qualifiedKeyName = $model->qualifyColumn($keyName);

                return $query->orderBy($sortField, $sort->direction->value) // @phpstan-ignore-line staticMethod.dynamicCall
                    ->when(
                        $sortField !== $keyName && $sortField !== $qualifiedKeyName,
                        // Apply tie-breaker sort
                        fn (self $tiebreakerQuery) => $tiebreakerQuery->orderBy($qualifiedKeyName, $sort->direction->value) // @phpstan-ignore-line staticMethod.dynamicCall
                    );
            },
        );

        return $this;
    }
}
