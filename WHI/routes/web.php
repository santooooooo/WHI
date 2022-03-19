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
        Route::resource('user', 'BasicController');
        Route::post('login', 'OtherController@login');
        Route::resource('user.profile', 'ProfileController')->shallow();
        Route::resource('user.sections', 'SectionController')->shallow();
        Route::resource('user.contents', 'ContentController')->shallow();
        Route::post('ogp', 'OtherController@ogp');
        Route::resource('blog', 'BlogController');
    }
);
