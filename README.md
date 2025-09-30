# Eloquent Filters
A package providing a PHP attribute for marking eloquent builder query scopes as filters for your model.

## Installation
```bash
composer require aryeo/eloquent-filters
```

## Usage

Eloquent Filters are meant to be used with the `Illuminate\Database\Eloquent\Builder` classes defined on your models.

### Setting up your eloquent builder class

Create a new eloquent builder class that implements the `Support\Database\Eloquent\Contracts\Filterable` contract and uses the `Support\Database\Eloquent\FiltersRequests` trait.

```php
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\FiltersRequests;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class UserBuilder extends Builder implements Filterable
{
    use FiltersRequests;

    //..
}
```

### Setting up your model

Next, you need to tell your model to use the eloquent builder class

```php
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //..

    /**
     * @return UserBuilder<User>
     */
    public function newEloquentBuilder($query): UserBuilder
    {
        /** @var UserBuilder<User> */
        return new UserBuilder($query);
    }
}
```

### Defining scopes to be used as filters

Adding the `Support\Database\Eloquent\Attributes\Filter` attribute over your query scopes will register them as available filters.

```php
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Attributes\Filter;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\FiltersRequests;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class UserBuilder extends Builder implements Filterable
{
    use FiltersRequests;

    #[Filter('role')]
    public function role(string $role): static
    {
        return $this->where('role', $role);
    }

    #[Filter('status')]
    public function ofStatus(string $status): static
    {
        return $this->where('status', $status);
    }

    #[Filter('is_new')]
    public function isNew(): static
    {
        return $this->where('created_at', '>', now()->subDays(1));
    }
}
```

### Filtering your model

A `filter()` method is exposed on your eloquent builder so instead of writing a query using query scope like this:

```php
User::query()
    ->role('admin')
    ->status('active')
    ->get()
```

A simple array of values to filter your model can be passed into the `filter()` method. The idea is to pass the entire form request array into the filter method for easy filtering:

```php
// incoming form request
new Request([
    'role' => 'admin',
    'status' => 'active'
]);

User::filter($request->all())
    ->get()
```

## Static Analysis

A custom PHPStan rules is available to add to your projects to ensure filterable Eloquent builders follow the implementation standards.

The rule can be added to your projects `phpstan.neon` to the `rules` key.

```yml
rules:
    - Tooling\PHPStan\Rules\FilteringRule
```

## Rector

A custom Rector rule is available to add to your projects `rector.php` file.

```php
use Rector\Config\RectorConfig;
use Tooling\Rector\Rules\AddContractAndTraitForFilterableBuilders;

return RectorConfig::configure()
    ->withRules([
        AddContractAndTraitForFilterableBuilders::class
        //..
    ])
    //..
```



