<?php


namespace Xsimarket\GraphQL\Mutation;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class AuthorMutator
{
    public function storeAuthor($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\AuthorController@store', $args);
    }
    public function updateAuthor($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\AuthorController@updateAuthor', $args);
    }
}
