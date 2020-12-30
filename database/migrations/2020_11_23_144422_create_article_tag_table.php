<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;  

class CreateArticleTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_tag', function (Blueprint $table) {

            //タグの紐付けをするID（整数）
            $table->bigIncrements('id');

            //タグがつけられた記事のID（整数）
            $table->bigInteger('article_id');
            $table->foreign('article_id')
                ->references('id')
                ->on('articles')
                //onDeleteメソッドでcascadeを指定すると、articlesテーブルやtagsテーブルからレコードが削除されたとき、それらに紐づくarticle_tagテーブルのレコードが同時に削除される
                ->onDelete('cascade');

            //記事に付けられたタグのID（整数）
            $table->bigInteger('tag_id');
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                //onDeleteメソッドでcascadeを指定すると、articlesテーブルやtagsテーブルからレコードが削除されたとき、それらに紐づくarticle_tagテーブルのレコードが同時に削除される
                ->onDelete('cascade');

            //created_at,updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_tag');
    }
}
