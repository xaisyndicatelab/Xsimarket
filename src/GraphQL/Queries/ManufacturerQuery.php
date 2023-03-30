<?php


namespace Xsimarket\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class ManufacturerQuery
{
    public function topManufacturer($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\ManufacturerController@topManufacturer', $args);
    }
}
