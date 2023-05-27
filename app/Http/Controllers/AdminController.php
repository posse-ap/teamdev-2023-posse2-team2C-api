<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Coins_converting_history;

class AdminController extends Controller
{
    //
    public function showConversion() {
        $conversions = Coins_converting_history::get();
        $data = [];
        foreach($conversions as $index => $conversion) {
            $data[$index] = [
                'id' => $conversion->id,
                'is_converted' => $conversion->status === 9,
                'name' => $conversion->applier()->name,
                'conversion_type' => 'アマギフ',
                'coin_amount' => $conversion->amount,
                'applied_at' => (new Carbon($conversion->created_at))->toDateString(),
            ];
        }

        return response()->json($data, 200);
    }
}
