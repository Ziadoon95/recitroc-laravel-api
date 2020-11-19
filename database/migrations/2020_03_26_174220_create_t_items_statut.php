<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTItemsStatut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_items_statut', function (Blueprint $table) {
            $table->id('item_statut_id');
            $table->string('item_statut_nom');
            $table->string('item_statut_desc');
        });  
        // Insert some stuff
        DB::table('t_items_statut')->insert([
            ['item_statut_nom' => 'disponible', 'item_statut_desc' => 'l item est disponible'],
            ['item_statut_nom' => 'attente_validation', 'item_statut_desc' => 'l item est accepté'],
            ['item_statut_nom' => 'attente_reponse', 'item_statut_desc' => 'l item est n est pas encore accepté'],
            ['item_statut_nom' => 'emprunté', 'item_statut_desc' => 'l item est emprunté'],
           
        ]);
    }
  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_items_statut');
    }
}
