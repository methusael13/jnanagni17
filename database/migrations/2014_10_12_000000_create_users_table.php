<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->string('college');
            $table->boolean('gender');
            $table->boolean('active');
            $table->string('email_hash', 60);
            $table->string('token', 20)->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique('email'); $table->unique('phone');
            $table->unique('email_hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
}
