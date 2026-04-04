# Eloquent Filters
A package providing filtering and sorting capabilities for your Eloquent builder classes.

## Installation
```bash
composer require aryeo/eloquent-filters
```

## Usage

Eloquent Filters are meant to be used with the `Illuminate\Database\Eloquent\Builder` classes defined on your models.

### Setting up your model

You need to tell your model to use a custom eloquent builder class:

```php
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //..

    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }
}
```

### Filters

#### Setting up your eloquent builder class

Implement the `Support\Database\Eloquent\Contracts\Filterable` contract and apply the `Support\Database\Eloquent\HasFilters` trait to your eloquent builder class:

```php
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;

class UserBuilder extends Builder implements Filterable
{
    use HasFilters;

    //..
}
```

#### Defining scopes to be used as filters

Adding the `Support\Database\Eloquent\Attributes\Filter` attribute over your query scopes will register them as available filters.

```php
use Support\Database\Eloquent\Attributes\Filter;

class UserBuilder extends Builder implements Filterable
{
    use HasFilters;

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

#### Filtering your model

A `filter()` method is exposed on your eloquent builder so instead of writing a query using query scope like this:

```php
User::query()
    ->role('admin')
    ->status('active')
    ->get()
```

A simple array of values to filter your model can be passed into the `filter()` method. The idea is to pass the entire form request array into the filter method for easy filtering:

```php
class UserController
{
    public function index(Request $request)
    {
        return User::filter($request->all())->get();
    }
}
```

### Sort

The `HasSort` trait provides a `sort()` method on your eloquent builder that applies ordering based on a field name and direction.

#### Setting up your eloquent builder class

Implement the `Support\Database\Eloquent\Contracts\Sortable` contract and apply the `Support\Database\Eloquent\HasSort` trait to your eloquent builder class:

```php
use Illuminate\Database\Eloquent\Builder;
use Support\Database\Eloquent\Contracts\Sortable;
use Support\Database\Eloquent\HasSort;

class UserBuilder extends Builder implements Sortable
{
    use HasSort;

    //..
}
```

#### Sorting your model

The `sort()` method accepts a string field name, a `Text` instance, a `Sort` instance, or `null`. Prefix a string field name with `-` to sort in descending order:

```php
// Sort by name ascending
User::sort('name')->get()

// Sort by name descending
User::sort('-name')->get()
```

A direction can also be passed explicitly as the second parameter, either as a `Direction` enum or a string:

```php
use Support\Primitives\Direction;

User::sort('name', Direction::Desc)->get()

User::sort('name', 'desc')->get()
```

When sorting by a field other than the model's primary key, a secondary sort by the primary key is automatically applied in the same direction to ensure deterministic ordering.

You can also pass a `Sort` instance or `null` -- which results in no sorting being applied (helpful when the `sort` parameter is optional in a `Request`):

```php
use Support\Primitives\Direction;
use Support\Primitives\Sort;

User::sort(Sort::make('name', Direction::Desc))->get()

User::sort(null)->get() // no sorting applied
```
