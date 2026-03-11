<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;

/** @extends RuleTestCase<BuilderImplementsFilterableContract> */
#[CoversClass(BuilderImplementsFilterableContract::class)]
class BuilderImplementsFilterableContractTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new BuilderImplementsFilterableContract;
    }

    #[Test]
    public function it_passes_valid_filterable_class(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilder.php')], []);
    }

    #[Test]
    public function it_passes_when_no_filters(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilderNoFilters.php')], []);
    }

    #[Test]
    public function it_fails_when_missing_contract(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilderNoContract.php')], [
            [
                'Classes with Filter attributes must implement the Support\Database\Eloquent\Contracts\Filterable contract.',
                17,
            ],
        ]);
    }
}
