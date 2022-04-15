<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('schema');
            $table->string('template_id')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_transferable')->default(true);
            $table->boolean('is_burnable')->default(true);
            $table->integer('issued_supply')->default(0);
            $table->integer('max_supply')->default(0);
            $table->json('immutable_data');
            $table->dateTime('created_at_time');
            $table->string('image');
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
        Schema::dropIfExists('cards');
    }
}
