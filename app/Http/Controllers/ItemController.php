<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Event;
use App\Models\Rental;
use App\Models\Rental_points_withdraw_history;

class ItemController extends Controller
{
    public function item($item_id)
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
            "likes" => $item->likes,
            "owner" => "出品者：" . $item->owner(),
            "slack_id" => $slack_id,
            "detail" => $item->detail,
            "status" => $status,
            "price" => $price,
            "created_at" => $created_at,
            "history" => $item->history()
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

    // レンタル時のPOSTまとめ
    // ①web.phpにメソッド追加 Route::post('/items/rental/{user_id}', [ItemController::class, 'storeRentalData']);
    // ②テーブルに挿入
    public function storeRentalData($item_id, $request){
        $rental = new Rental;
        $rental->item_id = $item_id;
        $rental->user_id = $request->user_id;
        $rental->owner_id = Item::shownCards()->find($item_id)->owner();
        $rental->save();

        $parent_id = $rental->id; // 親テーブルのIDを取得

        $rental_history = new Rental_points_withdraw_history;
        $rental_history->user_id = $request->user_id;
        $rental_history->amount = Item::shownCards()->find($item_id)->price;
        $rental_history->rental_id = $parent_id;
        $rental_history->type = 1;
        $rental_history->save();
    }

    /* rentalsテーブルに以下の情報を挿入する
    id	int(auto)	
    item_id	int	（もらってくる）
    user_id	int	（もらってくる）
    owner_id	int	（itemのownerからもらってくる）
    created_at	date	
    deleted_at	date

    rental_points_withdraw_history	
    id	int(auto)	
    user_id	int	（済）
    amount	int	　（itemのpriceからもらってくる）
    rental_id	int	　（itemのidと同値）
    type	int	（新規なので1！）
    created_at	date
    */
}
