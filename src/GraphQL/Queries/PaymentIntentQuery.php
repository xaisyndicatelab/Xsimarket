<?php


namespace Xsimarket\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class PaymentIntentQuery
{
    public function getPaymentIntent($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\PaymentIntentController@getPaymentIntent', $args);
    }
}
