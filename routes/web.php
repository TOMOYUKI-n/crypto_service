<?php

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
Auth::routes();

// 認証不要　TOPページ
Route::get('/', 'IndexController@top')->name('top');//ドメイントップ
Route::get('/term', 'IndexController@term')->name('term');// 利用規約への遷移
Route::get('/policy', 'IndexController@policy')->name('policy');// ポリシーへの遷移
Route::get('/home', 'HomeController@index')->name('home');
//Route::get('/test', 'indexController@test')->name('test');
//Route::get('/trendback', 'IndexController@trendback');//一時的な画面


// 認証必要
Route::group(['middleware' => 'auth'], function() {
    Route::get('/trend', 'IndexController@trend')->name('index.trend');
    Route::get('/account', 'IndexController@account')->name('index.account');
    Route::get('/news', 'IndexController@news')->name('index.news');
});

Route::get('/login/{provider}', 'Auth\LoginController@redirectToProvider'); //ソーシャルログイン
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback'); //ソーシャルログインのcallback





