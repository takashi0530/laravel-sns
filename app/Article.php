<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;   //articleモデルにUserモデルへのリレーションを追加する
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model {

    protected $fillable = [
        'title',
        'body'
    ];

    public function user():BelongsTo {                  //articleモデルにUserモデルへのリレーションを追加する    :BelongsTo メソッドの戻り値の「型」を宣言している（PHP7）userメソッドの戻り値がBelongsToクラスであることを宣言 別の型をreturnするときエラーとなり安全性と可読性が高まる。このメソッドが最終的にどの型を返すのか？他のエンジニアにわかりやすくなる
        //articles.user_id と users.id  同士のリレーションが成り立つ
        return $this->belongsTo('App\User');
    }

    public function likes():BelongsToMany {             //articleモデルにLikesモデルへのリレーションを追加する。いいねの記事モデルとユーザーモデルの関係は多対多：belongsToMany
        //belongsToManyメソッドの第一引数には関係するモデルのモデル名を書く。第二引数には中間テーブルのテーブル名を書く。
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    //ユーザーモデルを渡すとそのユーザーがこの記事をいいね済みかどうかを返すメソッド
    //引数である$userの型がUserモデルであることを宣言。その手前の？は引数$userがnullであることを許容している(nullableな型宣言を使用)
    public function isLikedBy(?User $user):bool {
        //$userに値がなければfalseを返す三項演算子
        return $user
            //where()メソッド：第一引数にキー名を指定し、第二引数に値を渡すと条件に一致するコレクション（配列を拡張したもの）を返す。
            //count()メソッド：コレクションの要素数を数えて数値を返す。
            //(bool) : 型キャストのこと。変数の前にカッコで記述しカッコ内に指定した型に変換する。(bool)ならtrueもしくはfalseに変換する
            ?(bool)$this->likes->where('id', $user->id)->count()
            :false;
    }

    public function getCountLikesAttribute(): int {
        return $this->likes->count();
    }

    //belongsToManyメソッドの第二引数には中間テーブルのテーブル名を渡します。
    //ただし、今回は中間テーブルの名前がarticle_tagといった2つのモデル名の単数形をアルファベット順に結合した名前ですので、第二引数は省略可能となっています
    public function tags():BelongsToMany {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

}
