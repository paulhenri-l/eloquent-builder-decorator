<?php

namespace PaulhenriL\EloquentBuilderDecorator\Tests\Unit;

use PaulhenriL\EloquentBuilderDecorator\Tests\Factories\UserFactory;
use PaulhenriL\EloquentBuilderDecorator\Tests\Fakes\QueryWithType;
use PaulhenriL\EloquentBuilderDecorator\Tests\Fakes\User;
use PaulhenriL\EloquentBuilderDecorator\Tests\Fakes\QueryWithActive;
use PaulhenriL\EloquentBuilderDecorator\Tests\TestCase;
use Illuminate\Database\Capsule\Manager as Capsule;

class AbstractDecoratorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        UserFactory::times(2)->create(['active' => false]);
        UserFactory::new()->create(['active' => true]);
    }

    public function test_eloquent_builder_can_be_decorated()
    {
        $decoratedQuery = new QueryWithActive(User::query());

        $this->assertEquals(1, $decoratedQuery->whereActive()->count());
    }

    public function test_database_builder_can_be_decorated()
    {
        $decoratedQuery = new QueryWithActive(
            Capsule::table('users')
        );

        $this->assertEquals(1, $decoratedQuery->whereActive()->count());
    }

    public function test_the_query_remains_decorated_after_multiple_calls()
    {
        $user = UserFactory::new()->create(['active' => true]);

        $query = new QueryWithActive(Capsule::table('users'));

        $this->assertInstanceOf(
            QueryWithActive::class,
            $query = $query->where('name', $user->name)
        );

        $this->assertInstanceOf(
            QueryWithActive::class,
            $query = $query->whereActive()
        );

        $this->assertEquals(1, $query->count());
    }

    public function test_you_can_chain_calls()
    {
        $user = UserFactory::new()->create(['active' => true]);

        $query = new QueryWithActive(Capsule::table('users'));

        $result = $query->where('name', $user->name)
            ->whereActive()
            ->limit(1)
            ->count();

        $this->assertEquals(1, $result);
    }

    public function test_decorate_function()
    {
        $decoratedQuery = QueryWithActive::decorate(User::query());

        $this->assertEquals(1, $decoratedQuery->whereActive()->count());
    }

    public function test_multiple_decorators_can_work_together()
    {
        $user = UserFactory::new()->create([
            'name' => 'test-name',
            'type' => 'premium',
            'active' => 1
        ]);

        $query = User::query();
        $query = new QueryWithActive($query);
        $query = new QueryWithType($query);

        $fetchedUser = $query->limit(1)
            ->whereActive()
            ->orderBy('name')
            ->wherePremium()
            ->whereName('test-name')
            ->first();

        $this->assertTrue($fetchedUser->is($user));
    }
}
