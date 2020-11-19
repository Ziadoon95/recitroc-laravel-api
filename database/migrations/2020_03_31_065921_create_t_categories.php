<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_categories', function (Blueprint $table) {
            $table->id('categorie_id');
            $table->timestamps();
            $table->string('categorie_name');
            $table->string('categorie_desc');
        });

        /*DB::table('t_categories')->insert([
            ["categorie_name"=>"informatique","categorie_desc"=>"none" ],
            ["categorie_name"=>"accessories","categorie_desc"=>"none" ],
            ["categorie_name"=>"santé","categorie_desc"=>"none" ],
            ["categorie_name"=>"animaux","categorie_desc"=>"none" ]
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_categories');
    }
}
