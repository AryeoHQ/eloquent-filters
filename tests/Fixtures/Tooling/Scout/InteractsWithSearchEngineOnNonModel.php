<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Support\Search\Scout\Contracts\Searchable;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;

class InteractsWithSearchEngineOnNonModel implements Searchable
{
    use InteractsWithSearchEngine;
}
