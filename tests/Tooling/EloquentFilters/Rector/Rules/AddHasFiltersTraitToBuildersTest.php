<?php

declare(strict_types=1);

namespace Tests\Tooling\EloquentFilters\Rector\Rules;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Support\Database\Eloquent\HasFilters;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\Rector\Rules\Provides\ParsesNodes;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tests\Tooling\EloquentFilters\Concerns\GetsFixtures;
use Tooling\EloquentFilters\Rector\Rules\AddHasFiltersTraitToBuilders;

#[CoversClass(AddHasFiltersTraitToBuilders::class)]
class AddHasFiltersTraitToBuildersTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ValidatesInheritance;

    #[Test]
    public function it_adds_trait_to_builders(): void
    {
        $rule = app(AddHasFiltersTraitToBuilders::class);

        $classNode = $this->getClassNode($this->getFixturePath('UserBuilderNoTrait.php'));

        $this->assertFalse($this->inherits($classNode, HasFilters::class));

        $result = $rule->refactor($classNode);

        $this->assertTrue($this->inherits($result, 'HasFilters'));
    }

    #[Test]
    public function it_does_not_add_trait_to_non_builder_classes(): void
    {
        $rule = app(AddHasFiltersTraitToBuilders::class);

        $classNode = $this->getClassNode($this->getFixturePath('NonBuilderClass.php'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }

    #[Test]
    public function it_does_not_add_trait_to_complete_classes(): void
    {
        $rule = app(AddHasFiltersTraitToBuilders::class);

        $classNode = $this->getClassNode($this->getFixturePath('UserBuilder.php'));

        $result = $rule->refactor($classNode);
        
        $this->assertTrue($this->inherits($result, HasFilters::class));
    }
}