<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_items', function (Blueprint $table) {
            /**
             * images for every item is needed in images table 
             */
            $table->id("item_id");
           $table->timestamp('item_date_creation')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('item_name',255)->nullable();
            $table->string('item_description',200)->nullable();
            $table->integer('id_user')->nullable();
            $table->date('item_start_date')->nullable();
            $table->date('item_end_date')->nullable();
            //$table->integer('item_quantity')->nullable();
            //$table->string('item_alt')->nullable();
            //$table->string('item_lon')->nullable();
            $table->integer('item_statut_id')->nullable();
            $table->integer('categorie_id')->nullable(); 
        });
    }
    //$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_items');
    }
}
