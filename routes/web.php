<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ItemController;
use App\Models\Coins_converting_history;
use App\Models\User;
use App\Models\Item;
use App\Models\Event;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// モデル動作チェック用
Route::group(
    ['prefix' => 'api', 'as' => 'api'],
    function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('/register', [AuthController::class, 'register']);

        Route::get('/csrf-token', function (Request $request) {
            $token = $request->session()->token();
            return $token;
        });

        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

        // ユーザ一覧
        Route::get('/users', [UserController::class, 'show']);
        Route::delete('/users/{user_id}', [UserController::class, 'destroy']);
        Route::put('/users/role/{user_id}', [UserController::class, 'updateRole']);

        //マイページ
        Route::get('/userInfo', [UserController::class, 'userInfo']);
        //詳細ページ
        Route::get('/detail/point/this_month', [UserController::class, 'detailPointThisMonth']);
        Route::get('/detail/point/history', [UserController::class, 'detailPointHistory']);
        Route::get('/detail/coin/convert', [UserController::class, 'detailCoinConvert']);
        Route::post('coin/convert', [UserController::class, 'coinConvert']);
        Route::get('/detail/coin/deposit', [UserController::class, 'detailCoinDeposit']);
        Route::get('/detail/coin/estimate', [UserController::class, 'detailCoinEstimate']);
        // アイテム出品
        Route::post('/createItem', [ItemController::class, 'store']);
        // 自分の出品一覧
        Route::get('/mypage/myItems', [CardController::class, 'myItems']);
        // 自分の出品編集
        Route::post('/itemUpdateByUser/{id}', [ItemController::class, 'updateMyItem']);

        // topページカード一覧
        Route::get('/cards', [CardController::class, 'cards']);

        // カード一枚分
        Route::get('/card/{id}', [CardController::class, 'card']);

        // アイテム詳細画面
        Route::get('/items/{item_id}', [ItemController::class, 'item']);
        Route::get('/items/payment/{item_id}', [ItemController::class, 'storeRentalData']);

        // アイテムレンタル完了画面
        Route::get('/item_thanks/{item_id}', [ItemController::class, 'item_thanks']);

        // レンタル中のアイテム一覧画面
        Route::get('/mypage/rentals/all/{user_id}', [CardController::class, 'rental_cards']);
        Route::get('/mypage/rentals/{item_id}', [ItemController::class, 'rental_detail']);
        Route::get('/mypage/rentals/return/{rental_id}', [ItemController::class, 'storeReturnData']);

        Route::get('/show/user', function () {
            $users = User::get();
            return $users;
        });

        // 管理者用

        // 換金申請一覧
        Route::get('/show/conversion', [AdminController::class, 'showConversion']);
        // 換金処理
        Route::get('/convert/{conversion_id}', [AdminController::class, 'convert']);

        // 出品申請一覧
        Route::get('/show/request', [ItemController::class, 'requests']);
        // 出品許可
        Route::post('/confirm/{id}', [ItemController::class, 'setPrice']);
        // 出品却下
        Route::get('/reject/{id}', [ItemController::class, 'reject']);

        // カード全表示
        Route::get('/allCards', [CardController::class, 'allCards']);

        // アイテム編集保存
        Route::post('/itemUpdate/{id}', [ItemController::class, 'update']);




        // ロール
        Route::get('/role/{user_id}', function ($user_id) {
            $role = User::find($user_id)->role();
            dd($role);
        });

        // 自己紹介
        Route::get('/intro/{user_id}', function ($user_id) {
            $intro = User::find($user_id)->intro();
            dd($intro);
        });

        // レンタル関連
        Route::get('mylikes/{user_id}', function ($user_id) {
            $likes = User::find($user_id)->likes()->get();
            foreach ($likes as $like) {
                $item_id = $like->item_id;
                var_dump(Item::find($item_id)->name);
            } // いいね
        });
        Route::get('/rentals/{user_id}', function ($user_id) {
            $rentals = User::find($user_id)->borrow()->get();
            dd($rentals);
        }); // 自分が借りた・借りてる
        Route::get('/lend/{user_id}', function ($user_id) {
            $items = User::find($user_id)->lend()->get();
            dd($items);
        }); // 自分が貸した・貸してる
        Route::get('/items/{user_id}', function ($user_id) {
            $items = User::find($user_id)->items()->get();
            dd($items);
        }); // 自分の出品一覧

        // イベント関連
        Route::get('/events/{user_id}', function ($user_id) {
            $events = User::find($user_id)->events()->get();
            dd($events);
        }); // 主催

        // コイン関連
        Route::get('converting/{user_id}', function ($user_id) {
            $items = User::find($user_id)->converting()->get();
            dd($items);
        }); // 換金履歴
        Route::get('coins/rental/{user_id}', function ($user_id) {
            $items = User::find($user_id)->coinsByRental()->get();

            dd($items);
        }); // 貸出での獲得
        Route::get('coins/event/{user_id}', function ($user_id) {
            $items = User::find($user_id)->coinsByEvent()->get();

            dd($items);
            // $items[0]->event() でイベントタイトル
        }); // イベントでの獲得

        // ポイント関連
        Route::get('points/event/{user_id}', function ($user_id) {
            $histories = User::find($user_id)->eventPoint()->get();
            dd($histories);
        }); // イベント参加での利用
        Route::get('points/rental/{user_id}', function ($user_id) {
            $histories = User::find($user_id)->rentalPoint()->get();
            dd($histories);
        }); // アイテムレンタルでの利用

        // イベント
        // 主催者
        Route::get('owner/{event_id}', function ($event_id) {
            $owner = Event::find($event_id)->owner();
            var_dump($owner);
        });
        //ステータス
        Route::get('status/{event_id}', function ($event_id) {
            $status = Event::find($event_id)->status();
            dd($status);
        });
        // 参加者
        Route::get('attendance/{event_id}', function ($event_id) {
            $participants = Event::find($event_id)->participants();
            dd($participants);
        });

        // アイテム
        // 出品者
        Route::get('itemowner/{item_id}', function ($item_id) {
            $owner = Item::find($item_id)->owner();
            dd($owner);
        });
        // 貸出履歴
        Route::get('history/{item_id}', function ($item_id) {
            dd(
                Item::find($item_id)->history()
            );
        });
        // いいねした人
        Route::get('likers/{item_id}', function ($item_id) {
            $items = Item::find($item_id)->likes();
            dd($items);
        });
        }
    );
    