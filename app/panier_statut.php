<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class panier_statut extends Model
{
    //
    protected $table ="t_paniers_statut";
    protected $primaryKey  ="panier_statut_id";
    public $timestamps  =false;

    public function paniers()
    {
        return $this->hasMany('App\panier','panier_statut_id', 'panier_statut_id');
    }


}
