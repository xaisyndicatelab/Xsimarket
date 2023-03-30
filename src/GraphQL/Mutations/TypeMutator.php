<?php


namespace Xsimarket\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class TypeMutator
{
    public function storeType($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\TypeController@store', $args);
    }
    public function updateType($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\TypeController@updateType', $args);
    }
}
