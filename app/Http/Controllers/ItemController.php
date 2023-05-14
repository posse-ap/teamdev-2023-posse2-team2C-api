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
}
