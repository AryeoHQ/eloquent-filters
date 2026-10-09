<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Illuminate\Database\Eloquent\Model;
use Support\Search\Scout\Contracts\Searchable;

abstract class SearchableWithoutInteractsWithSearchEngine extends Model implements Searchable {}
