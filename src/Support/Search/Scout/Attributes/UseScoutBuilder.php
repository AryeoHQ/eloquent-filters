<?php

declare(strict_types=1);

namespace Support\Search\Scout\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class UseScoutBuilder
{
    /** @var class-string<\Laravel\Scout\Builder<*>> */
    public string $class;

    /**
     * @param  class-string<\Laravel\Scout\Builder<*>>  $class
     */
    public function __construct(string $class)
    {
        $this->class = $class;
    }
}
