<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveConstraintFromAuthorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropForeign('places_author_id_foreign');
            $table->foreign('author_id')
            ->references('id')->on('users')
            ->onDelete('set null');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_author_id_foreign');
            $table->foreign('author_id')
            ->references('id')->on('users')
            ->onDelete('set null');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_author_id_foreign');
            $table->foreign('author_id')
            ->references('id')->on('users')
            ->onDelete('set null');
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
            $table->dropForeign('places_author_id_foreign');
            $table->foreign('author_id')
            ->references('id')->on('users')
            ->constrained();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_author_id_foreign');
            $table->foreign('author_id')
            ->references('id')->on('users')
            ->constrained();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_author_id_foreign');
            $table->foreign('author_id')
            ->references('id')->on('users')
            ->constrained();
        });
    }
}
