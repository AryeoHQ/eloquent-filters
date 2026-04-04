<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\Tooling\ValidBuilder;

#[UseFactory(UserFactory::class)]
class User extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasUuids;

    protected $table = 'users';

    protected $fillable = [
        'role',
        'status',
    ];

    protected $casts = [
        'role' => Role::class,
    ];

    /**
     * @return ValidBuilder<User>
     */
    public function newEloquentBuilder($query): ValidBuilder
    {
        /** @var ValidBuilder<User> */
        return new ValidBuilder($query);
    }
}
