<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //マイグレーション実行時にupメソッドが実行される
    public function up()
    {
        //Schema::create()でテーブルを作成する。第一引数：テーブル名  第二引数（無名関数）：カラム定義
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title');
            $table->text('body');
            $table->bigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');  //＜カラム生成する＋外部キー制約もつける＞articlesテーブルのuser_idカラムは、usersテーブルのidカラムを参照すること。

            $table->timestamps();   //created_atとupdated_atの２つのカラムが生成される
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    //down()メソッド：ロールバックのときに実行される
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
