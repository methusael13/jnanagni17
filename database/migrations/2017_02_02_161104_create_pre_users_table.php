<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('pre_users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->bigInteger('phone')->unsigned();
            $table->string('college');
            $table->boolean('active');
            $table->string('email_hash', 32);
            $table->string('token', 10)->nullable();
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
        Schema::dropIfExists('pre_users');
    }
}
