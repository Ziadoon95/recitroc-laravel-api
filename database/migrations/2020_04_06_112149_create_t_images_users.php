<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTImagesUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_images_users', function (Blueprint $table) {
            $table->id("image_user_id");
            $table->integer("image_user_owner_id");
            $table->string("image_user_src");
        });
        DB::table('t_images_users')->insert([
            ['image_user_owner_id' => 1, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/benoit.jpg'],
            ['image_user_owner_id' => 2, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/carine.jpg'],
            ['image_user_owner_id' => 3, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/zaidun.jpg'],
            ['image_user_owner_id' => 4, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/emilie.jpg'],
            ['image_user_owner_id' => 5, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/vitaly.jpg'],
            ['image_user_owner_id' => 6, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/kenny.jpg'],
            ['image_user_owner_id' => 7, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/quentin.jpg'],
            ['image_user_owner_id' => 8, 'image_user_src' => 'https://recitrocapi.herokuapp.com/images/cedric.jpg'],
            
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_images_users');
    }
}
