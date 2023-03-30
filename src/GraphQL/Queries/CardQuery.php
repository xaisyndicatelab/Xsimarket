<?php


namespace Xsimarket\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class CardQuery
{
    public function fetchCards($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\PaymentMethodController@index', $args);
    }
}
