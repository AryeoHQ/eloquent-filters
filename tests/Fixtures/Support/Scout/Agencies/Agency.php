<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Scout\Agencies;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Search\Scout\Attributes\ScoutConnection;
use Support\Search\Scout\Attributes\ScoutQueue;
use Support\Search\Scout\Attributes\UseScoutBuilder;
use Support\Search\Scout\Contracts\Searchable;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;

#[ScoutConnection('database')]
#[ScoutQueue('search')]
#[UseFactory(Factory::class)]
#[UseScoutBuilder(Builders\Scout::class)]
class Agency extends Model implements Searchable
{
    /** @use HasFactory<Factory> */
    use HasFactory;

    use InteractsWithSearchEngine;
    use SoftDeletes;

    protected $table = 'companies';
}
