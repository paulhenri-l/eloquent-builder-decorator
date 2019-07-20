<?php

namespace PaulhenriL\EloquentBuilderDecorator\Tests\Fakes;

use PaulhenriL\EloquentBuilderDecorator\AbstractBuilderDecorator;

class QueryWithActive extends AbstractBuilderDecorator
{
    public function whereActive()
    {
        return static::decorate(
            $this->queryToDecorate->where('active', true)
        );
    }
}
