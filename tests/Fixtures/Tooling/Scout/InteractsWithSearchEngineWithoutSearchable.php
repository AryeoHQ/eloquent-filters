<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Illuminate\Database\Eloquent\Model;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;

class InteractsWithSearchEngineWithoutSearchable extends Model
{
    use InteractsWithSearchEngine;
}
