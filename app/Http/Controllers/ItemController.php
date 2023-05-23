<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Event;
use App\Models\Rental;
use App\Models\Rental_points_withdraw_history;
use App\Models\User;

class ItemController extends Controller
{
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

    // レンタル完了
    public function storeRentalData($item_id){
        $rental = new Rental;
        $rental->item_id = $item_id;
        $rental->user_id = Auth::id();
        $rental->owner_id = Item::shownCards()->find($item_id)->owner_id;
        $rental->save();

        $parent_id = $rental->id; // 親テーブルのIDを取得

        $rental_history = new Rental_points_withdraw_history;
        $rental_history->user_id = Auth::id();
        $rental_history->amount = Item::shownCards()->find($item_id)->price;
        $rental_history->rental_id = $parent_id;
        $rental_history->type = 1;
        $rental_history->save();

        return response()->json('レンタル完了', 200);
    }
}
