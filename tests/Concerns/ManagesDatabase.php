<?php

namespace PaulhenriL\EloquentBuilderDecorator\Tests\Concerns;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Capsule\Manager as Capsule;

trait ManagesDatabase
{
    /**
     * Have we already prepared the DB?
     *
     * @var bool
     */
    protected static $dbPrepared = false;

    /**
     * The Eloquent factory instance.
     *
     * @var Factory
     */
    protected static $factory;

    /**
     * Prepare the test database.
     */
    protected function prepareDbIfNecessary()
    {
        if (!static::$dbPrepared) {
            $this->bootEloquent();
            $this->loadFactories();
            static::$dbPrepared = true;
        }
    }

    /**
     * Boot eloquent by using an in memory sqlite db.
     */
    protected function bootEloquent()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    /**
     * Equivalent of migarte:fresh
     */
    protected function freshSchema()
    {
        Capsule::schema()->dropIfExists('users');
        Capsule::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Load the database factories.
     */
    protected function loadFactories()
    {
        static::$factory = Factory::construct(
            \Faker\Factory::create(),
            __DIR__ . '/../factories'
        );
    }

    /**
     * You can use this method exactly as you would use the default laravel
     * factory helper.
     */
    public function factory(...$arguments)
    {
        if (isset($arguments[1]) && is_string($arguments[1])) {
            return static::$factory->of($arguments[0], $arguments[1])->times($arguments[2] ?? null);
        } elseif (isset($arguments[1])) {
            return static::$factory->of($arguments[0])->times($arguments[1]);
        }

        return static::$factory->of($arguments[0]);
    }
}
