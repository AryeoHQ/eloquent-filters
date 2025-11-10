<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench;

abstract class TestCase extends Testbench\TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Testing\TestResponse|null */
    public static $latestResponse = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }
}
