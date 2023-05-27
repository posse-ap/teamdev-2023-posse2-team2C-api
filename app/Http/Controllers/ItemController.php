<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Rental;
use App\Models\Rental_points_withdraw_history;
use App\Models\User;

class ItemController extends Controller
{
    // ＝＝＝＝＝＝＝＝＝＝＝アイテム詳細画面＝＝＝＝＝＝＝＝＝＝＝
    public function item($item_id)
    {
        $item = Item::shownCards()->find($item_id);
        $slack_id = $item->ownerSlackId();
        $status = $item->status_id;
        $price = $status === 1 ? "???" : $item->price;
        $created_at = (new Carbon($item->created_at))->toDateString();
        if (Auth::check()) {
            $user_point = Auth::user()->point;
        }else{
            $user_point = null;
        }

        return [
            "id" => $item->id,
            "image_url" => $item->image_url,
            "name" => $item->name,
            "likes" => $item->likes,
            "owner" => "出品者：" . $item->owner(),
            "slack_id" => $slack_id,
            "detail" => $item->detail,
            "status" => $status,
            "price" => $price,
            "created_at" => $created_at,
            "history" => $item->history(),
            "user_point" => $user_point
        ];
    }

    public function item_thanks($item_id)
    {
        $item = Item::shownCards()->find($item_id);
        $slack_id = $item->ownerSlackId();
        $status = $item->status_id;
        $price = $status === 1 ? "???" : $item->price;
        $created_at = (new Carbon($item->created_at))->toDateString();

        return [
            "id" => $item->id,
            "image_url" => $item->image_url,
            "name" => $item->name,
            "owner" => $item->owner(),
            "slack_id" => $slack_id,
            "status" => $status,
            "price" => $price,
            "created_at" => $created_at,
            "history" => $item->history()
        ];
    }

    // ＝＝＝＝＝＝＝＝＝＝＝レンタル詳細画面＝＝＝＝＝＝＝＝＝＝＝

    public function rental_detail($item_id)
    {
        $user_id = Auth::id();
        $rental_days = (Rental::where('user_id', $user_id)->where('item_id', $item_id)->get()->pluck('created_at'));
        $rental_at = null;
        foreach ($rental_days as $rental_day) {
            $rental_at = $rental_day;
        }//foreachしてるのは、同じ人が同じものを借りた場合、最後のものを取ってくるため＋pluckが配列で出力するため。
        $rental_day = (new Carbon($rental_at))->toDateString();
        $rental_item = Item::shownCards()->find($item_id);
        $slack_id = $rental_item->ownerSlackId();
        $status = $rental_item->status_id;
        $price = $status === 1 ? "???" : $rental_item->price;
        $created_at = (new Carbon($rental_item->created_at))->toDateString();

        return [
            "id" => $rental_item->id,
            "image_url" => $rental_item->image_url,
            "name" => $rental_item->name,
            "likes" => $rental_item->likes,
            "owner" => "出品者：" . $rental_item->owner(),
            "slack_id" => $slack_id,
            "detail" => $rental_item->detail,
            "status" => $status,
            "price" => $price,
            "created_at" => $created_at,
            "rental_day" => $rental_day,
            "history" => $rental_item->history()
        ];
    }

    // ＝＝＝＝＝＝＝＝＝＝＝アイテム詳細→決済のPOST＝＝＝＝＝＝＝＝＝＝＝
    public function storeRentalData($item_id)
    {
        $user_id = Auth::id();
        $item_price = Item::shownCards()->find($item_id)->price;

        $rental = new Rental;
        $rental->item_id = $item_id;
        $rental->user_id = $user_id;
        $rental->owner_id = Item::shownCards()->find($item_id)->owner_id;
        $rental->save();

        $parent_id = $rental->id; // 親テーブルのIDを取得

        $rental_history = new Rental_points_withdraw_history;
        $rental_history->user_id = $user_id;
        $rental_history->amount = $item_price;
        $rental_history->rental_id = $parent_id;
        $rental_history->type = 1;
        $rental_history->save();

        $user = User::find($user_id);
        $user->point -=  $item_price;
        $user->save();

        $item = Item::find($item_id);
        $item->status_id = 4;
        $item->save();

        return response()->json('レンタル完了', 200);
    }
}
