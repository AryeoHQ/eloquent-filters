<?php

declare(strict_types=1);

namespace Tooling\Search\PhpStan\Scout;

use Laravel\Scout\Builder;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use Support\Search\Scout\Attributes\UseScoutBuilder;
use Tooling\PhpStan\Rules\Rule;
use Tooling\Rules\Attributes\NodeType;

/**
 * @extends Rule<Class_>
 */
#[NodeType(Class_::class)]
final class UseScoutBuilderMustReferenceScoutBuilder extends Rule
{
    public function shouldHandle(Node $node, Scope $scope): bool
    {
        return $this->hasAttribute($node, UseScoutBuilder::class);
    }

    public function handle(Node $node, Scope $scope): void
    {
        collect($node->attrGroups)
            ->flatMap(fn (AttributeGroup $group): array => $group->attrs)
            ->filter(fn (Attribute $attr): bool => $attr->name instanceof FullyQualified
                && $attr->name->toString() === UseScoutBuilder::class
                && $attr->args !== [])
            ->reject(fn (Attribute $attr): bool => $this->referencesScoutBuilder($attr, $scope))
            ->each(fn (Attribute $attr) => $this->error(
                message: class_basename(UseScoutBuilder::class).' must reference '.Builder::class.'.',
                line: $attr->getStartLine(),
                identifier: 'UseScoutBuilder.Builder.required'
            ));
    }

    private function referencesScoutBuilder(Attribute $attr, Scope $scope): bool
    {
        return collect($scope->getType($attr->args[0]->value)->getConstantStrings())
            ->every(fn (ConstantStringType $class): bool => (new ObjectType(Builder::class))
                ->isSuperTypeOf(new ObjectType($class->getValue()))
                ->yes());
    }
}
