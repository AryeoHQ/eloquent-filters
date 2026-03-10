<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithCachedConfig;
use Illuminate\Foundation\Testing\WithCachedRoutes;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench;
use Support\Database\Eloquent\Providers\Provider;

abstract class TestCase extends Testbench\TestCase
{
    use RefreshDatabase;
    use WithCachedConfig;
    use WithCachedRoutes;

    protected function defineDatabaseMigrations(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('role');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            Provider::class,
        ];
    }
}
