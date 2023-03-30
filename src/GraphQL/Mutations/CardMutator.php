<?php


namespace Xsimarket\GraphQL\Mutation;


use Xsimarket\Exceptions\XsimarketException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class CardMutator
{
    /**
     * @throws XsimarketException
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('Xsimarket\Http\Controllers\PaymentMethodController@deletePaymentMethod', $args);
        } catch (\Exception $e) {
            throw new XsimarketException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * @throws XsimarketException
     */
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('Xsimarket\Http\Controllers\PaymentMethodController@store', $args);
        } catch (\Exception $e) {
            throw new XsimarketException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * @throws XsimarketException
     */
    public function setDefaultPaymentMethod($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('Xsimarket\Http\Controllers\PaymentMethodController@setDefaultPaymentMethod', $args);
        } catch (\Exception $e) {
            throw new XsimarketException(SOMETHING_WENT_WRONG);
        }
    }
}
