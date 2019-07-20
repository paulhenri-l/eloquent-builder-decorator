<?php

namespace PaulhenriL\EloquentBuilderDecorator\Tests\Fakes;

use PaulhenriL\EloquentBuilderDecorator\AbstractBuilderDecorator;

class QueryWithType extends AbstractBuilderDecorator
{
    public function wherePremium()
    {
        return $this->whereType('premium');
    }

    public function whereRegular()
    {
        return $this->whereType('regular');
    }

    public function whereType($type)
    {
        return static::decorate(
            $this->queryToDecorate->where('type', $type)
        );
    }
}
