<?php


namespace Xsimarket\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class QuestionMutator
{
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\QuestionController@store', $args);
    }

    public function updateQuestion($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\QuestionController@updateQuestion', $args);
    }
}
