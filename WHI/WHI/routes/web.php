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

Route::get(
    '/no', function () {
        return view('welcome');
    }
)->name('login');

$nameSpace = 'App\Http\Controllers\User';
Route::namespace($nameSpace)->middleware('throttle')->group(
    function () {
        Route::resource('user', 'UserController');
        Route::post('login', 'UserOtherController@login');
        Route::resource('user.profile', 'ProfileController')->shallow();
        Route::resource('user.sections', 'SectionController')->shallow();
        Route::resource('user.contents', 'ContentController')->shallow();
        Route::post('ogp', 'UserOtherController@ogp');
        Route::resource('blog', 'BlogController');
        Route::post('sendEmail', 'UserOtherController@sendEmail');
        Route::post('checkId', 'UserOtherController@checkId');
        Route::post('resetPassword', 'UserOtherController@resetPassword');
    }
);
