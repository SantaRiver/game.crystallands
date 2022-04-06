<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards');
            $table->boolean('active')->default(1);
            $table->string('type')->nullable();
            $table->integer('mining_time')->comment('sec')->default(3600);
            $table->float('max_strength', 9,6)->default(100)->nullable();
            $table->float('efficiency', 9,6)->nullable();
            $table->float('wear', 9,6)->nullable();
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
        Schema::dropIfExists('items');
    }
}
