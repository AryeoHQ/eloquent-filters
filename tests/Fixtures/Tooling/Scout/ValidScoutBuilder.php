<?php

declare(strict_types=1);

namespace Tests\Fixtures\Tooling\Scout;

use Laravel\Scout\Builder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class ValidScoutBuilder extends Builder {}
