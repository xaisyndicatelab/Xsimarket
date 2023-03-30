<?php


namespace Xsimarket\GraphQL\Mutation;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class RefundMutator
{

    public function createRefund($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\RefundController@store', $args);
    }

    public function updateRefund($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\RefundController@updateRefund', $args);
    }
}
