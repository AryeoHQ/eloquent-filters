<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Scout;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

#[UseFactory(CompanyFactory::class)]
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    use Searchable;

    protected $table = 'companies';

    protected $fillable = [
        'name',
    ];
}
