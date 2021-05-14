<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {

            $table->json('contact_infos')->after('visibility')->nullable();

            $table->unsignedBigInteger('hosting_duration_id')->after('visibility')->nullable();
            $table->foreign('hosting_duration_id')->references('id')->on('hosting_durations')->constrained();

            $table->unsignedBigInteger('hosting_type_id')->after('visibility')->nullable();
            $table->foreign('hosting_type_id')->references('id')->on('hosting_types')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function (Blueprint $table) {

            $table->dropForeign(['hosting_duration_id']);
            $table->dropColumn('hosting_duration_id');

            $table->dropForeign(['hosting_type_id']);
            $table->dropColumn('hosting_type_id');

            $table->dropColumn('contact_infos');

        });
    }
}
