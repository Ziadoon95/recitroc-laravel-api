<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTPaniersStatut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_paniers_statut', function (Blueprint $table) {
            $table->id('panier_statut_id');
            $table->string('panier_statut_nom');
            $table->string('panier_statut_desc');
        });

         // Insert some stuff
        DB::table('t_paniers_statut')->insert([
            ['panier_statut_nom' => 'actif', 'panier_statut_desc' => 'le panier est actif'],
            ['panier_statut_nom' => 'terminé', 'panier_statut_desc' => 'le panier est terminé']
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_paniers_statut');
    }
}
