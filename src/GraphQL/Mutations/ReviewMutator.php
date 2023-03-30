<?php


namespace Xsimarket\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class ReviewMutator
{
    public function store($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\ReviewController@store', $args);
    }

    public function update($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\ReviewController@updateReview', $args);
    }
}
