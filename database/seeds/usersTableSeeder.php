<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class usersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            ['name' => 'Benoît',
            'email'=>'benoit@yahoo.com',
            'user_role'=>1,
            'password'=> Hash::make('benoit'),
            ],
            ['name' => 'Carine',
            'email'=>'carine@yahoo.com',
            'user_role'=>NULL,
            'password'=> Hash::make('carine'),
            ],
            ['name' => 'zaidun',
            'email'=>'zaidun@yahoo.com',
            'user_role'=>NULL,
            'password'=> Hash::make('zaidun'),
            ],
            ['name' => 'Emilie',
            'email'=>'emilie@yahoo.com',
            'user_role'=>NULL,
            'password'=> Hash::make('emilie'),
          ],//from here new users
            ['name' => 'vitaly',
            'email'=>'vitaly@yahoo.com',
            'user_role'=>NULL,
            'password'=> Hash::make('vitaly'),
            ],
            ['name' => 'kenny',
            'email'=>'kenny@yahoo.com',
            'user_role'=>NULL,
            'password'=> Hash::make('kenny'),
            ],
            ['name' => 'quentin',
            'email'=>'quentin@yahoo.com',
            'user_role'=>NULL,
            'password'=> Hash::make('quentin'),
            ],
            ['name' => 'cedric',
            'email'=>'cedric@yahoo.com',
            'user_role'=>NULL,
            'password'=> Hash::make('cedric'),
            ]


        ]);
    }
}
