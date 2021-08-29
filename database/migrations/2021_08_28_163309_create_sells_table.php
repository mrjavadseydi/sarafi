<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->string('cache_id')->nullable();
            $table->string('photo')->nullable();
            $table->double('amount')->nullable();
            $table->double('price')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('voocher')->nullable();
            $table->string('activator')->nullable();
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
        Schema::dropIfExists('sells');
    }
}
