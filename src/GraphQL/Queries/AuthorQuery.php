<?php


namespace Xsimarket\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class AuthorQuery
{
    public function topAuthor($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\AuthorController@topAuthor', $args);
    }
}
