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

Route::get(
    '/', function () {
        return view('home');
    }
);

$nameSpace = 'App\Http\Controllers\User';
Route::namespace($nameSpace)->middleware('throttle:api')->group(
    function () {
        Route::resource('user', 'UserController')->middleware('user.auth');
        Route::post('login', 'UserOtherController@login')->middleware('user.auth');
        Route::resource('user.profile', 'ProfileController')->shallow()->middleware('user.auth');
        Route::resource('user.sections', 'SectionController')->shallow()->middleware('user.auth');
        Route::resource('user.contents', 'ContentController')->shallow()->middleware('user.auth');
        Route::post('ogp', 'UserOtherController@ogp');
        Route::resource('blog', 'BlogController')->middleware('user.auth');
        Route::post('sendEmail', 'UserOtherController@sendEmail');
        Route::post('checkId', 'UserOtherController@checkId');
        Route::post('resetPassword', 'UserOtherController@resetPassword');
    }
);
