# Eloquent Builder Decorator

This package will allow you to create Query builder decorators. (this package
does not require laravel/framework)

Decorating a query builder might be useful if you are creating a package that
accepts a query builder as an argument and want to call the same complex query
on it at multiple places.

It is also useful if you want to share common queries between multiple projects.

### Why usinng this package instead of using the query builder macro system?

While being very useful the macro system is global. If you are writing a package
and adding macros to the query builder your code is having a side effect on the
application of your user. There are cases where this might be wanted for the
others decorating is a safer approach.

## Installation

```
composer require paulhenri-l/eloquent-builder-decorator
```

## Usage

### Creating a decorator

In order to create a decorator you need to create a new class and make it extend
`PaulhenriL\EloquentBuilderDecorator\AbstractBuilderDecorator`.

```php
class QueryWithActive extends PaulhenriL\EloquentBuilderDecorator\AbstractBuilderDecorator
{
    public function whereActive()
    {
        return static::decorate(
            $this->queryToDecorate->where('active', true)
        );
    }
}
```

### Using a decorator

Now that you have created your decorator you can use it like so:

```php
$usersQuery = User::query();
$usersQuery = new QueryWithActive($query);

// We can now call whereActive() on the query.
$activeUsersCount = $decoratedQuery->whereActive()->count();
```

### Chaining multiple decorators

Decorators become really powerful when you chain them together. In this example
we'll chain the fictional decorators QueryWithDates and QueryWithRegion.

Now that they are chained our query is extended with the functions of each one
of them.

We can call their methods as many times as we want and in any order.

```php
$podcastsQuery = \DB::table('podcasts');

$podcastsQuery = new QueryWithDates($query);
$podcastsQuery = new QueryWithRegion($query);

$podcasts = $podcastsQuery->where('host', 'someone')
    ->happeningToday()
    ->inEurope()
    ->take(10)
    ->get();
```

### Caveats

If you want to keep the abbilty to chain decorators you have to make sure that
the methods you create in your decorators are returning decorators instances and
not builder instances.

So you should do:

```php
public function whereActive()
{
    return static::decorate(
        $this->queryToDecorate->where('active', true)
    );
}
```

and you shouldn't do:

```php
public function whereActive()
{
    return $this->queryToDecorate->where('active', true)
}
```

The `static::decorate($query)` function is just a bit of syntactic sugar what it
really does is `new static($query)`.

### Tests

If you want to know more about the inner workings of this package go have a
look at its tests :)

## Contributing

If you have any questions about how to use this library feel free to open an
issue.

If you think that the documentation or the code could be improved in any way
open a PR and I'll happily review it!
