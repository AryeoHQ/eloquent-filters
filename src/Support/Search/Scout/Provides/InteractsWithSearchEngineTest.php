<?php

declare(strict_types=1);

namespace Support\Search\Scout\Provides;

use Illuminate\Support\Facades\Queue;
use Laravel\Scout\Builder;
use Laravel\Scout\Jobs\MakeSearchable;
use Orchestra\Testbench\Attributes\WithConfig;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\Test;
use Tests\Fixtures\Support\Scout\Agencies\Agency;
use Tests\Fixtures\Support\Scout\Agencies\Builders;
use Tests\Fixtures\Support\Scout\Brokerages\Brokerage;
use Tests\TestCase;

#[CoversTrait(InteractsWithSearchEngine::class)]
class InteractsWithSearchEngineTest extends TestCase
{
    #[Test]
    public function the_first_search_returns_the_declared_builder(): void
    {
        $this->assertInstanceOf(Builders\Scout::class, Agency::search());
    }

    #[Test]
    public function a_subclass_without_attributes_gets_scouts_builder_when_the_parent_searched_first(): void
    {
        Agency::search();

        $builder = Brokerage::search();

        $this->assertSame(Builder::class, $builder::class);
    }

    #[Test]
    public function a_subclass_without_attributes_gets_scouts_builder_when_it_searched_first(): void
    {
        $builder = Brokerage::search();

        Agency::search();

        $this->assertSame(Builder::class, $builder::class);
        $this->assertInstanceOf(Builders\Scout::class, Agency::search());
    }

    #[Test]
    #[WithConfig('scout.queue', true)]
    public function the_queue_attributes_place_the_sync_job(): void
    {
        Queue::fake();

        Agency::factory()->create();

        Queue::assertPushedOn(
            'search',
            MakeSearchable::class,
            fn (MakeSearchable $job): bool => $job->connection === 'database'
        );
    }

    #[Test]
    #[WithConfig('scout.queue', ['connection' => 'sync', 'queue' => 'scout'])]
    public function a_subclass_without_queue_attributes_uses_scouts_config(): void
    {
        Queue::fake();

        Brokerage::factory()->create();

        Queue::assertPushedOn(
            'scout',
            MakeSearchable::class,
            fn (MakeSearchable $job): bool => $job->connection === 'sync'
        );
    }

    #[Test]
    public function the_builder_keeps_the_query_and_callback_given_to_search(): void
    {
        $callback = fn (): null => null;

        $builder = Agency::search('query', $callback);

        $this->assertSame('query', $builder->query);
        $this->assertSame($callback, $builder->callback);
        $this->assertInstanceOf(Agency::class, $builder->model);
    }

    #[Test]
    #[WithConfig('scout.soft_delete', true)]
    public function search_excludes_soft_deleted_models_when_scout_is_configured_to(): void
    {
        $this->assertSame(['__soft_deleted' => 0], Agency::search()->wheres);
    }

    #[Test]
    public function search_includes_soft_deleted_models_by_default(): void
    {
        $this->assertSame([], Agency::search()->wheres);
    }
}
