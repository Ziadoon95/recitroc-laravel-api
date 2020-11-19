<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class itemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        DB::table('t_items')->insert([
            [
              'id_user' => 1,
              'item_name'=>"Banc pliable en platique",
              'item_description'=>"blah blah blah",
              'categorie_id'=>1,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 1,
              'item_name'=>"Table de mixage DJ usb",
              'item_description'=>"blah blah blah",
              'categorie_id'=>2,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 2,
              'item_name'=>"disque dur",
              'item_description'=>"disque dur en panne",
              'categorie_id'=>1,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 2,
              'item_name'=>"Boule à facette",
              'item_description'=>"blah blah blah",
              'categorie_id'=>2,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 3,
              'item_name'=>"Paire d'enceintes audio",
              'item_description'=>"blah blah blah",
              'categorie_id'=>2,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 3,
              'item_name'=>"Percolateur Samovar 15l",
              'item_description'=>"blah blah blah",
              'categorie_id'=>3,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 4,
              'item_name'=>"6 verres de vin",
              'item_description'=>"blah blah blah",
              'categorie_id'=>3,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 4,
              'item_name'=>"Lot de 10 assiettes plates",
              'item_description'=>"blah blah blah",
              'categorie_id'=>3,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 5,
              'item_name'=>"Tonelle 6*6m",
              'item_description'=>"blah blah blah",
              'categorie_id'=>4,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 5,
              'item_name'=>"Vidéo projecteur",
              'item_description'=>"blah blah blah",
              'categorie_id'=>5,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 6,
              'item_name'=>"Ponceuse",
              'item_description'=>"blah blah blah",
              'categorie_id'=>6,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 6,
              'item_name'=>"Château gonflable",
              'item_description'=>"blah blah blah",
              'categorie_id'=>7,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 7,
              'item_name'=>"Photographe",
              'item_description'=>"blah blah blah",
              'categorie_id'=>8,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 7,
              'item_name'=>"Fanfare",
              'item_description'=>"blah blah blah",
              'categorie_id'=>8,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 8,
              'item_name'=>"Webmaster",
              'item_description'=>"blah blah blah",
              'categorie_id'=>8,
              'item_statut_id'=>1
            ],
            [
              'id_user' => 8,
              'item_name'=>"Lindy hop",
              'item_description'=>"blah blah blah",
              'categorie_id'=>8,
              'item_statut_id'=>1
            ],

          ]);
    }
}
