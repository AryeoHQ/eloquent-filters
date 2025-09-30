<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Tooling\Rector\Rules\AddContractAndTraitForFilterableBuilders;

return RectorConfig::configure()
    ->withRules([
        AddContractAndTraitForFilterableBuilders::class,
    ])
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
