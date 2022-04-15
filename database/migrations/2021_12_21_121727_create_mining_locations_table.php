<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('item_type');
            $table->json('resources');
            $table->float('health')->default(0);
            $table->float('hunger')->default(0);
            $table->float('energy')->default(0);
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
        Schema::dropIfExists('mining_locations');
    }
}
