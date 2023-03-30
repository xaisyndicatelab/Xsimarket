<?php


namespace Xsimarket\GraphQL\Mutation;

use Xsimarket\Facades\Shop;
use Illuminate\Support\Facades\Log;
use Xsimarket\Exceptions\XsimarketException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CouponMutator
{

    public function verify($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('Xsimarket\Http\Controllers\CouponController@verify', $args);
        } catch (\Exception $e) {
            throw new XsimarketException(SOMETHING_WENT_WRONG);
        }
    }

    public function store($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('Xsimarket\Http\Controllers\CouponController@store', $args);
        } catch (\Exception $e) {
            throw new XsimarketException(SOMETHING_WENT_WRONG);
        }
    }

    public function update($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('Xsimarket\Http\Controllers\CouponController@updateCoupon', $args);
        } catch (\Exception $e) {
            throw new XsimarketException(SOMETHING_WENT_WRONG);
        }
    }
}
