<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class emprunt_history extends Model
{
    //
    protected $primaryKey ='emprunt_history_id';
    protected $table ='t_items_emprunts_history';
    public $timestamps = false ;

    protected $fillable = [
        'item_id', 'panier_id','user_id','emprunt_date_debut','emprunt_date_fin','emprunt_statut_id'
    ];
}
