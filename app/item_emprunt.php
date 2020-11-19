<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class item_emprunt extends Model
{
    //
    protected $table = 't_items_emprunts';
    protected $primaryKey = 'emprunt_id';
    public $timestamps = false;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'item_id', 'emprunt_statut_id','emprunt_date_debut','emprunt_date_fin' , 'panier_id'
    ]; 


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
/*     protected $hidden = [
        'emprunt_id'
    ];  */

    public function item_emprunt_statut()
    {
        return $this->belongsTo('App\item_emprunt_statut');
    }
}
