<?php

namespace App\Http\Controllers;
use App\Tag;

use Illuminate\Http\Request;

class TagController extends Controller 
{
    public function show(string $name) {
        //firstメソッドでコレクションから最初のタグモデルを１件取り出し変数$tagに代入する
        $tag = Tag::where('name', $name)->first();
        //viewメソッドを使用して、tags/show.blade.phpを表示する。$tagをviewにわたす
        return view(
            'tags.show',
            ['tag' => $tag]);
    }
}


