<?php

declare(strict_types=1);

namespace Tests\Tooling\EloquentFilters\Rector\Rules;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tooling\Rector\Testing\ParsesNodes;
use Tooling\Rector\Testing\ResolvesRectorRules;
use PHPUnit\Framework\Attributes\CoversClass;
use Support\Database\Eloquent\Contracts\Filterable;
use Tooling\Rector\Rules\Provides\ValidatesInheritance;
use Tests\Tooling\EloquentFilters\Concerns\GetsFixtures;
use Tooling\EloquentFilters\Rector\Rules\AddFilterableContractToBuilders;

#[CoversClass(AddFilterableContractToBuilders::class)]
class AddFilterableContractToBuildersTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ResolvesRectorRules;
    use ValidatesInheritance;

    #[Test]
    public function it_adds_contract_to_builders(): void
    {
        $rule = $this->resolveRule(AddFilterableContractToBuilders::class);

        $classNode = $this->getClassNode($this->getFixturePath('UserBuilderNoContract.php'));

        $this->assertFalse($this->inherits($classNode, 'Contract'));

        $result = $rule->refactor($classNode);

        $this->assertTrue($this->inherits($result, Filterable::class));
    }

    #[Test]
    public function it_does_not_add_contract_to_non_builder_classes(): void
    {
        $rule = $this->resolveRule(AddFilterableContractToBuilders::class);

        $classNode = $this->getClassNode($this->getFixturePath('NonBuilderClass.php'));

        $result = $rule->refactor($classNode);

        $this->assertNull($result);
    }

    #[Test]
    public function it_does_not_add_contract_to_complete_classes(): void
    {
        $rule = $this->resolveRule(AddFilterableContractToBuilders::class);

        $classNode = $this->getClassNode($this->getFixturePath('UserBuilder.php'));

        $result = $rule->refactor($classNode);
        
        $this->assertNull($result);
    }
}