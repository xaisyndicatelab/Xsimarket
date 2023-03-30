<?php


namespace Xsimarket\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class AttributeMutator
{
    public function storeAttribute($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\AttributeController@store', $args);
    }
    public function updateAttribute($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\AttributeController@updateAttribute', $args);
    }
    public function deleteAttribute($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\AttributeController@deleteAttribute', $args);
    }

    public function importAttributes($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\AttributeController@importAttributes', $args);
    }
}
