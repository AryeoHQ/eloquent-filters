<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Illuminate\Database\Eloquent\Model;
use Support\Search\Scout\Attributes\ScoutConnection;
use Support\Search\Scout\Attributes\ScoutQueue;
use Support\Search\Scout\Attributes\UseScoutBuilder;
use Support\Search\Scout\Contracts\Searchable;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;

#[ScoutConnection('redis')]
#[ScoutQueue('search')]
#[UseScoutBuilder(ValidScoutBuilder::class)]
class ValidSearchableModel extends Model implements Searchable
{
    use InteractsWithSearchEngine;
}
