<?php

namespace App\Http\Controllers;

use App\Article;
use App\Tag;
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ArticleController extends Controller {

    public function __construct() {
        $this->authorizeResource(Article::class, 'article');     //authorizeResource()メソッド ：第一引数にはモデルのクラス名を渡す。第2引数にはモデルのIDがセットされるパラメーターを渡す
    }

    public function index() {

        //Articleモデルの全データが最新の投稿日時順に並び替えられた上で$articles に代入
        $articles = Article::all()->sortByDesc('created_at');


        //ダミーデータ
        // $articles = [
        //     (object) [
        //         'id' => 1,
        //         'title' => 'タイトル１',
        //         'body' => '本文１',
        //         'created_at' => now(),
        //         'user' => (object) [
        //             'id' => 1,
        //             'name' => 'ユーザー名１'
        //         ],
        //     ],
        //     (object) [
        //         'id' => 2,
        //         'title' => 'タイトル2',
        //         'body' => '本文2',
        //         'created_at' => now(),
        //         'user' => (object) [
        //             'id' => 2,
        //             'name' => 'ユーザー名2'
        //         ],
        //     ],
        //     (object) [
        //         'id' => 3,
        //         'title' => 'タイトル3',
        //         'body' => '本文3',
        //         'created_at' => now(),
        //         'user' => (object) [
        //             'id' => 3,
        //             'name' => 'ユーザー名3',
        //         ],
        //     ],
        // ];

        return view(
            'articles.index',[          //  articles.index  ： resources/views/articlesの indexのビューファイルが表示される
                'articles' => $articles  //第二引数'article'というキーを定義し、ビュー側で連想配列形式で変数$articlesを使用することができる
            ]
        );

        // return view('articles.index', ['articles' => $articles]);
        // //ビューへ値を渡す他の方法 (withメソッドをつなげる)
        // return view('articles.index')->with(['articles' => $articles]);
        // //ビューへ値を渡す他の方法 (compact関数を使う)
        // return view('articles.index', compact('articles'));
    }


    public function create() {

       $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);

    }

    //記事投稿処理
    public function store(ArticleRequest $request, Article $article) { //引数の前の意味：$requestはArticleRequestクラスのインスタンスであるという宣言。（php7以降でできる引数の型宣言）
        // $article->title = $request->title;          //記事登録画面からPOSTされたデータを代入
        // $article->body = $request->body;            //記事登録画面からPOSTされたデータを代入

        $article->fill($request->all());                //Articleモデルの$fillable配列にtitleとbodyが入ってる。それを全て取得できる
        $article->user_id = $request->user()->id;   //ログイン済みのユーザーなら,user()クラスのインスタンスにアクセスでき、idを取得できる
        // dd($article);
        $article->save();   //save()メソッド：articlesテーブルにレコードが新規登録される。save()メソッドを実行すると、登録日時と更新日時が入る

        //$request->tags はコレクションのためeachメソッドを使用することができる。登録されるタグの数だけeachメソッドが繰り返し処理を行う
        $request->tags->each(function ($tagName) use ($article) {
            //firstOrCreateメソッド  既存のレコードが無いときだけレコードを挿入するlaravelのメソッド。カラム名と値を渡して有無を判定する。$tagにはタグモデルが代入される
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });
        return redirect()->route('articles.index');
    }

    //記事編集画面を表示
    public function edit(Article $article) {    //Article $articleと型を宣言している：DIが行われる。editアクションメソッド内の$articleにはArticleモデルのインスタンスが代入された状態になる

        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.edit', [
            'article' => $article,
            //view側に$tagNamesという変数で渡す
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);


        //ビューには'article'というキー名で、変数$articleの値(Articleモデルのインスタンス)を渡しています。
    }

    //記事を更新
    public function update(ArticleRequest $request, Article $article) {
        $article->fill($request->all())->save();        //titleとbody等全て取得してsave()する。fill()メソッドの戻り値はモデル自身のため、そのまま->savve()メソッドをつなげて使うことができる
        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });
        return redirect()->route('articles.index');
    }

    //記事を削除
    public function destroy(Article $article) {
        $article->delete();
        return redirect()->route('articles.index');
    }

    //記事詳細
    public function show(Article $article) {
        return view(
            'articles.show', [
                'article' => $article   //view側で$articleを使用することができる
                ]
        );
    }

    //いいねを押したときのメソッド
    public function like(Request $request, Article $article) {
        //$article->likes()で記事モデルからlikesテーブル経由で紐付いているユーザーモデルのコレクションが返る
        //記事とlikesは多対多の関係となるため、detach()メソッドとattach()メソッドがを使用できる
        $article->likes()->detach($request->user()->id);    //同一ユーザーがいいねを2回以上押すことを防止するため、いったんいいねを削除したあとに再度登録する。結果的に同一ユーザーではいいね数１となる。まだいいねしていないユーザーの場合detachは空振りとなる。
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    //いいねを解除したときのメソッド
    public function unlike(Request $request, Article $article) {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

}
