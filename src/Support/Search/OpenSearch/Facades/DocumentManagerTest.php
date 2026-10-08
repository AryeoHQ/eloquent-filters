<?php

declare(strict_types=1);

namespace Support\Search\OpenSearch\Facades;

use DirectoryTree\OpenSearchAdapter\Documents\Document;
use DirectoryTree\OpenSearchAdapter\Documents\DocumentManagerInterface;
use Orchestra\Testbench\Attributes\WithConfig;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Support\Scout\Company;
use Tests\TestCase;

#[CoversClass(DocumentManager::class)]
#[WithConfig('scout.driver', 'opensearch')]
class DocumentManagerTest extends TestCase
{
    #[Test]
    public function fake_replaces_the_bound_document_manager(): void
    {
        $fake = DocumentManager::fake();

        $this->assertSame(
            $fake,
            $this->app->make(DocumentManagerInterface::class)
        );
    }

    #[Test]
    public function fake_records_a_saved_searchable_model(): void
    {
        IndexManager::fake();
        $documents = DocumentManager::fake();

        $company = Company::factory()->create();

        $documents->assertIndexed(
            $company->searchableAs(),
            [
                new Document((string) $company->getScoutKey(),
                    $company->toSearchableArray()),
            ]
        );
    }

    #[Test]
    public function fake_records_a_deleted_searchable_model(): void
    {
        IndexManager::fake();
        $documents = DocumentManager::fake();

        $company = Company::factory()->create();

        $company->delete();

        $documents->assertDeleted(
            $company->searchableAs(),
            [(string) $company->getScoutKey()]
        );
    }
}
