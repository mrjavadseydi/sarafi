<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->string('state')->nullable();
            $table->string('phone')->nullable();
            $table->string('national_id')->nullable();
            $table->string('national_card')->nullable();
            $table->string('video')->nullable();
            $table->string('shaba')->nullable();
            $table->string('card')->nullable();
            $table->string('bank_card')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->integer('custom_price')->nullable();
            $table->boolean('blocked')->default(false);
            $table->boolean('admin')->default(false);
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
        Schema::dropIfExists('members');
    }
}
