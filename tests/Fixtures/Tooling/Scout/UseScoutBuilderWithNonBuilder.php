<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use Support\Search\Scout\Attributes\UseScoutBuilder;
use Support\Search\Scout\Contracts\Searchable;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;

#[UseScoutBuilder(stdClass::class)]
class UseScoutBuilderWithNonBuilder extends Model implements Searchable
{
    use InteractsWithSearchEngine;
}
