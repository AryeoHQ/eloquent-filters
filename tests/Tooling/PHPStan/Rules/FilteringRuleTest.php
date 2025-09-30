<?php

namespace Tests\Tooling\PHPStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\PHPStan\Rules\FilteringRule;

#[CoversClass(FilteringRule::class)]
class FilteringRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new FilteringRule;
    }

    private function getFixturePath(string $filename): string
    {
        return __DIR__.'/../../../Fixtures/Variations/'.$filename;
    }

    public function test_it_passes_valid_filterable_class(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilder.php')], []);
    }

    public function test_it_passes_when_no_filters(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilderNoFilters.php')], []);
    }

    public function test_it_fails_when_missing_trait(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilderNoTrait.php')], [
            [
                'Classes with Filter attributes must use the Support\Database\Eloquent\FiltersRequests trait.',
                17,
            ],
        ]);
    }

    public function test_it_fails_when_missing_contract(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilderNoContract.php')], [
            [
                'Classes with Filter attributes must implement the Support\Database\Eloquent\Contracts\Filterable contract.',
                17,
            ],
        ]);
    }
}
