<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Event;

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
    // ①web.phpにメソッド追加 ②テーブルに挿入
    // 例：Route::put('/users/role/{user_id}', [UserController::class, 'updateRole']);

    /*
        public function storeRentalData($user_id, $item_id, $owner_id, $request){
        $newRoleId = $request->is_admin? 1: 2;
        User::find($user_id)->update(['role_id' => $newRoleId]);
    }
    rentalsテーブルに以下の情報を挿入する
    id	int(auto)	
    item_id	int	（もらってくる）
    user_id	int	（もらってくる）
    owner_id	int	（もらってくる）
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
