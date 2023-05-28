<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
        } else {
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

    // 新規出品
    public function store(Request $request)
    {
        $image = $request->file("image");
        $path = $image->store("public/image");
        $item = new Item;
        $item->name = $request["itemName"];
        $item->owner_id = Auth::id();
        $item->detail = $request["detail"];
        $item->status_id = 1;
        $item->likes = 0;
        $item->image_url = "http://localhost:80" . Storage::url($path);
        $item->save();
        return response()->json("出品完了", 200);
    }

        // ＝＝＝＝＝＝＝＝＝＝＝レンタル詳細画面＝＝＝＝＝＝＝＝＝＝＝

    public function rental_detail($item_id)
    {
        $user_id = 5;
        // $user_id = Auth::id();
        $rental_info = Rental::where('user_id', $user_id)->where('item_id', $item_id)->get()->last();
        $rental_id = $rental_info->id;
        $rental_day = (new Carbon($rental_info->created_at))->toDateString();
        
        $rental_item = Item::shownCards()->find($item_id);
        $slack_id = $rental_item->ownerSlackId();
        $status = $rental_item->status_id;
        $price = $status === 1 ? "???" : $rental_item->price;
        $created_at = (new Carbon($rental_item->created_at))->toDateString();

        return [
            "id" => $rental_item->id,
            "rental_id" => $rental_id,
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

    // ＝＝＝＝＝＝＝＝＝＝＝アイテム詳細→決済＝＝＝＝＝＝＝＝＝＝＝
    public function storeRentalData($item_id){
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

    // ＝＝＝＝＝＝＝＝＝＝＝アイテム詳細→返却＝＝＝＝＝＝＝＝＝＝＝
    public function storeReturnData($rental_id)
    {
        // TODO  Rental と Withdraw history (type = 2 継続分)をsoft delete

        $item_id = Rental::find($rental_id)->item_id;
        Rental::find($rental_id)->delete();
        Rental_points_withdraw_history::where('rental_id', $rental_id)->where('type', 2)->delete();
        Item::find($item_id)->update(['status_id' => 3]);
        return response()->json('返却完了', 200);
    }

    // 出品申請一覧
    public function requests()
    {
        $applying = Item::statusEqual(1)->get();
        $data = [];
        foreach ($applying as $index => $erem) {
            $data[$index] = [
                'id' => $erem->id,
                'user_name' => $erem->owner(),
                'item_name' => $erem->name,
                'RequestDateTime' => (new Carbon($erem->created_at))->toDateTimeString()
            ];
        }
        return response()->json($data, 200);
    }
    // 出品許可
    public function setPrice($id, Request $request)
    {
        $item = Item::where('id', $id)->first();
        $item->update(['status_id' => 3]);
        $item->update(['price' => $request['price']]);
        return response()->json('完了', 200);
    }
    // 出品却下
    public function reject ($id)
    {
        $item = Item::where('id', $id)->first();
        $item->update(['status_id' => 2]);
        return response()->json('完了', 200);
    }

    // アイテム情報変更
    public function update ($id, Request $request)
    {
        $item = Item::where('id', $id)->first();
        $item->update([
            'name' => $request['itemName'],
            'detail' => $request['detail'],
            'price' => $request['price'],
        ]);
        if($request->file('image')){
            $image = $request->file('image');
            $path = $image->store('public/image');
            $item->update(['image_url' => 'http://localhost:80' . Storage::url($path)]);
        }
        return response()->json('更新完了', 200);
    }
}
