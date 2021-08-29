<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buys', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->string('cache_id')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('vocher')->nullable();
            $table->string('activator')->nullable();
            $table->string('shaba')->nullable();
            $table->string('card')->nullable();
            $table->string('card_holder')->nullable();
            $table->double('amount')->nullable();
            $table->boolean('paid')->default(false);
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
        Schema::dropIfExists('buys');
    }
}
