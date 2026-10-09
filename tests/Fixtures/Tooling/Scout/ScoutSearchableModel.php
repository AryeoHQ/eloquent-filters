<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ScoutSearchableModel extends Model
{
    use Searchable;
}
