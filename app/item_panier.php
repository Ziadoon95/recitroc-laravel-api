<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_panier extends Model
{
    //
    protected $primaryKey ="item_panier_id";
    protected $table ="t_items_paniers";
    public $timestamps = false;
    protected $fillable = [
        'item_id', 'panier_id'
    ]; 

    public function items()
    {
        return $this->belongsTo('App\item'/* ,'id_user','user_id' */);
    }
    public function paniers()
    {
        return $this->belongsTo('App\panier'/* ,'id_user','user_id' */);
    }
}
