<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->json('contact_infos')->after('password')->nullable();

            $table->text('expectations')->after('password')->nullable();

            $table->text('bio')->after('password')->nullable();

            $table->unsignedBigInteger('home_type_id')->after('password')->nullable();
            $table->foreign('home_type_id')->references('id')->on('home_types')->constrained();

            $table->unsignedBigInteger('user_type_id')->after('password')->nullable();
            $table->foreign('user_type_id')->references('id')->on('user_types')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['user_type_id']);
            $table->dropColumn('user_type_id');

            $table->dropForeign(['home_type_id']);
            $table->dropColumn('home_type_id');

            $table->dropColumn('bio');

            $table->dropColumn('expectations');

            $table->dropColumn('contact_infos');

        });
    }
}
