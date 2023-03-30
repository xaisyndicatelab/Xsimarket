<?php


namespace Xsimarket\GraphQL\Queries;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Xsimarket\Facades\Shop;

class ProductQuery
{
    public function relatedProducts($rootValue, array $args, GraphQLContext $context)
    {
        $args['slug'] = $rootValue->slug;
        return Shop::call('Xsimarket\Http\Controllers\ProductController@relatedProducts', $args);
    }
    public function fetchProducts($rootValue, array $args, GraphQLContext $context)
    {
        return Shop::call('Xsimarket\Http\Controllers\ProductController@fetchProducts', $args);
    }
    public function fetchDigitalFilesForProduct($rootValue, array $args, GraphQLContext $context)
    {
        $args['parent_id'] = $rootValue->id;
        return Shop::call('Xsimarket\Http\Controllers\ProductController@fetchDigitalFilesForProduct', $args);
    }
    public function fetchDigitalFilesForVariation($rootValue, array $args, GraphQLContext $context)
    {
        $args['parent_id'] = $rootValue->id;
        return Shop::call('Xsimarket\Http\Controllers\ProductController@fetchDigitalFilesForVariation', $args);
    }
}
