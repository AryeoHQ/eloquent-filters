<?php

declare(strict_types=1);

namespace Support\Search\Providers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Search\Database;
use Support\Search\OpenSearch;
use Support\Search\Scout;
use Tests\TestCase;

#[CoversClass(Provider::class)]
class ProviderTest extends TestCase
{
    #[Test]
    public function it_registers_the_sub_providers(): void
    {
        $this->assertTrue($this->app->providerIsLoaded(Database\Providers\Provider::class));
        $this->assertTrue($this->app->providerIsLoaded(OpenSearch\Providers\Provider::class));
        $this->assertTrue($this->app->providerIsLoaded(Scout\Providers\Provider::class));
    }
}
