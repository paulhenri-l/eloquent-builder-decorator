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
     * Prepare the test database.
     */
    protected function prepareDbIfNecessary()
    {
        if (!static::$dbPrepared) {
            $this->bootEloquent();
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
}
