<?php

declare(strict_types=1);

namespace Support\Search\OpenSearch\Facades;

use DirectoryTree\OpenSearchAdapter\Indices\IndexManagerInterface;
use DirectoryTree\OpenSearchAdapter\Testing\Fakes\FakeIndexManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see \DirectoryTree\OpenSearchAdapter\Indices\IndexManagerInterface
 */
class IndexManager extends Facade
{
    public static function fake(): FakeIndexManager
    {
        static::swap($fake = new FakeIndexManager);

        return $fake;
    }

    protected static function getFacadeAccessor(): string
    {
        return IndexManagerInterface::class;
    }
}
