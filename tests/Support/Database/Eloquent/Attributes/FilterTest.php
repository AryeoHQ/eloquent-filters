<?php

namespace Tests\Support\Database\Eloquent\Attributes;

use PHPUnit\Framework\TestCase;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts\RequestFilter;

class FilterTest extends TestCase
{
    public function test_filter_attribute_can_be_created(): void
    {
        $filter = new Filter('test');
        $this->assertEquals('test', $filter->name);
    }

    public function test_filter_attribute_is_instance_of_request_filter(): void
    {
        $filter = new Filter('test');
        $this->assertInstanceOf(RequestFilter::class, $filter);
    }
}
