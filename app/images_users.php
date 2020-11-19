<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class images_users extends Model
{
    //
    protected $primaryKey ="image_user_id";
    protected $table ="t_images_users";
    protected $timestamps  = false;
    protected $fillable = [
        'image_user_src', 'image_user_owner_id'
    ];
}
