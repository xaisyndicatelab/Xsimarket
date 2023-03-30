<?php


namespace Xsimarket\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class ManufacturerMutator
{
    public function storeManufacturer($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\ManufacturerController@store', $args);
    }
    public function updateManufacturer($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\ManufacturerController@updateManufacturer', $args);
    }
}
