<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//タグモデルに記事モデルへのリレーションを追加する（タグモデルと記事モデルは多対多のためBelongsToMany）
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    //$fillableで指定したカラムのみ、create(),update()で値が代入する
    protected $fillable = [
        'name',
    ];

    public function getHashtagAttribute(): string {
        return '#' . $this->name;
    }

    public function articles(): BelongsToMany {
        return $this->belongsToMany('App\Article')->withTimestamps();
        
    }
}
