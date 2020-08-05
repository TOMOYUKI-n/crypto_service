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

/**
 * 認証不要
 */
// トップ
Route::get('/', 'IndexController@top')->name('top');
// 利用規約への遷移
Route::get('/term', 'IndexController@term')->name('term');
// ポリシーへの遷移
Route::get('/policy', 'IndexController@policy')->name('policy');
// home
Route::get('/home', 'HomeController@index')->name('home');
// ログイン失敗時
Route::get('/misslogin', 'IndexController@misslogin')->name('misslogin');
//ソーシャルログイン
Route::get('/login/{provider}', 'Auth\LoginController@redirectToProvider');
//ソーシャルログインのcallback
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');

/**
 * 認証必要
 */
Route::group(['middleware' => 'auth'], function() {
    // トレンド一覧用
    Route::get('/trend', 'IndexController@trend')->name('index.trend');
    // 画面表示用
    Route::get('/account', function(){ return view('index.account'); });
    // フォローチェック
    Route::post('/account/followcheck', 'IndexController@followCheck');
    // フォロー用
    Route::post('/account/follows', 'IndexController@follows');
    // ユーザー情報取得用
    Route::get('/auth/users', 'AuthController@getUsers');
    // 自動フォロー用
    Route::post('/account/autofollows', 'IndexController@autoFollows');
    // news一覧用
    Route::get('/news', 'IndexController@news')->name('index.news');
});
