<?php

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Xsimarket\Database\Models\Attribute;
use Xsimarket\Database\Models\AttributeValue;
use Xsimarket\Database\Models\Product;
use Xsimarket\Database\Models\User;
use Xsimarket\Database\Models\Category;
use Xsimarket\Database\Models\Type;
use Xsimarket\Database\Models\Order;
use Xsimarket\Database\Models\Coupon;
use Spatie\Permission\Models\Permission;
use Xsimarket\Enums\Permission as UserPermission;
use Illuminate\Database\Eloquent;


class DemoDataSeeders extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users_path = public_path('sql/xsimarket/users.sql');
        $users_sql = file_get_contents($users_path);
        DB::statement($users_sql);

        $types_path = public_path('sql/xsimarket/types.sql');
        $types_sql = file_get_contents($types_path);
        DB::statement($types_sql);

        $categories_path = public_path('sql/xsimarket/categories.sql');
        $categories_sql = file_get_contents($categories_path);
        DB::statement($categories_sql);

        $products_path = public_path('sql/xsimarket/products.sql');
        $products_sql = file_get_contents($products_path);
        DB::statement($products_sql);

        $coupons_path = public_path('sql/xsimarket/coupons.sql');
        $coupons_sql = file_get_contents($coupons_path);
        DB::statement($coupons_sql);

        $category_product_path = public_path('sql/xsimarket/category_product.sql');
        $category_product_sql = file_get_contents($category_product_path);
        DB::statement($category_product_sql);

        $orders_path = public_path('sql/xsimarket/orders.sql');
        $orders_sql = file_get_contents($orders_path);
        DB::statement($orders_sql);

        $order_product_path = public_path('sql/xsimarket/order_product.sql');
        $order_product_sql = file_get_contents($order_product_path);
        DB::statement($order_product_sql);

        $settings_path = public_path('sql/xsimarket/settings.sql');
        $settings_sql = file_get_contents($settings_path);
        DB::statement($settings_sql);

        $permissions_path = public_path('sql/xsimarket/permissions.sql');
        $permissions_sql = file_get_contents($permissions_path);
        DB::statement($permissions_sql);

        $shipping_classes_path = public_path('sql/xsimarket/shipping_classes.sql');
        $shipping_classes_sql = file_get_contents($shipping_classes_path);
        DB::statement($shipping_classes_sql);

        $tax_classes_path = public_path('sql/xsimarket/tax_classes.sql');
        $tax_classes_sql = file_get_contents($tax_classes_path);
        DB::statement($tax_classes_sql);

        $this->command->info('Seed completed from sql file!');
    }
}
