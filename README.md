# Eloquent Search
A package providing filtering, sorting, and search for your Eloquent models.

## Installation
```bash
composer require aryeo/eloquent-search
```

## Usage

Eloquent Search is meant to be used with the `Illuminate\Database\Eloquent\Builder` classes defined on your models.

### Setting up your model

You need to tell your model to use a custom eloquent builder class:

```php
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(UserBuilder::class)]
class User extends Model
{
    //..
}
```

### Filters

#### Setting up your eloquent builder class

Implement the `Support\Search\Database\Contracts\Filterable` contract and apply the `Support\Search\Database\Provides\HasFilters` trait to your eloquent builder class:

```php
use Illuminate\Database\Eloquent\Builder;
use Support\Search\Database\Contracts\Filterable;
use Support\Search\Database\Provides\HasFilters;

class UserBuilder extends Builder implements Filterable
{
    use HasFilters;

    //..
}
```

#### Defining scopes to be used as filters

Adding the `Support\Search\Attributes\Filter` attribute over your query scopes will register them as available filters.

```php
use Support\Search\Attributes\Filter;

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

Implement the `Support\Search\Database\Contracts\Sortable` contract and apply the `Support\Search\Database\Provides\HasSort` trait to your eloquent builder class:

```php
use Illuminate\Database\Eloquent\Builder;
use Support\Search\Database\Contracts\Sortable;
use Support\Search\Database\Provides\HasSort;

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

### Search

Search is built on [Laravel Scout](https://laravel.com/docs/scout) with an OpenSearch driver. The package registers Scout and the OpenSearch driver for you, so set `scout.driver` to `opensearch` and you're ready.

#### Setting up your model

Implement the `Support\Search\Scout\Contracts\Searchable` contract and use the `Support\Search\Scout\Provides\InteractsWithSearchEngine` trait. Use this trait instead of Scout's own `Searchable` trait. It wraps Scout's trait and also reads the attributes below. PHPStan will flag a model that uses Scout's trait directly.

```php
use Illuminate\Database\Eloquent\Model;
use Support\Search\Scout\Contracts\Searchable;
use Support\Search\Scout\Provides\InteractsWithSearchEngine;

class Company extends Model implements Searchable
{
    use InteractsWithSearchEngine;
}
```

#### Attributes

Scout normally makes you override methods to change a few per-model settings. With this trait you can use attributes instead:

```php
use Support\Search\Scout\Attributes\ScoutConnection;
use Support\Search\Scout\Attributes\ScoutQueue;
use Support\Search\Scout\Attributes\UseScoutBuilder;

#[UseScoutBuilder(CompanySearchBuilder::class)]
#[ScoutQueue('search')]
#[ScoutConnection('redis')]
class Company extends Model implements Searchable
{
    use InteractsWithSearchEngine;
}
```

- `#[UseScoutBuilder]` sets the builder `Company::search()` returns. It must extend `Laravel\Scout\Builder`.
- `#[ScoutQueue]` and `#[ScoutConnection]` set where the sync job goes. They only matter when `scout.queue` is on. With it off, Scout syncs right away and there's no job.

Leave an attribute off and you get Scout's default. Attributes don't carry over to subclasses, so a child model that wants them has to declare its own.

#### Testing

The `Support\Search\OpenSearch\Facades\DocumentManager` and `IndexManager` facades each have a `fake()` method, so tests don't need a running OpenSearch:

```php
use Support\Search\OpenSearch\Facades\DocumentManager;

$documents = DocumentManager::fake();
```
