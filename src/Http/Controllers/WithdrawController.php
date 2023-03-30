<?php


namespace Xsimarket\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xsimarket\Database\Models\Balance;
use Xsimarket\Database\Models\Withdraw;
use Xsimarket\Database\Repositories\WithdrawRepository;
use Xsimarket\Enums\Permission;
use Xsimarket\Enums\WithdrawStatus;
use Xsimarket\Exceptions\XsimarketException;
use Xsimarket\Http\Requests\UpdateWithdrawRequest;
use Xsimarket\Http\Requests\WithdrawRequest;
use Prettus\Validator\Exceptions\ValidatorException;


class WithdrawController extends CoreController
{
    public $repository;

    public function __construct(WithdrawRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Collection|Withdraw[]
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?   $request->limit : 15;
        $withdraw = $this->fetchWithdraws($request);
        return $withdraw->paginate($limit);
    }

    public function fetchWithdraws(Request $request)
    {
        $user = $request->user();
        $shop_id = isset($request['shop_id']) && $request['shop_id'] != 'undefined' ? $request['shop_id'] : false;
        if ($shop_id) {
            if ($user->shops->contains('id', $shop_id)) {
                return $this->repository->with(['shop'])->where('shop_id', $shop_id);
            } elseif ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                return $this->repository->with(['shop'])->where('shop_id', '=', $shop_id);
            } else {
                throw new XsimarketException(NOT_AUTHORIZED);
            }
        } else {
            if ($user && $user->hasPermissionTo(Permission::SUPER_ADMIN)) {
                return $this->repository->with(['shop'])->where('id', '!=', null);
            } else {
                throw new XsimarketException(NOT_AUTHORIZED);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WithdrawRequest $request
     * @return mixed
     * @throws ValidatorException
     */
    public function store(WithdrawRequest $request)
    {
        $validatedData = $request->validated();
        if (isset($validatedData['shop_id'])) {
            $balance = Balance::where('shop_id', '=', $validatedData['shop_id'])->first();
            if (isset($balance->current_balance) && $balance->current_balance >= $validatedData['amount']) {
                $withdraw = $this->repository->create($validatedData);
                $balance->withdrawn_amount = $balance->withdrawn_amount + $validatedData['amount'];
                $balance->current_balance = $balance->current_balance - $validatedData['amount'];
                $balance->save();
                $withdraw->status = WithdrawStatus::PENDING;
                $withdraw->shop = $withdraw->shop;
                return $withdraw;
            } else {
                throw new XsimarketException(INSUFFICIENT_BALANCE);
            }
        } else {
            throw new XsimarketException(WITHDRAW_MUST_BE_ATTACHED_TO_SHOP);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id)
    {
        $request->id = $id;
        return $this->fetchSingleWithdraw($request);
    }

    public function fetchSingleWithdraw(Request $request)
    {
        $id = $request->id;
        try {
            $withdraw = $this->repository->with(['shop'])->findOrFail($id);
        } catch (\Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }
        if ($request->user() && ($request->user()->hasPermissionTo(Permission::SUPER_ADMIN) || $request->user()->shops->contains('id', $withdraw->shop_id))) {
            return $withdraw;
        } else {
            throw new XsimarketException(NOT_AUTHORIZED);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WithdrawRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateWithdrawRequest $request, $id)
    {
        throw new XsimarketException(ACTION_NOT_VALID);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user() && $request->user()->hasPermissionTo(Permission::SUPER_ADMIN)) {
            try {
                return $this->repository->findOrFail($id)->delete();
            } catch (\Exception $e) {
                throw new XsimarketException(NOT_FOUND);
            }
        } else {
            throw new XsimarketException(NOT_AUTHORIZED);
        }
    }

    public function approveWithdraw(Request $request)
    {
        $id = $request->id;
        $status = $request->status->value ?? $request->status;
        try {
            $withdraw = $this->repository->findOrFail($id);
        } catch (Exception $e) {
            throw new XsimarketException(NOT_FOUND);
        }

        $withdraw->status = $status;

        $withdraw->save();

        return $withdraw;
    }
}
