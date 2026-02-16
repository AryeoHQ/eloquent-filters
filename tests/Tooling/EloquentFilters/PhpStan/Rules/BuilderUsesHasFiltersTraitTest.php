<?php

namespace Tests\Tooling\EloquentFilters\PhpStan\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\EloquentFilters\PhpStan\Rules\BuilderUsesHasFiltersTrait;
use PHPStan\Reflection\ReflectionProvider;

#[CoversClass(BuilderUsesHasFiltersTrait::class)]
class BuilderUsesHasFiltersTraitTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new BuilderUsesHasFiltersTrait(
            self::getContainer()->getByType(ReflectionProvider::class)
        );
    }

    private function getFixturePath(string $filename): string
    {
        return __DIR__.'/../../../../Fixtures/'.$filename;
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
