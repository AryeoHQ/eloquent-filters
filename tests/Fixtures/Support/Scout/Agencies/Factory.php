<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Scout\Agencies;

use Illuminate\Database\Eloquent\Factories;

/**
 * @extends Factories\Factory<Agency>
 */
class Factory extends Factories\Factory
{
    protected $model = Agency::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
        ];
    }
}
