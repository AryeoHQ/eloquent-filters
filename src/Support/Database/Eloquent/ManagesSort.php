<?php

declare(strict_types=1);

namespace Support\Database\Eloquent;

use Support\Primitives\Sort;

trait ManagesSort
{
    final public function sort(null|Sort|string $sort): static
    {
        $sort = $sort === null ? $sort : Sort::make($sort);

        return $this->when(
            $sort,
            fn () => $this->orderBy($sort->field->toString(), $sort->direction->value)
                ->when($sort->field->toString() !== $this->defaultKeyName(),
                    // Apply tie-breaker sort
                    fn () => $this->orderBy($this->defaultKeyName(), $sort->direction->value)),
        );
    }
}
