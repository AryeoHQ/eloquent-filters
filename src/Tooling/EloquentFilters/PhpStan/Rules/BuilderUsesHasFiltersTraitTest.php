<?php

declare(strict_types=1);

namespace Tooling\EloquentFilters\PhpStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Tooling\Concerns\GetsFixtures;

/** @extends RuleTestCase<BuilderUsesHasFiltersTrait> */
#[CoversClass(BuilderUsesHasFiltersTrait::class)]
class BuilderUsesHasFiltersTraitTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new BuilderUsesHasFiltersTrait;
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
    public function it_fails_when_missing_trait(): void
    {
        $this->analyse([$this->getFixturePath('UserBuilderNoTrait.php')], [
            [
                'Classes with Filter attributes must use the Support\Database\Eloquent\HasFilters trait.',
                17,
            ],
        ]);
    }
}
