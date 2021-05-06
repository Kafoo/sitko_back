<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconToUserTypesAndHomeTypesAndVisibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visibilities', function (Blueprint $table) {
            $table->string('icon')->after('name');
        });

        Schema::table('home_types', function (Blueprint $table) {
            $table->string('icon')->after('name');
        });

        Schema::table('user_types', function (Blueprint $table) {
            $table->string('icon')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visibilities', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('home_types', function (Blueprint $table) {
            $table->dropColumn('icon');
        });

        Schema::table('user_types', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
}
