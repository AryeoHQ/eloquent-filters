<?php

declare(strict_types=1);

namespace Tests\Fixtures\Support\Database\Users;

use Illuminate\Database\Eloquent\Factories;

/**
 * @extends Factories\Factory<User>
 */
class Factory extends Factories\Factory
{
    public function definition(): array
    {
        return [
            'role' => 'member',
            'status' => 'active',
        ];
    }

    public function admin(): self
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    public function member(): self
    {
        return $this->state([
            'role' => 'member',
        ]);
    }

    public function active(): self
    {
        return $this->state([
            'status' => 'active',
        ]);
    }

    public function invited(): self
    {
        return $this->state([
            'status' => 'invited',
        ]);
    }
}
