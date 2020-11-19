<?php

namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\user ;

class item extends Model
{
    //
    protected $table = 't_items';
    protected $primaryKey = 'item_id';
    public $timestamps = false ;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_name', 'item_description', 'id_user','item_statut_id','categorie_id'
    ]; 


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
  /*   protected $hidden = [
        'created_at', 'updated_at','item_id'
    ];  */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * about relations 
     */
    public function user()
    {
        return $this->belongsTo('App\User'/* ,'id_user','user_id' */);
    }

    public function paniers()
    {
        return $this->belongsToMany('App\panier','t_items_paniers','panier_id', 'item_id');
    }
}
