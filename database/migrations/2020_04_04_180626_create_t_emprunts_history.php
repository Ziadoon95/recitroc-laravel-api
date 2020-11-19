<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTEmpruntsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_emprunts_history', function (Blueprint $table) {
            $table->id('emprunt_history_id');
            $table->integer('user_id');
            $table->integer('item_id');
            $table->integer('emprunt_statut_id');
            $table->date('emprunt_date_debut')->nullable();
            $table->date('emprunt_date_fin')->nullable();
            $table->integer('panier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_emprunts_history');
    }
}
