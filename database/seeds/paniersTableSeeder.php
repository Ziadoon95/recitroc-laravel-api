<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class paniersTableSeeder extends Seeder
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
            DB::table('t_paniers')->insert([
                ['user_id' => 1,
                'panier_satut_id'=>1,
                ],
                ['user_id' => 2,
                'panier_satut_id'=>1,
                ],
                ['user_id' => 3,
                'panier_satut_id'=>1,
                ],
                ['user_id' => 4,
                'panier_satut_id'=>1,
                ],
                ['user_id' => 5,
                'panier_satut_id'=>1,
                ],
                ['user_id' => 6,
                'panier_satut_id'=>1,
                ],
                ['user_id' => 7,
                'panier_satut_id'=>1,
                ],
                ['user_id' => 8,
                'panier_satut_id'=>1,
                ]
            ]);
    }
}
