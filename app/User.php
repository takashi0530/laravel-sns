<?php

namespace App;

use App\Mail\BareMail;
use App\Notifications\PasswordResetNotification;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function follows(): BelongsToMany {
        //中間テーブルとのリレーションが発生するため、第3引数と第4引数を省略できない
        //リレーション元のusersテーブルのidは、中間テーブルのfollowee_idと紐付く
        //リレーション先のusersテーブルのidは、中間テーブルのfollower_idと紐付く
        return $this->belongsToMany('App\user', 'follows', 'followee_id', 'follower_id')->withTimestamps();
    }

    public function isFollowedBy(?User $user): bool{
        return $user
            ? (bool)$this->followers->where('id', $user->id)->count()
            : false;
    }
}
