<?php

namespace Tests\Support\Database\Eloquent;

use Tests\TestCase;
use Tests\Fixtures\Role;
use Tests\Fixtures\User;
use Illuminate\Http\Request;
use PHPUnit\Metadata\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Support\Database\Eloquent\HasFilters;

#[CoversClass(HasFilters::class)]
class FilterableEloquentBuilderTest extends TestCase
{
    #[Test]
    public function filter_method_proxies_to_scope_methods(): void
    {
        $user1 = User::factory()
            ->admin()
            ->active()
            ->create();
        User::factory()
            ->member()
            ->invited()
            ->create();

        $request = new Request([
            'role' => Role::Admin,
        ]);

        $users = User::filter($request->all())
            ->get();

        $this->assertCount(1, $users);
        $this->assertEquals($user1->id, $users->first()->id);
    }

    #[Test]
    public function filter_method_proxies_to_scope_methods_with_multiple_filters(): void
    {
        $user1 = User::factory()
            ->admin()
            ->active()
            ->create();

        User::factory()
            ->admin()
            ->active()
            ->create([
                'created_at' => now()->subDays(2),
            ]);

        $request = new Request([
            'role' => Role::Admin,
            'status' => 'active',
            'is_new' => true,
        ]);

        $users = User::filter($request->all())
            ->get();

        $this->assertCount(1, $users);
        $this->assertEquals($user1->id, $users->first()->id);
    }
}
