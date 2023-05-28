<?php

namespace App\Http\Controllers;

use App\Models\Coins_converting_history;
use App\Models\Rental_coins_deposit_history;
use App\Models\Rental_points_withdraw_history;
use App\Models\User;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    //

    public function show()
    {
        $users = User::get();
        $user_list = [];
        foreach ($users as $index => $user) {
            $user_name = $user->name;
            $listed_items = $user->items()->where('items.status_id', 3)->count();
            $coin_amount = $user->coin;
            $point_amount = $user->point;
            $is_admin = $user->role() === 'admin';

            $user_list[$index] = [
                "id" => $user->id,
                "name" => $user_name,
                "listed_items" => $listed_items,
                "coin_amount" => $coin_amount,
                "point_amount" => $point_amount,
                "is_admin" => $is_admin,
            ];
        }

        return $user_list;
    }

    public function destroy($user_id): JsonResponse
    {
        User::find($user_id)->delete();
        return response()->json('削除完了', 200);
    }

    public function updateRole(Request $request): JsonResponse
    {
        $newRoleId = $request['is_admin'] ? 1: 2;
        User::find($request['user_id'])->update(['role_id' => $newRoleId]);
        return response()->json('変更完了', 200);
    }


    public function userInfo()
    {

        $user = Auth::user();
        $lastMonth = Carbon::now()->subMonth()->endOfMonth();
        $history = Rental_points_withdraw_history::where('user_id', $user->id)
            ->where('created_at', '<=', $lastMonth)
            ->sum('amount');
        $deposit = Rental_coins_deposit_history::where('user_id', $user->id)->sum('amount');
        $estimate = Rental::estimateNextMonthCoin($user->id);

        $response = [
            'account' => [
                'name' => $user->name,
            ],
            'point' => [
                'this_month' => $user->point,
                'history' => $history,
            ],
            'coin' => [
                'hold' => $user->coin,
                'deposit' => $deposit,
                'estimate' => $estimate,
            ],
        ];

        return response()->json($response);
    }

    public function detailPointThisMonth()
    {
        $use_id = Auth::id();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data = Rental_points_withdraw_history::where('user_id', $use_id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->with(['rental_relation' => function ($query) {
                $query->withTrashed();
            }, 'rental_relation.item_relation' => function ($query) {
                $query->withTrashed();
            }])
            ->get()->map(function ($withdraw) {
                return [
                    'id' => $withdraw->id,
                    'type' => [
                        'id' => $withdraw->type,
                        'name' => $withdraw->type === 1 ? '新規' : '継続',
                    ],
                    'date' => $withdraw->created_at->format('Y-m-d'),
                    'name' => $withdraw->rental_relation->item_relation->name,
                    'amount' => $withdraw->amount,
                ];
            });

        return $data;
    }

    public function detailPointHistory()
    {
        $user_id = Auth::user();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data = Rental_points_withdraw_history::where('user_id', $user_id)
            ->where(function ($query) use ($currentMonth, $currentYear) {
                $query->where('created_at', '<', "$currentYear-$currentMonth-01");
            })
            ->orderBy('created_at', 'desc')
            ->with(['rental_relation' => function ($query) {
                $query->withTrashed();
            }, 'rental_relation.item_relation' => function ($query) {
                $query->withTrashed();
            }])
            ->get()->map(function ($withdraw) {
                return [
                    'id' => $withdraw->id,
                    'type' => [
                        'id' => $withdraw->type,
                        'name' => $withdraw->type === 1 ? '新規' : '継続',
                    ],
                    'date' => $withdraw->created_at->format('Y-m-d'),
                    'name' => $withdraw->rental_relation->item_relation->name,
                    'amount' => $withdraw->amount,
                ];
            });

        return $data;
    }

    public function detailCoinDeposit()
    {
        $user_id = Auth::id();

        $data = Rental_coins_deposit_history::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->with(['rental_relation' => function ($query) {
                $query->withTrashed();
            }, 'rental_relation.item_relation' => function ($query) {
                $query->withTrashed();
            }])
            ->get()->map(function ($deposit) {
                return [
                    'id' => $deposit->id,
                    'type' => [
                        'id' => $deposit->type,
                        'name' => $deposit->type === 1 ? '新規' : '継続',
                    ],
                    'date' => $deposit->created_at->format('Y-m-d'),
                    'name' => $deposit->rental_relation->item_relation->name,
                    'amount' => $deposit->amount,
                ];
            });
        return $data;
    }

    public function detailCoinEstimate()
    {
        $user_id = Auth::id();

        $data = Rental::where('owner_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->with(['item_relation' => function ($query) {
                $query->withTrashed();
            }])
            ->get()->map(function ($rental) {
                $is_new = (date('Y-m') == date('Y-m', strtotime($rental->created_at)));
                return [
                    'id' => $rental->id,
                    'type' => [
                        'id' => $is_new ? 1 : 2, //rentalのcreated_atが今月の場合
                        'name' => $is_new ? '新規' : '継続',
                    ],
                    'date' => $rental->created_at->format('Y-m-d'),
                    'name' => $rental->item_relation->name,
                    'amount' => $rental->amount,
                ];
            });
        return $data;
    }

    public function detailCoinConvert()
    {
        $user_id = Auth::id();

        $data = Coins_converting_history::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get()->map(function ($convert) {
                return [
                    'id' => $convert->id,
                    'type' => [
                        'id' => $convert->status === 9 ? 1 : 2,
                        'name' => $convert->status === 9 ? '獲得済' : '処理中',
                    ],
                    'date' => $convert->created_at->format('Y-m-d'),
                    'amount' => $convert->amount,
                ];
            });
        return $data;
    }

    public function coinConvert(Request $request)
    {
        $user_id = Auth::id();

        try {
            DB::beginTransaction();

            $user = User::find($user_id);
            if ($user->coin < $request->amount) {
                return response()->json([
                    'message' => '申請失敗',
                    'error' => '所持コインが足りません',
                ], 400);
            }

            $user->coin -= $request->amount;
            $user->save();

            $history = new Coins_converting_history;
            $history->user_id = $user_id;
            $history->amount = $request->amount;
            $history->status = 10;
            $history->save();

            DB::commit();

            return response()->json('申請完了', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '申請失敗',
                'error' => $e->getMessage(),
            ], 500);
        } 
    }
}
