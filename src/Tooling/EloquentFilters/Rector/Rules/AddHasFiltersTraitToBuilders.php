<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\Rector\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Database\Eloquent\HasFilters;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;
use Tooling\Rector\Rules\Rule;
use Tooling\Rector\Rules\Samples\Attributes\Sample;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[Definition('Add the HasFilters trait to the eloquent builder class')]
#[NodeType(Class_::class)]
#[Sample('eloquent-filters.rector.rules.samples')]
final class AddHasFiltersTraitToBuilders extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $this->inherits($node, Builder::class)
            && $this->doesNotInherit($node, HasFilters::class);
    }

    public function handle(Node $node): Node
    {
        $node = $this->addTrait($node, HasFilters::class);

        return $node;
    }
}
