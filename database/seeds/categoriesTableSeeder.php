<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class categoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('t_categories')->insert([
            ["categorie_name"=>"Mobilier","categorie_desc"=>"none" ],
            ["categorie_name"=>"Son et lumière","categorie_desc"=>"none" ],
            ["categorie_name"=>"Cuisine et vaisselle","categorie_desc"=>"none" ],
            ["categorie_name"=>"Tente et infrastructure extérieure","categorie_desc"=>"none" ],
            ["categorie_name"=>"Multimédia","categorie_desc"=>"none" ],
            ["categorie_name"=>"Bricolage","categorie_desc"=>"none" ],
            ["categorie_name"=>"Jeux","categorie_desc"=>"none" ],
            ["categorie_name"=>"Talents","categorie_desc"=>"none" ]
        ]);
    }
}
