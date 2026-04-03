<?php

declare(strict_types=1);

namespace Support\Database\Eloquent;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use Support\Primitives\Direction;
use Support\Primitives\Sort;
use Tests\Fixtures\Support\User;
use Tests\TestCase;

#[CoversTrait(ManagesSort::class)]
class ManagesSortTest extends TestCase
{
    #[Test]
    public function sort_method_applies_ascending_order_by_clause(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $query = User::sort('id');

        $orderByClauses = $query->getQuery()->orders;
        $this->assertCount(1, $orderByClauses);
        $this->assertEquals('id', $orderByClauses[0]['column']);
        $this->assertEquals('asc', $orderByClauses[0]['direction']);

        $users = $query->get();

        $this->assertCount(2, $users);
        $this->assertEquals($user1->id, $users[0]->id);
        $this->assertEquals($user2->id, $users[1]->id);
    }

    #[Test]
    public function sort_method_ignores_null_sort(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $query = User::sort(null);

        $orderByClauses = $query->getQuery()->orders;
        $this->assertNull($orderByClauses);

        $users = $query->get();

        $this->assertCount(2, $users);
        $this->assertEqualsCanonicalizing(
            [$user1->id, $user2->id],
            $users->pluck('id')->toArray()
        );
    }

    #[Test]
    public function sort_scope_applies_descending_sorts(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $query = User::sort('-id');

        $orderByClauses = $query->getQuery()->orders;
        $this->assertCount(1, $orderByClauses);
        $this->assertEquals('id', $orderByClauses[0]['column']);
        $this->assertEquals('desc', $orderByClauses[0]['direction']);

        $users = $query->get();

        $this->assertCount(2, $users);
        $this->assertEquals($user2->id, $users[0]->id);
        $this->assertEquals($user1->id, $users[1]->id);
    }

    #[Test]
    public function sort_scope_accepts_sort_primitive(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $sort = new Sort('id', Direction::Desc);

        $query = User::sort($sort);

        $orderByClauses = $query->getQuery()->orders;
        $this->assertCount(1, $orderByClauses);
        $this->assertEquals('id', $orderByClauses[0]['column']);
        $this->assertEquals('desc', $orderByClauses[0]['direction']);

        $users = $query->get();

        $this->assertCount(2, $users);
        $this->assertEquals($user2->id, $users[0]->id);
        $this->assertEquals($user1->id, $users[1]->id);
    }

    #[Test]
    public function sort_scope_applies_tiebreaking_sort(): void
    {
        $tiebreaker = now();
        $nonTiebreaker = now()->addDay();

        $tieUser1 = User::factory()->create([
            'created_at' => $tiebreaker,
        ]);
        $tieUser2 = User::factory()->create([
            'created_at' => $tiebreaker,
        ]);
        $nonTieUser = User::factory()->create([
            'created_at' => $nonTiebreaker,
        ]);

        $query = User::sort('created_at');

        $orderByClauses = $query->getQuery()->orders;
        $this->assertCount(2, $orderByClauses);
        $this->assertEquals('created_at', $orderByClauses[0]['column']);
        $this->assertEquals('asc', $orderByClauses[0]['direction']);
        $this->assertEquals('users.id', $orderByClauses[1]['column']);
        $this->assertEquals('asc', $orderByClauses[1]['direction']);

        $users = $query->get();

        $this->assertCount(3, $users);
        $this->assertEquals($tieUser1->id, $users[0]->id);
        $this->assertEquals($tieUser2->id, $users[1]->id);
        $this->assertEquals($nonTieUser->id, $users[2]->id);
    }

    #[Test]
    public function sort_scope_applies_tiebreaking_sort_in_same_order(): void
    {
        $tiebreaker = now();
        $nonTiebreaker = now()->addDay();

        $tieUser1 = User::factory()->create([
            'created_at' => $tiebreaker,
        ]);
        $tieUser2 = User::factory()->create([
            'created_at' => $tiebreaker,
        ]);
        $nonTieUser = User::factory()->create([
            'created_at' => $nonTiebreaker,
        ]);

        $query = User::sort('-created_at');

        $orderByClauses = $query->getQuery()->orders;
        $this->assertCount(2, $orderByClauses);
        $this->assertEquals('created_at', $orderByClauses[0]['column']);
        $this->assertEquals('desc', $orderByClauses[0]['direction']);
        $this->assertEquals('users.id', $orderByClauses[1]['column']);
        $this->assertEquals('desc', $orderByClauses[1]['direction']);

        $users = $query->get();

        $this->assertCount(3, $users);
        $this->assertEquals($nonTieUser->id, $users[0]->id);
        $this->assertEquals($tieUser2->id, $users[1]->id);
        $this->assertEquals($tieUser1->id, $users[2]->id);
    }
}
