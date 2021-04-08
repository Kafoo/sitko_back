<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMillisecondsTimestampsToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->timestamp("created_at", 6)->change();
            $table->timestamp("read_at", 6)->change();
            $table->timestamp("updated_at", 6)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->timestamp("created_at", 0)->change();
            $table->timestamp("read_at", 0)->change();
            $table->timestamp("updated_at", 0)->change();
        });
    }
}
