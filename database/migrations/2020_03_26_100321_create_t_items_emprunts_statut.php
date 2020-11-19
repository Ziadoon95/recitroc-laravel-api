<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTItemsEmpruntsStatut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_items_emprunts_statut', function (Blueprint $table) {
            $table->id('emprunt_statut_id');
            $table->string('emprunt_statut_nom');
            $table->string('emprunt_statut_couleur')->nullable();
            //this champ is optionel is only to make it clear what is this statut for
            $table->string('emprunt_statut_desc')->nullable();
        });

        DB::table('t_items_emprunts_statut')->insert([
            ['emprunt_statut_nom' => 'attente_reponse'],
            ['emprunt_statut_nom' => 'attente_validation'],
            ['emprunt_statut_nom' => 'refusé'],
            ['emprunt_statut_nom' => 'emprunté'],


        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_items_emprunts_statut');
    }
}
