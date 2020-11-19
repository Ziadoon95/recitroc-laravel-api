<?php

namespace App\Http\Controllers;
use App\Http\Controllers\ajouterPanierController ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\item ;
use App\User ;
use App\item_panier ;
use App\panier ;
use App\item_emprunt ;
use Response ;
/**
 * this contoller and ajouterpaniercontroller have to be integerated then in one controller
 */
class panierController extends AuthController
{



    protected $items_ids_inside_this_panier =[] ;
//show the panier of theis user
//to add : where the statut id is equal to 1  , i have to verify then then in postman
    public function show_this_user_panier_with_items()
    {
        /**
         * remarque27/3 only one user has a panier , on registeration a panier must be added on the registeration controller
         */
   //show this current panier use r_ item
        $items_panier = DB::table('users')
            ->select('users.user_id','t_items.item_id','t_items.item_name','t_items.item_description','t_items.item_date_creation',
            'from_user.user_id as from_user_id','from_user.name as from_user_name')
            ->join('t_paniers','t_paniers.user_id','=','users.user_id')
            ->join('t_items_paniers','t_items_paniers.panier_id','=','t_paniers.panier_id')
            ->join('t_items','t_items_paniers.item_id','=','t_items.item_id')
            ->join('users as from_user','from_user.user_id','=','t_items.id_user')
            ->where('users.user_id','=',$this->authUserId()/*Auth::user()->user_id*/)
            ->where('t_paniers.panier_satut_id','=',1);//actif panier

            if($items_panier->exists())
            {
                 return  array(
                                "statut" =>"success",
                                "nombre d'items" =>count($items_panier->get()),
                                "data" => $items_panier->get()->toArray()
                          );
            }else{
                return  array(
                            "statut" =>"failed",
                            "message" =>"there is no items in the panier",
                            "data" => ""
                           );
            }
    }

    /*to send the request , retrive all the panier items that user added and add them from t_panier_items
    *to another table t_items_emprunts , to send a request to the poster and notify the demander
    */
    /**
     * remarque 27/3: pour eviter de demander un item qui est deja emprunte :
     * 1- d'abbord il faut le cacher des recherche , et afficher tout les items , et les item d'un utilisateur
     * 2- verifier avant d'ajouter au panier qu'il n'est pas emprunté
     * 3-verifier avant d'envoyer la demande qu'il n'est pas emprunté
     */
    public function panier_send_demande()
    {
        //i can change this line by a property inside the class after
       $items_paniers = $this->show_this_user_panier_with_items();
       //trying item id  || user id by token , and panier id can be send by $panier_i
       //fillup the the array with the item ids
       //return $items_paniers ;
      //verify if the item is available
      if($items_paniers["statut"]=="failed")
      {
        return array(
            "statut"=>"failed",
            "message"=>"can't send the request , this panier is empty"
          );
      }
        foreach($items_paniers["data"] as $item_panier)
        {
            $this->items_ids_inside_this_panier[]["item_id"] =  $item_panier->item_id;
        }
        //return $this->items_ids_inside_this_panier;
        //insert the epmrunted_items && change the emprunt_statut_id to attente
        foreach($this->items_ids_inside_this_panier as $item_inside_panier)
        {
             $insert_emprunted_items = item_emprunt::create([
                'user_id' => $this->authUserId(),
                'item_id' => $item_inside_panier["item_id"],
                'panier_id' => $this->panier_id_of_User(),
                'emprunt_statut_id' => 1 , //attente _une reponse
             ]);
                //change the item_statut to attente_reponse
             if ($insert_emprunted_items->exists())
             {
                   //update the item statut to attente_une reponse
                   $resItem = $this->update_item_statut($item_inside_panier["item_id"],3);

                    //update the panier statut to 2
                    $res_update_panier= $this->update_panier_statut(2);

                    //create a new panier for this user
                    $create_panier = panier::create([
                        'user_id' =>$this->authUserId() ,//Auth::user()->user_id,
                        'panier_satut_id' => 1 , //attente _une reponse
                     ]);

                     return array(
                           "statut"=>"success",
                           "message"=>"your request has been sent !"
                         );

             }else{
               return array(
                   "statut"=>"failed",
                   "message"=>"le panier item has already been sent!"
                 );
             }
        }

    }
    /*
    *to update the status
    *
    */
    //update item statut
    public function update_item_statut($item_id ,$to_statut)
    {
      //update the item statut to attente_une reponse
       $item_data = item::find($item_id);
       $item_data->item_statut_id = $to_statut; //attente_reponse
       return $item_data->save();
    }
    //update panier statut 
    public function update_panier_statut($to_statut)
    {
        $update_panier = panier::find($this->panier_id_of_User());
        $update_panier->panier_satut_id = $to_statut;
        return  $update_panier->save();
    }
    /*
    *end update the status
    *
    */
    public function ajouter_au_panier(Request $request)
    {
        //
        $id_panier_of_user = $this->panier_id_of_User();

        $validator = Validator::make($request->all(),[
            'item_id' => 'required|numeric',
        ]);

        if($validator->fails())
        {
            return response()->json([
              "statut" =>"failed" ,
              "message" =>  $validator->errors()->toJson()
            ]);
        }else if($this->is_item_added_to_panier($request->get('item_id'),$id_panier_of_user))
        {
            return response()->json([
                "statut" =>"failed",
                "message" =>"this item is already added to the panier" ,
            ]);
        }else{
            $item = item_panier::create([
                'item_id' => $request->get('item_id'),
                'panier_id' => $id_panier_of_user,
            ]);
            return response()->json([
                "statut" =>"success",
                "message" => "added to the panier",
                "data" => $item,
            ]);
        }


    }
//
    //return the panier id of this user
    public function panier_id_of_User()
    {
        //get the panier of the current user
        $user_panier = User::find($this->authUserId())->paniers->where('panier_satut_id','=',1)->first();
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
