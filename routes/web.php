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

//Routeファサード
// Route::get('/', function () {
//     return view('welcome');
// });

// localhost/でアクセスするとArticleControllerのindexアクションに飛び、アクションの最後でreturn viewにて、articles.indexに飛ばしている

use App\Http\Controllers\UserController;

Auth::routes(); //この行を追加
Route::get('/', 'ArticleController@index')->name('articles.index');         //Routeファサードのメソッドに->name()メソッドを繋げるとそのルーティングに名前をつけられる
Route::resource('/articles', 'ArticleController')->except(['index', 'show'])->middleware('auth');       //②  //erxcept()メソッドを繋げると指定したルーティングを除外できる（③のindexを除外する）   // ④ ->middleware('auth) ：  authミドルウェアはリクエストをコントローラーで処理する前にユーザーがログイン済みであるかどうかをチェックし、ログインしていなければユーザーをログイン画面へリダイレクトする。すでにログイン済みであるならコントローラーの処理が行われる。
Route::resource('/articles', 'ArticleController')->only(['show']);

//groupメソッドを使うことで、それまで定義したprefix('articles)とname('articles.)がgroupメソッドにクロージャ（無名関数）として渡された各ルーティングにまとめて適用される
//本来だったら    Route::put('articles/{article}/like', 'ArticleController@like')->name('articles.like')->middleware('auth');   とするところ、/articlesを省略できる
Route::prefix('articles')->name('articles.')->group(function () {
    Route::put('/{article}/like', 'ArticleController@like')->name('like')->middleware('auth');
    Route::delete('/{article}/like', 'ArticleController@unlike')->name('unlike')->middleware('auth');
});
Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

Route::prefix('users')->name('users.')->group(function() {
    Route::get('{name}', 'UserController@show')->name('show');
});


/* ②で追加されるルーティング
+--------+-----------+-------------------------+------------------+------------------------------------------------------------------------+------------+
| Domain | Method    | URI                     | Name             | Action                                                                 | Middleware |
+--------+-----------+-------------------------+------------------+------------------------------------------------------------------------+------------+
|        | POST      | articles                | articles.store   | App\Http\Controllers\ArticleController@store                           | web        |       store 登録処理
|        | GET|HEAD  | articles                | articles.index   | App\Http\Controllers\ArticleController@index                           | web        |       ③index 一覧表示  ※記事一覧のURLが '/' と'/aticles'とかぶっているので '/'に統一させる
|        | GET|HEAD  | articles/create         | articles.create  | App\Http\Controllers\ArticleController@create                          | web        |       create 登録画面表示
|        | DELETE    | articles/{article}      | articles.destroy | App\Http\Controllers\ArticleController@destroy                         | web        |       destroy 削除処理
|        | PUT|PATCH | articles/{article}      | articles.update  | App\Http\Controllers\ArticleController@update                          | web        |       update 更新処理
|        | GET|HEAD  | articles/{article}      | articles.show    | App\Http\Controllers\ArticleController@show                            | web        |       show 個別表示
|        | GET|HEAD  | articles/{article}/edit | articles.edit    | App\Http\Controllers\ArticleController@edit                            | web        |       edit 更新画面表示



④のauthミドルウェアを追加したあとのルートリスト
+--------+-----------+-------------------------+------------------+------------------------------------------------------------------------+------------+
| Domain | Method    | URI                     | Name             | Action                                                                 | Middleware |
+--------+-----------+-------------------------+------------------+------------------------------------------------------------------------+------------+
|        | GET|HEAD  | /                       | articles.index   | App\Http\Controllers\ArticleController@index                           | web        |
|        | POST      | articles                | articles.store   | App\Http\Controllers\ArticleController@store                           | web,auth   |       authミドルウェアを④で設定した結果、ログイン済みでないとトップページへ戻されるようになった
|        | GET|HEAD  | articles/create         | articles.create  | App\Http\Controllers\ArticleController@create                          | web,auth   |       authミドルウェアを④で設定した結果、ログイン済みでないとトップページへ戻されるようになった
|        | DELETE    | articles/{article}      | articles.destroy | App\Http\Controllers\ArticleController@destroy                         | web,auth   |       authミドルウェアを④で設定した結果、ログイン済みでないとトップページへ戻されるようになった
|        | PUT|PATCH | articles/{article}      | articles.update  | App\Http\Controllers\ArticleController@update                          | web,auth   |       authミドルウェアを④で設定した結果、ログイン済みでないとトップページへ戻されるようになった
|        | GET|HEAD  | articles/{article}      | articles.show    | App\Http\Controllers\ArticleController@show                            | web,auth   |       authミドルウェアを④で設定した結果、ログイン済みでないとトップページへ戻されるようになった
|        | GET|HEAD  | articles/{article}/edit | articles.edit    | App\Http\Controllers\ArticleController@edit                            | web,auth   |       authミドルウェアを④で設定した結果、ログイン済みでないとトップページへ戻されるようになった
|        | POST      | login                   |                  | App\Http\Controllers\Auth\LoginController@login                        | web,guest  |
|        | GET|HEAD  | login                   | login            | App\Http\Controllers\Auth\LoginController@showLoginForm                | web,guest  |
|        | POST      | logout                  | logout           | App\Http\Controllers\Auth\LoginController@logout                       | web        |
|        | POST      | password/confirm        |                  | App\Http\Controllers\Auth\ConfirmPasswordController@confirm            | web,auth   |
|        | GET|HEAD  | password/confirm        | password.confirm | App\Http\Controllers\Auth\ConfirmPasswordController@showConfirmForm    | web,auth   |
|        | POST      | password/email          | password.email   | App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail  | web        |
|        | POST      | password/reset          | password.update  | App\Http\Controllers\Auth\ResetPasswordController@reset                | web        |
|        | GET|HEAD  | password/reset          | password.request | App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm | web        |
|        | GET|HEAD  | password/reset/{token}  | password.reset   | App\Http\Controllers\Auth\ResetPasswordController@showResetForm        | web        |
|        | POST      | register                |                  | App\Http\Controllers\Auth\RegisterController@register                  | web,guest  |
|        | GET|HEAD  | register                | register         | App\Http\Controllers\Auth\RegisterController@showRegistrationForm      | web,guest  |
+--------+-----------+-------------------------+------------------+------------------------------------------------------------------------+------------+

//  ④で未ログインの場合トップへ飛ばしたくない場合ここから修正できる  /Users/takashi/laravel-sns/laravel/app/Http/Middleware/Authenticate.php



*/


