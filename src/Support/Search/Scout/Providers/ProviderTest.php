<?php

declare(strict_types=1);

namespace Support\Search\Scout\Providers;

use Laravel\Scout\ScoutServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[CoversClass(Provider::class)]
class ProviderTest extends TestCase
{
    #[Test]
    public function it_registers_scout(): void
    {
        $this->assertTrue($this->app->providerIsLoaded(ScoutServiceProvider::class));
    }
}
