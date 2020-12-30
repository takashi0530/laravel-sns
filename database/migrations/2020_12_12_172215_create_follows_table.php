<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            //フォロワー・被フォローの紐付けを識別するID
            $table->bigIncrements('id');

            //フォロワーのユーザーid
            $table->bigInteger('follower_id');
            //followsテーブルのfollower_idはusersテーブルのidを参照する。onDeleteメソッドcascadeで、usersテーブルからレコードが削除されたときにそれに紐づくfollowsテーブルのレコードが同時に削除される
            $table->foreign('follower_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            //フォローされている側のユーザーID
            $table->bigInteger('followee_id');
            $table->foreign('followee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('follows');
    }
}
