# Eloquent Builder Decorator

This package will allow you to create Query builder decorators.

### Why decorating the query builder?

Decorating a query builder might be useful if you are creating a package that
accepts a query builder as an argument and want to call the same complex query
on it at multiple places.

It is also useful if you want to share common queries between multiple projects.

### Why using this package instead of the query builder macro system?

While being very useful the macro system is global.

If your package adds macros to the query builder, then, your code is having a
side effect on the application of your user.

There are cases where this might be wanted, but if it is not, decorating is a
safer approach.

## Installation

```
composer require paulhenri-l/eloquent-builder-decorator
```

## Usage

### Creating a decorator

In order to create a decorator you need to create a new class and make it extend
`PaulhenriL\EloquentBuilderDecorator\AbstractBuilderDecorator`.

Inside of this class is the `queryToDecorate` property. This property contains
the builder instance that you are decorating.

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
$activeUsersCount = $usersQuery->whereActive()->count();
```

### Chaining multiple decorators

Decorators become really powerful when you chain them together. In this example
we'll chain the fictional decorators QueryWithDates and QueryWithRegion.

Now that they are chained our query is extended with the methods of each one
of them.

We can call their methods as many times as we want and in any order.

```php
$podcastsQuery = \DB::table('podcasts');

$podcastsQuery = new QueryWithDates($podcastsQuery);
$podcastsQuery = new QueryWithRegion($podcastsQuery);

$podcasts = $podcastsQuery->where('host', 'someone')
    ->happeningToday()
    ->inEurope()
    ->take(10)
    ->get();
```

### Caveats

If you want to keep the ability to chain decorators you have to make sure that
the methods you create in your decorators are returning decorator instances and
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

The `static::decorate($query)` method is just a bit of syntactic sugar what it
really does is `new static($query)`.

## Contributing

If you have any questions about how to use this library feel free to open an
issue.

If you think that the documentation or the code could be improved in any way
open a PR and I'll happily review it!
