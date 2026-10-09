<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Illuminate\Database\Eloquent\Model;
use Support\Search\Scout\Attributes\ScoutQueue;

#[ScoutQueue('search')]
class ScoutQueueOnNonSearchable extends Model {}
