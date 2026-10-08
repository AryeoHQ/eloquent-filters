<?php

declare(strict_types=1);

namespace Support\Search\OpenSearch\Providers;

use DirectoryTree\OpenSearchClient\OpenSearchClientServiceProvider;
use DirectoryTree\OpenSearchMigrations\OpenSearchMigrationsServiceProvider;
use DirectoryTree\OpenSearchScoutDriver\OpenSearchScoutServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Provider::class)]
class ProviderTest extends TestCase
{
    #[Test]
    public function it_registers_the_opensearch_packages(): void
    {
        $this->assertTrue($this->app->providerIsLoaded(OpenSearchClientServiceProvider::class));
        $this->assertTrue($this->app->providerIsLoaded(OpenSearchScoutServiceProvider::class));
        $this->assertTrue($this->app->providerIsLoaded(OpenSearchMigrationsServiceProvider::class));
    }
}
