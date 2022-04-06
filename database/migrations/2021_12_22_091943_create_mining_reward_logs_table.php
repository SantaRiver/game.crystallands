<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningRewardLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_reward_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mining_log_id')->constrained('mining_logs');
            $table->string('status')->default('claim');
            $table->float('reward', 12, 6)->default(0);
            $table->string('resource');
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
        Schema::dropIfExists('mining_reward_logs');
    }
}
