<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_emprunt_statut extends Model
{
    //
    
    protected $primaryKey ='emprunt_statut_id';
    protected $table ='t_items_emprunts_statut';
    public $timestamps = false ;

    public function item_emprunt()
    {
        return $this->hasMany('App\item_emprunt','emprunt_statut_id', 'emprunt_statut_id');
    }

}
