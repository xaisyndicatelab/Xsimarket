<?php


namespace Xsimarket\GraphQL\Queries;


use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class QuestionQuery
{
    public function fetchShops($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\QuestionController@index', $args);
    }
}
