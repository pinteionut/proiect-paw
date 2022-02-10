<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCapturedNodesCountToGamesPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games_players', function (Blueprint $table) {
            //
            $table->integer('captured_nodes_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games_players', function (Blueprint $table) {
            //
            $table->dropColumn('captured_nodes_count');
        });
    }
}
