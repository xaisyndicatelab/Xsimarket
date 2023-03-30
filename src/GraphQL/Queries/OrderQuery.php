<?php


namespace Xsimarket\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class OrderQuery
{
    public function fetchOrders($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\OrderController@fetchOrders', $args);
    }

    public function fetchSingleOrder($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\OrderController@fetchSingleOrder', $args);
    }
}
