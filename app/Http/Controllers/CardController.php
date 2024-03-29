<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Item;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CardController extends Controller
{
    //
    public function cards()
    {
        $cards = collect([]);
        $items = Item::shownCards()->get();
        foreach ($items as $item) {
            $image_url = $item->image_url;
            $name = $item->name;
            $likes = $item->likes;
            $owner = "出品者：" . $item->owner();
            $status = $item->status_id;
            $price = $status === 1 ? "???" : $item->price;
            $created_at = (new Carbon($item->created_at))->toDateString();

            $cards->add(
                [
                    "image_url" => $image_url,
                    "name" => $name,
                    "likes" => $likes,
                    "owner" => $owner,
                    "status" => $status,
                    "price" => $price,
                    "is_item" => true,
                    "created_at" => $created_at,
                    "id" => $item->id,
                ]
            );
        }

        $events = Event::shownCards()->get();
        foreach ($events as $event) {
            $image_url = $event->image_url;
            $name = $event->name;
            $participants = $event->participants;
            $owner = "主催者：" . $event->owner();
            $status = $event->status_id;
            $date = $event->date;
            $created_at = (new Carbon($event->created_at))->toDateString();

            $cards->add(
                [
                    "image_url" => $image_url,
                    "name" => $name,
                    "participants" => $participants,
                    "owner" => $owner,
                    "status" => $status,
                    "date" => $date,
                    "is_item" => false,
                    "created_at" => $created_at,
                    "id" => $event->id,
                ]
            );
        }

        $cards = $cards->sortBy("created_at")->values()->toArray();
        return $cards;
    }

    public function allCards()
    {
        $cards = collect([]);
        $items = Item::get();
        foreach ($items as $item) {
            $image_url = $item->image_url;
            $name = $item->name;
            $likes = $item->likes;
            $owner = "出品者：" . $item->owner();
            $status = $item->status_id;
            $price = $status === 1 ? "???" : $item->price;
            $created_at = (new Carbon($item->created_at))->toDateString();

            $cards->add(
                [
                    "image_url" => $image_url,
                    "name" => $name,
                    "likes" => $likes,
                    "owner" => $owner,
                    "status" => $status,
                    "price" => $price,
                    "is_item" => true,
                    "created_at" => $created_at,
                    "id" => $item->id,
                ]
            );
        }

        $events = Event::shownCards()->get();
        foreach ($events as $event) {
            $image_url = $event->image_url;
            $name = $event->name;
            $participants = $event->participants;
            $owner = "主催者：" . $event->owner();
            $status = $event->status_id;
            $date = $event->date;
            $created_at = (new Carbon($event->created_at))->toDateString();

            $cards->add(
                [
                    "image_url" => $image_url,
                    "name" => $name,
                    "participants" => $participants,
                    "owner" => $owner,
                    "status" => $status,
                    "date" => $date,
                    "is_item" => false,
                    "created_at" => $created_at,
                    "id" => $event->id,
                ]
            );
        }

        $cards = $cards->sortBy("created_at")->values()->toArray();
        return $cards;
    }

    public function card($id)
    {
        $item = Item::where('id', $id)->first();
        $image_url = $item->image_url;
        $name = $item->name;
        $likes = $item->likes;
        $owner = "出品者：" . $item->owner();
        $status = $item->status_id;
        $price = $status === 1 ? "???" : $item->price;
        $created_at = (new Carbon($item->created_at))->toDateString();

        $card = collect([
            "id" => $item->id,
            "is_item" => true,
            "image_url" => $image_url,
            "name" => $name,
            "likes" => $likes,
            "owner" => $owner,
            "status" => $status,
            "price" => $price,
            "created_at" => $created_at,
            "detail" => $item->detail,
        ]);

        // return response()->json($card, 200);
        return $card;
    }

    // ＝＝＝＝＝＝＝＝＝＝＝レンタル一覧画面＝＝＝＝＝＝＝＝＝＝＝
    public function rental_cards($user_id)
    {
        $rental_item_ids = User::find($user_id)->borrow()->where('deleted_at', null)->get()->pluck('item_id')->toArray();
        $cards = collect([]);
        if ($rental_item_ids) {
            foreach (array_values($rental_item_ids) as $id) {
                $item = Item::find($id);
                if (is_null($item->image_url)) {
                    $image_url = null;
                } else {
                    $image_url = $item->image_url;
                }
                $item_id = $id;
                $name = $item->name;
                $likes = $item->likes;
                $owner = "出品者：" . $item->owner();
                $status = $item->status_id;
                $price = $status === 1 ? "???" : $item->price;
                $created_at = (new Carbon($item->created_at))->toDateString();

                $cards->add(
                    [
                        "image_url" => $image_url,
                        "name" => $name,
                        "likes" => $likes,
                        "owner" => $owner,
                        "status" => $status,
                        "price" => $price,
                        "is_item" => true,
                        "created_at" => $created_at,
                        "item_id" => $item_id
                    ]
                );
            }
            return $cards;
        } else {
            return [];
        }
    }

    // ログインユーザーの出品
    public function myItems()
    {
        $cards = collect([]);
        $items = Item::where('owner_id', Auth::id())->get();
        foreach ($items as $item) {
            $image_url = $item->image_url;
            $name = $item->name;
            $likes = $item->likes;
            $owner = "出品者：" . $item->owner();
            $status = $item->status_id;
            $price = $status === 1 ? "???" : $item->price;
            $created_at = (new Carbon($item->created_at))->toDateString();

            $cards->add(
                [
                    "image_url" => $image_url,
                    "name" => $name,
                    "likes" => $likes,
                    "owner" => $owner,
                    "status" => $status,
                    "price" => $price,
                    "is_item" => true,
                    "created_at" => $created_at,
                    "id" => $item->id,
                ]
            );
        }

        return response()->json($cards, 200);
    }
}
