<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\Rector\Rules;

use PhpParser\Node;
use Tooling\Rector\Rules\Rule;
use PhpParser\Node\Stmt\Class_;
use Tooling\Rules\Attributes\NodeType;
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Filterable;
use Tooling\Rector\Rules\Definitions\Attributes\Definition;

#[NodeType(Class_::class)]
#[Definition('Add the Filterable contract to the eloquent builder class')]
final class AddFilterableContractToBuilders extends Rule
{
    public function shouldHandle(Node $node): bool
    {
        return $node->extends?->toString() === Builder::class
            || $node->extends?->toString() === 'Builder';
    }

    public function handle(Node $node): null|Node
    {
        $node = $this->ensureInterfaceIsImplemented($node, Filterable::class);

        return $node;
    }
}
