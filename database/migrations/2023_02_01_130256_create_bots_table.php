<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('owner_id');
            $table->index('owner_id', 'bot_owner_idx');
            $table->foreign('owner_id', 'bot_owner_fk')->on('users')->references('id');

            $table->unsignedBigInteger('bot_id');
            // $table->index('bot_id', 'bot_telegram_users_idx');
            // $table->foreign('bot_id', 'bot_telegram_users_fk')->on('telegram_users')->references('bot_id');

            $table->string('token');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bots');
    }
};
