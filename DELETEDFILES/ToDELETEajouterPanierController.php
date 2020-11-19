<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\item ;
use App\User ;
use App\item_panier ;

use Validator;

class ajouterPanierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ajouter_au_panier(Request $request)
    {
        //       
        $id_panier_of_user = $this->panier_id_of_User();

        $validator = validator::make($request->all(),[
            'item_id' => 'required|numeric',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors()->toJson(),400);
        }else if($this->is_item_added_to_panier($request->get('item_id'),$id_panier_of_user))
        { 
            return response()->json([
                "statut" =>"repeated",
                "message" =>"this item is already added to the panier" ,
            ]);
        }else{
            $item = item_panier::create([
                'item_id' => $request->get('item_id'),
                'panier_id' => $id_panier_of_user,
            ]); 
            return response()->json([
                "statut" =>"success",
                "data" => $item,
            ]);
        }
        
           
    }
//تم التعديل يوم 29/3
    //return the panier id of this user
    public function panier_id_of_User()
    {
        //get the panier of the current user
        $user_panier = User::find(Auth::user()->user_id)->paniers->where('panier_satut_id','=',1)->first();
        //$user_panier->toArray();

        return $user_panier->panier_id;
    }

    //verify if the item is already exist in the panier
    public function is_item_added_to_panier($id_item ,  $id_panier )
    {
         $item_exist_in_panier =  DB::table('t_items_paniers')
        ->where('item_id',$id_item)
        ->where('panier_id',$id_panier  )->exists(); 
        return $item_exist_in_panier;
    }
}
