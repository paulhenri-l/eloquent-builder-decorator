<?php

namespace PaulhenriL\EloquentBuilderDecorator\Tests;

use PaulhenriL\EloquentBuilderDecorator\Tests\Concerns\ManagesDatabase;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    use ManagesDatabase;

    /**
     * Prepare the DB and load a fresh schema for your test suite.
     */
    protected function setUp(): void
    {
        $this->prepareDbIfNecessary();
        $this->freshSchema();
    }
}
