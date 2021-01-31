<?php

namespace App\Http\Controllers;

//Userモデルの使用を宣言
use App\User;
//Requestクラスの使用を宣言
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $name) {
        //Userモデルのwhereメソッドに引数を渡す。第2引数で渡したUserの名前と一致するものをuserモデルのnameカラムから、最初に合致する（->fistメソッド）レコードを取得する
        $user = User::where('name', $name)->first()
        // 今ユーザーモデルのリレーション先の記事(投稿した記事)の、さらにリレーション先の、
        // 記事を投稿したユーザー
        // 記事にいいねしたユーザー
        // 記事に付けられたタグ
        // をEagerロードして発行SQLをへらす
        ->load(['articles.user', 'articles.likes', 'articles.tags']);

        //userモデルでリレーションしたarticlesモデルの（ユーザーの投稿記事を降順にする）created_atを降順にソートして変数に代入
        $articles = $user->articles->sortByDesc('created_at');
        // dd($articles);

        // users/show のviewを表示する。変数$userをview側でも$userとして使えるように渡す(複数の変数を渡す場合は第2引数以降に記述)
        return view('users.show', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }


    //引数$nameには、URLusers/{name}/followの{name}の部分が渡ってくる。{name}はフォローされる側のユーザーの名前が入る
    public function follow(Request $request, string $name) {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            return abort('404', 'Cannot follow yourself.');
        }

        //Userモデルのfollowingsメソッドにアクセスしている
        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return ['name' => $name];
    }

    public function unfollow(Request $request, string $name) {
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id) {
            //abort関数:ユーザーからのリクエストが誤っている場合などに使われる関数。第二引数は省略可能
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);

        return ['name' => $name];
    }

    //ユーザー詳細ページ内でいいねタブを押したときの表示内容を取得する
    public function likes(string $name) {

        $user = User::where('name', $name)->first();

        $articles = $user->likes->sortByDesc('created_at');

        // users/likes.blade.php ビューに飛ばす。
        return view('users.likes', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    //特定のユーザーがフォローしている一覧ページを表示
    public function followings(string $name) {

        $user = User::where('name', $name)->first()
            //ユーザーモデルのリレーション先のフォロー中ユーザーの、さらにリレーション先の、
            // フォロワー
            // をEagerロードしている
            ->load('followings.followers');

        //ユーザーコレクション（ユーザーモデル）のfollowings()メソッドにアクセスし、フォロー中のユーザーモデルをコレクションで取得する
        $followings = $user->followings->sortByDesc('created_at');

        // resources/views/users/followings.blade.phpを表示
        return view('users.followings', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }

    public function followers(string $name) {

        $user = User::where('name', $name)->first()
            //ユーザーモデルのリレーション先のフォロワーの、さらにリレーション先の、
            //フォロワー
            //をEagerロードする
            ->load('followers.followers');

        $followers = $user->followers->sortByDesc('created_at');

        // resources/views/users/followers.blade.phpを表示
        return view('users.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }




}