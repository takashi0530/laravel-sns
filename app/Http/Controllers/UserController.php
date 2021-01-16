<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(string $name) {
        $user = User::where('name', $name)->first();
        return view('users.show', [
            'user' => $user,
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
}