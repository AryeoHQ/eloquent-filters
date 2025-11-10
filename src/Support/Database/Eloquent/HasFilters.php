<?php

declare(strict_types=1);

namespace Support\Database\Eloquent;

use ReflectionClass;
use ReflectionMethod;
use Support\Database\Eloquent\Attributes\Filter;

trait HasFilters
{
    /**
     * @var array<string, array<string, mixed>>
     */
    private static array $filterMethodsCache = [];

    /**
     * Apply filters to the query builder.
     *
     * @param  array<string, mixed>  $requestParams
     */
    final public function filter(array $requestParams): static
    {
        $builderClass = get_class($this);

        // Get cached filter methods or build cache
        if (data_get(self::$filterMethodsCache, $builderClass) === null) {
            self::$filterMethodsCache[$builderClass] = $this->getFilterMethods($builderClass);
        }

        $filterMethods = self::$filterMethodsCache[$builderClass];

        foreach ($requestParams as $param => $value) {
            if (data_get($filterMethods, $param) !== null) {
                $scopeName = $filterMethods[$param];
                if (is_string($scopeName) && method_exists($this, $scopeName)) {
                    /** @var callable(mixed): static $callback */
                    $callback = [$this, $scopeName];
                    // call the builder scope method with the value
                    call_user_func($callback, $value);
                }
            }
        }

        return $this;
    }

    /**
     * Get all methods with Filter attribute for the given builder class.
     *
     * @param  class-string  $builderClass
     * @return array<string, string> Array of filter names to method names
     */
    private function getFilterMethods(string $builderClass): array
    {
        /** @var class-string $builderClass */
        $reflection = new ReflectionClass($builderClass);
        $filterMethods = [];

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(Filter::class);

            // if the method has a Filter attribute, add it to the filter methods
            if (count($attributes) > 0) {
                $filterAttribute = $attributes[0]->newInstance();
                $filterMethods[$filterAttribute->name] = $method->getName();
            }
        }

        return $filterMethods;
    }
}
