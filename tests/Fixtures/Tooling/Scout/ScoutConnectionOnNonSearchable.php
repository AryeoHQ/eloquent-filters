<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Illuminate\Database\Eloquent\Model;
use Support\Search\Scout\Attributes\ScoutConnection;

#[ScoutConnection('redis')]
class ScoutConnectionOnNonSearchable extends Model {}
