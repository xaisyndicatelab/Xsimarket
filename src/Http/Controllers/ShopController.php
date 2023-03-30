<?php

namespace Xsimarket\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Xsimarket\Enums\Permission;
use Xsimarket\Database\Models\Shop;
use Xsimarket\Database\Models\User;
use Illuminate\Support\Facades\Log;
use Xsimarket\Database\Models\Balance;
use Xsimarket\Database\Models\Product;
use Xsimarket\Exceptions\XsimarketException;
use Xsimarket\Http\Requests\ShopCreateRequest;
use Xsimarket\Http\Requests\ShopUpdateRequest;
use Xsimarket\Http\Requests\UserCreateRequest;
use Xsimarket\Database\Repositories\ShopRepository;

class ShopController extends CoreController
{
    public $repository;

    public function __construct(ShopRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Shop[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        return $this->fetchShops($request)->paginate($limit)->withQueryString();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function fetchShops(Request $request)
    {
        $language = $request->language ?? DEFAULT_LANGUAGE;
        return $this->repository->withCount(['orders'])->withCount(['products' => function ($query) use ($language) {
            $query->where('language', $language);
        }])->with(['owner.profile'])->where('id', '!=', null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ShopCreateRequest $request
     * @return mixed
     * @throws XsimarketException
     */
    public function store(ShopCreateRequest $request)
    {
        if ($request->user()->hasPermissionTo(Permission::STORE_OWNER)) {
            return $this->repository->storeShop($request);
        } else {
            throw new XsimarketException(NOT_AUTHORIZED);
        }
    }

    /**
     * Display the specified shop.
     *
     * @param $slug
     * @param Request $request
     * @return JsonResponse
     * @throws XsimarketException
     */
    public function show($slug, Request $request)
    {
        $language = $request->language ?? DEFAULT_LANGUAGE;
        $shop = $this->repository
            ->with(['categories', 'owner'])
            ->withCount(['orders'])
            ->withCount(['products' => function ($query) use ($language) {
                $query->where('language', $language);
            }]);
        if ($request->user() && ($request->user()->hasPermissionTo(Permission::SUPER_ADMIN) || $request->user()->shops->contains('slug', $slug))) {
            $shop = $shop->with('balance');
        }
        try {
            $shop = $shop->findOneByFieldOrFail('slug', $slug);
            return $shop;
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ShopUpdateRequest $request
     * @param int $id
     * @return array
     * @throws XsimarketException
     */
    public function update(ShopUpdateRequest $request, $id)
    {
        $request->id = $id;
        return $this->updateShop($request);
    }

    public function updateShop(Request $request)
    {
        $id = $request->id;
        if ($request->user()->hasPermissionTo(Permission::SUPER_ADMIN) || ($request->user()->hasPermissionTo(Permission::STORE_OWNER) && ($request->user()->shops->contains($id)))) {
            return $this->repository->updateShop($request, $id);
        } else {
            throw new XsimarketException(NOT_AUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws XsimarketException
     */
    public function destroy(Request $request, $id)
    {
        $request->id = $id;
        return $this->deleteShop($request);
    }

    public function deleteShop(Request $request)
    {
        $id = $request->id;
        if ($request->user()->hasPermissionTo(Permission::SUPER_ADMIN) || ($request->user()->hasPermissionTo(Permission::STORE_OWNER) && ($request->user()->shops->contains($id)))) {
            try {
                $shop = $this->repository->findOrFail($id);
            } catch (\Exception $e) {
                throw new XsimarketException(NOT_FOUND);
            }
            $shop->delete();
            return $shop;
        } else {
            throw new XsimarketException(NOT_AUTHORIZED);
        }
    }

    public function approveShop(Request $request)
    {
        $id = $request->id;
        $admin_commission_rate = $request->admin_commission_rate;
        try {
            $shop = $this->repository->findOrFail($id);
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }
        $shop->is_active = true;
        $shop->save();
        $balance = Balance::firstOrNew(['shop_id' => $id]);
        $balance->admin_commission_rate = $admin_commission_rate;
        $balance->save();
        return $shop;
    }

    public function disApproveShop(Request $request)
    {
        $id = $request->id;
        try {
            $shop = $this->repository->findOrFail($id);
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }

        $shop->is_active = false;
        $shop->save();

        Product::where('shop_id', '=', $id)->update(['status' => 'draft']);

        return $shop;
    }

    public function addStaff(UserCreateRequest $request)
    {
        if ($this->repository->hasPermission($request->user(), $request->shop_id)) {
            $permissions = [Permission::CUSTOMER, Permission::STAFF];
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'shop_id' => $request->shop_id,
                'password' => Hash::make($request->password),
            ]);

            $user->givePermissionTo($permissions);

            return true;
        } else {
            throw new XsimarketException(NOT_AUTHORIZED);
        }
    }

    public function deleteStaff(Request $request, $id)
    {
        $request->id = $id;
        return $this->removeStaff($request);
    }

    public function removeStaff(Request $request)
    {
        $id = $request->id;
        try {
            $staff = User::findOrFail($id);
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }
        if ($request->user()->hasPermissionTo(Permission::STORE_OWNER) || ($request->user()->hasPermissionTo(Permission::STORE_OWNER) && ($request->user()->shops->contains('id', $staff->shop_id)))) {
            $staff->delete();
            return $staff;
        } else {
            throw new XsimarketException(NOT_AUTHORIZED);
        }
    }

    public function myShops(Request $request)
    {
        $user = $request->user;
        return $this->repository->where('owner_id', '=', $user->id)->get();
    }

    public function topShops(Request $request)
    {
        $language = $request->language ?? DEFAULT_LANGUAGE;
        $limit = $request->limit ? $request->limit : 10;
        $range = !empty($request->range) && $request->range !== 'undefined' ? $request->range : '';
        return $this->repository->withCount(['orders' => function ($query) use ($range) {
            if ($range) {
                $query->whereDate('created_at', '>', Carbon::now()->subDays($range + 2));
            }
        }])->withCount(['products' => function ($query) use ($language) {
            $query->where('language', $language);
        }])->orderBy('orders_count', 'desc')->paginate($limit);

    }

    /**
     * Popular products by followed shops
     *
     * @param Request $request
     * @return array
     * @throws XsimarketException
     */
    public function followedShopsPopularProducts(Request $request)
    {
        $language = $request->language ?? DEFAULT_LANGUAGE;

        $request->validate([
            'limit' => 'numeric',
        ]);

        try {
            $user = $request->user();
            $userShops = User::where('id', $user->id)->with('follow_shops')->get();
            $followedShopIds = $userShops->first()->follow_shops->pluck('id')->all();
            $limit = $request->limit ? $request->limit : 10;

            $products_query = Product::withCount('orders')->with(['shop'])->whereIn('shop_id', $followedShopIds)->where('language', $language)->orderBy('orders_count', 'desc');

            return $products_query->take($limit)->get();
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }
    }

    /**
     * Get all the followed shops of logged-in user
     *
     * @param Request $request
     * @return mixed
     */
    public function userFollowedShops(Request $request)
    {
        $limit = $request->limit ? $request->limit : 15;
        $user = $request->user();
        $currentUser = User::where('id', $user->id)->first();

        return $currentUser->follow_shops()->paginate($limit);

    }

    /**
     * Get boolean response of a shop follow/unfollow status
     *
     * @param Request $request
     * @return bool
     * @throws XsimarketException
     */
    public function userFollowedShop(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|numeric',
        ]);

        try {
            $user = $request->user();
            $userShops = User::where('id', $user->id)->with('follow_shops')->get();
            $followedShopIds = $userShops->first()->follow_shops->pluck('id')->all();

            $shop_id = (int)$request->input('shop_id');

            return in_array($shop_id, $followedShopIds);
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }
    }

    /**
     * Follow/Unfollow shop
     *
     * @param Request $request
     * @return bool
     * @throws XsimarketException
     */
    public function handleFollowShop(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|numeric',
        ]);

        try {
            $user = $request->user();
            $userShops = User::where('id', $user->id)->with('follow_shops')->get();
            $followedShopIds = $userShops->first()->follow_shops->pluck('id')->all();

            $shop_id = (int)$request->input('shop_id');

            if (in_array($shop_id, $followedShopIds)) {
                $followedShopIds = array_diff($followedShopIds, [$shop_id]);
            } else {
                $followedShopIds[] = $shop_id;
            }

            $response = $user->follow_shops()->sync($followedShopIds);

            if (count($response['attached'])) {
                return true;
            }

            if (count($response['detached'])) {
                return false;
            }
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }
    }
}
