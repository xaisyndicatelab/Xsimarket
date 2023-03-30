<?php


namespace Xsimarket\GraphQL\Mutation;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class CheckoutMutator
{

    public function verify($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\CheckoutController@verify', $args);
    }
}
