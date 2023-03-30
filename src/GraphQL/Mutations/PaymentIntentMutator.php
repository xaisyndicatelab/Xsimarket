<?php


namespace Xsimarket\GraphQL\Mutation;


use Xsimarket\Exceptions\XsimarketException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class PaymentIntentMutator
{
    /**
     * @throws XsimarketException
     */
    public function savePaymentMethod($rootValue, array $args, GraphQLContext $context)
    {
        try {
            return Shop::call('Xsimarket\Http\Controllers\PaymentMethodController@savePaymentMethod', $args);
        } catch (\Exception $e) {
            throw new XsimarketException(SOMETHING_WENT_WRONG);
        }
    }
}
