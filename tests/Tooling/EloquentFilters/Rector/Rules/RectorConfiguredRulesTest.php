<?php

declare(strict_types=1);

namespace Tests\Tooling\EloquentFilters\Rector\Rules;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\HasFilters;
use Tooling\Rector\Rules\AddInterfaceByClass;
use Tooling\Rector\Rules\AddInterfaceByTrait;
use Tooling\Rector\Rules\AddTraitByInterface;
use Support\Database\Eloquent\Contracts\Filterable;

class RectorConfiguredRulesTest extends TestCase
{
    private array $config;

    public function setUp(): void
    {
        parent::setUp();
        $this->config = require __DIR__ . '/../../../../../tooling/rector/configured-rules.php';
    }

    #[Test]
    public function configuration_exists_to_add_filterable_contract_to_builders(): void
    {
        $this->assertArrayHasKey(AddInterfaceByClass::class, $this->config);
        $this->assertArrayHasKey(Builder::class, $this->config[AddInterfaceByClass::class]);
        $this->assertEquals(Filterable::class, $this->config[AddInterfaceByClass::class][Builder::class]);
    }

    #[Test]
    public function configuration_exists_to_add_filterable_contract_to_builders_when_using_has_filters_trait(): void
    {
        $this->assertArrayHasKey(AddInterfaceByTrait::class, $this->config);
        $this->assertArrayHasKey(HasFilters::class, $this->config[AddInterfaceByTrait::class]);
        $this->assertEquals(Filterable::class, $this->config[AddInterfaceByTrait::class][HasFilters::class]);
    }

    #[Test]
    public function configuration_exists_to_add_has_filters_trait_to_builders_when_using_filterable_contract(): void
    {
        $this->assertArrayHasKey(AddTraitByInterface::class, $this->config);
        $this->assertArrayHasKey(Filterable::class, $this->config[AddTraitByInterface::class]);
        $this->assertEquals(HasFilters::class, $this->config[AddTraitByInterface::class][Filterable::class]);
    }
}