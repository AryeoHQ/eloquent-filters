<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\Rector\Rules;

use PhpParser\Node;
use Tooling\Rector\Rules\Rule;
use PhpParser\Node\Stmt\Class_;
use Tooling\Rules\Attributes\NodeType;
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\HasFilters;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;

#[NodeType(Class_::class)]
#[Definition('Add the HasFilters trait to the eloquent builder class')]
final class AddHasFiltersTraitToBuilders extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $node->extends?->toString() === Builder::class
            || $node->extends?->toString() === 'Builder';
    }

    public function handle(Node $node): null|Node
    {
        $node = $this->ensureTraitIsUsed($node, HasFilters::class);

        return $node;
    }
}
