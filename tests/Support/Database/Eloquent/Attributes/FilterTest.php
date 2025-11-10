<?php

namespace Tests\Support\Database\Eloquent\Attributes;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts;

class FilterTest extends TestCase
{
    #[Test]
    public function filter_attribute_can_be_created(): void
    {
        $filter = new Filter('test');

        $this->assertEquals('test', $filter->name);
    }

    #[Test]
    public function filter_attribute_is_instance_of_request_filter(): void
    {
        $filter = new Filter('test');
        
        $this->assertInstanceOf(Contracts\Filter::class, $filter);
    }
}
