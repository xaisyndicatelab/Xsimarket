<?php


namespace Xsimarket\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class WithdrawQuery
{
    public function fetchWithdraws($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\WithdrawController@fetchWithdraws', $args);
    }

    public function fetchSingleWithdraw($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\WithdrawController@fetchSingleWithdraw', $args);
    }
}
