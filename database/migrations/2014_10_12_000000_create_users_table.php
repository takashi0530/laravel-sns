<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();   //uniqueメソッドを使うことで、ユニーク制約をつけることができる
            $table->string('email')->unique();  //uniqueメソッドを使うことで、ユニーク制約をつけることができる
            $table->timestamp('email_verified_at')->nullable(); //カラムにnullが入ることを許容する
            $table->string('password')->nullable(); //カラムにnullが入ることを許容する
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
