<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

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

// User go to top-page specifying no word in URL
Route::get(
    '/', function () {
        return view('home');
    }
);

$nameSpace = 'App\Http\Controllers\User';
Route::namespace($nameSpace)->middleware('throttle:api')->group(
    function () {
	// ユーザーのリクエストに関する処理
        Route::resource('user', 'UserController')->middleware('user.auth');

	// ユーザーのログインのリクエストに関する処理
        Route::post('login', 'UserOtherController@login')->middleware('user.auth');

	// プロフィールのリクエストに関する処理
        Route::resource('user.profile', 'ProfileController')->shallow()->middleware('user.auth');

	// セクションのリクエストに関する処理
        Route::resource('user.sections', 'SectionController')->shallow()->middleware('user.auth');

	// セクション内のコンテンツのリクエストに関する処理
        Route::resource('user.contents', 'ContentController')->shallow()->middleware('user.auth');

	// OGPのリクエストに関する処理
        Route::post('ogp', 'UserOtherController@ogp');

	// ブログのリクエストに関する処理
        Route::resource('blog', 'BlogController')->middleware('user.auth');

	// パスワードリセットのためのメール送信のリクエストに関する処理
        Route::post('sendEmail', 'UserOtherController@sendEmail');

	// パスワードの再設定用のIDのリクエストに関する処理
        Route::post('checkId', 'UserOtherController@checkId');

	// パスワードの再設定のパスワードの再設定のリクエストに関する処理
	// テストメッセージてすてｓてｔーエラーのえらーえらえｒえてすてｓてｔエラーのえらーえらえｒえてすてｓてｔ
	// 日本語入力にほにｈにｎしたばあいｎしたばあいしたばあしたばしたｂしたしｔしｓにほにｈにｎエラーがえらえｒえた発生はっせいはっせはｓｓはｓはｈえらえｒえ
        Route::post('resetPassword', 'UserOtherController@resetPassword');
    }
);
