<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use App\Models\Item;
use App\Models\Event;

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
            ]);
        }

        $events = Event::shownCards()->get();
        foreach ($events as $event)
        {
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
            ]);
        }

        $cards = $cards->sortBy("created_at")->values()->toArray();
        return $cards;
    }
}
