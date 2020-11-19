<?php

namespace App;



use Illuminate\Database\Eloquent\Model;

class panier extends Model
{
    //
    protected $table ="t_paniers";
    protected $primaryKey  ="panier_id";
    protected $fillable = [
        'user_id', 'panier_satut_id'
    ];
    public $timestamps  =false;

    //user has many panier
    public function user()
    {
        return $this->belongsTo('App\User'/* ,'id_user','user_id' */);
    }
    public function paniers_statut()
    {
        return $this->belongsTo('App\panier_statut');
    }

    public function items()
    {
        return $this->belongsToMany('App\item','t_items_paniers','panier_id','item_id' );
    }
}
