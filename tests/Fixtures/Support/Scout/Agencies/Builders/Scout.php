<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Scout\Agencies\Builders;

use Laravel\Scout as Base;
use Tests\Fixtures\Support\Scout\Agencies\Agency;

/**
 * @extends Base\Builder<Agency>
 */
class Scout extends Base\Builder {}
