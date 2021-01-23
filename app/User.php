<?php

namespace App;

use App\Mail\BareMail;
use App\Notifications\PasswordResetNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;

//belongsToManyメソッドを使用する（多対多）
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

//HasManyメソッドを使用する（ユーザーとそのユーザーが投稿した記事の関係性。1対多）
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token) {
        $this->notify(new PasswordResetNotification($token, new BareMail()));       //PasswordResetNotificationクラスのインスタンスを生成してnotify()メソッドに値を渡している
    }

    //ユーザーモデルと記事モデルをリレーションさせる（1体多の関係）
    public function articles(): HasMany {
        return $this->hasMany('App\Article');
    }

    public function followers(): BelongsToMany {
        //中間テーブルとのリレーションが発生するため、第3引数と第4引数を省略できない
        //リレーション元のusersテーブルのidは、中間テーブルのfollowee_idと紐付く
        //リレーション先のusersテーブルのidは、中間テーブルのfollower_idと紐付く
        return $this->belongsToMany('App\User', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    //これからフォローするユーザー、もしくはフォロー中のユーザーのモデルにアクセス可能するためのリレーションメソッド
    public function followings(): BelongsToMany {
        // リレーション元のusersテーブルのidは、中間テーブルのfollower_idと紐付く
        //リレーション先のusersテーブルのidは、中間テーブルのfollowee_idと紐付く
        return $this->belongsToMany('App\User', 'follows', 'follower_id', 'followee_id')->withTimestamps();
    }

    //
    public function likes(): BelongsToMany {
        return $this->belongsToMany('App\Article', 'likes')->withTimestamps();
    }

    public function isFollowedBy(?User $user): bool{
        return $user
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }


    public function getCountFollowersAttribute(): int {
        return $this->followers->count();
    }

    //userのshow.bladeでcount_followingsとして扱われる
    // ＜アクセサ（メソッド）をview側で使う方法＞
    //・getとAttributeの部分は除く
    //・残った部分をスネークケースにする(全て小文字で、単語と単語の間は_で繋ぐ書き方)
    //・メソッドの呼び出し時に通常必要な()は記述しない
    public function getCountFollowingsAttribute(): int {
        return $this->followings->count();
    }


}
