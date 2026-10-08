<?php

declare(strict_types=1);

namespace Support\Search\OpenSearch\Facades;

use DirectoryTree\OpenSearchAdapter\Indices\IndexManagerInterface;
use Orchestra\Testbench\Attributes\WithConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Support\Scout\Company;
use Tests\TestCase;

#[CoversClass(IndexManager::class)]
#[WithConfig('scout.driver', 'opensearch')]
class IndexManagerTest extends TestCase
{
    #[Test]
    public function fake_replaces_the_bound_index_manager(): void
    {
        $fake = IndexManager::fake();

        $this->assertSame($fake, $this->app->make(IndexManagerInterface::class));
    }

    #[Test]
    public function fake_records_a_created_index(): void
    {
        DocumentManager::fake();
        $indices = IndexManager::fake();

        $this->artisan('scout:index', ['name' => Company::class])->assertSuccessful();

        $indices->assertCreated((new Company)->searchableAs());
    }

    #[Test]
    public function fake_records_a_deleted_index(): void
    {
        DocumentManager::fake();
        $indices = IndexManager::fake();

        $this->artisan('scout:delete-index', ['name' => (new Company)->searchableAs()])->assertSuccessful();

        $indices->assertDeleted((new Company)->searchableAs());
    }
}
