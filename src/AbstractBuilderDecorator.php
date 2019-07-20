<?php

namespace PaulhenriL\EloquentBuilderDecorator;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as DatabaseBuilder;

abstract class AbstractBuilderDecorator
{
    /**
     * The Query to decorate.
     *
     * @var EloquentBuilder|DatabaseBuilder
     */
    protected $queryToDecorate;

    /**
     * Create a new Builder decorator.
     *
     * @param $queryToDecorate EloquentBuilder|DatabaseBuilder
     */
    public function __construct($queryToDecorate)
    {
        $this->queryToDecorate = $queryToDecorate;
    }

    /**
     * Decorate the given query. This method is just syntactic sugar.
     */
    public static function decorate($queryToDecorate)
    {
        return new static($queryToDecorate);
    }

    /**
     * Forward calls to the decorated builder.
     */
    public function __call($methodName, $arguments)
    {
        $result = $this->queryToDecorate->{$methodName}(...$arguments);

        if (
            $result instanceof EloquentBuilder
            || $result instanceof DatabaseBuilder
            || $result instanceof AbstractBuilderDecorator
        ) {
            $result = new static($result);
        }

        return $result;
    }
}
