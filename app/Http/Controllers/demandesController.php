<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\item ;
use App\User ;
use App\item_emprunt ;
use App\item_panier ;
use Validator;
Use \Carbon\Carbon;

class demandesController extends panierController
{
    //
//for the owner : show all recieved demandes
    public function show_all_reieved_demandes()
    {

       $demandes = DB::table('t_items_emprunts')
       ->select('property.user_id' , 't_items.item_id' , 't_items_emprunts_statut.emprunt_statut_nom' , 't_items.item_name' ,
        't_items_emprunts.emprunt_id','t_items.categorie_id','t_categories.categorie_name' , 'users.name as demandeur' , 'property.name as demande_de','t_items_emprunts.emprunt_statut_id')
       ->join('users' ,'users.user_id','=','t_items_emprunts.user_id')
       ->join('t_items_emprunts_statut' ,'t_items_emprunts.emprunt_statut_id','=','t_items_emprunts_statut.emprunt_statut_id')
       ->join('t_items' ,'t_items.item_id','=','t_items_emprunts.item_id')
       ->leftJoin('t_categories','t_categories.categorie_id','=','t_items.categorie_id')

       ->join('users as property' ,'t_items.id_user','=','property.user_id')
       ->where('property.user_id', '=', $this->authUserId())
       ->where('t_items_emprunts.emprunt_statut_id','=',1) ;//1 waiting response


        if($demandes->exists())
        {
            return
            response()->json([
                'statut' => 'success',
                "message"=>'',
                'data' => $demandes->get()
                ]);
        }else{
            return response()->json([
                'statut' => 'failed',
                "message"=>'non demandes to this user',
                'data' => ''
                ]);
        }
    }
//the owner : to answer the demande
//check recieve an array from the user
    public function answer_demande(Request $request)
    {
        $user_id =$this->authUserId() ;
        //verifier si l'item appartient à lui meme
        //if the user is authinticated && these are his items && recieve a demande
        $Validator = Validator::make($request->all(),[
            "answer" => "required|numeric" ,
            "item_id" => "required|numeric" ,
        ]);

        if(! $Validator->fails())
        {   //if the user accept to give his item
            //this code is applicable only for one item not many -> it must be updated

            //new code
            /**
             *             //verify if the items are his/her items
             * thinking of making a fucntion of the variable demande
             * resort the if and else or using another conditions methode
             */
            $demandes = DB::table('t_items_emprunts')
            ->select('property.user_id' , 't_items.item_id' , 't_items_emprunts_statut.emprunt_statut_nom' , 't_items.item_name' , 't_items_emprunts.emprunt_id' , 'users.name as demandeur' , 'property.name as demande_de')
            ->join('users' ,'users.user_id','=','t_items_emprunts.user_id')
            ->join('t_items_emprunts_statut' ,'t_items_emprunts.emprunt_statut_id','=','t_items_emprunts_statut.emprunt_statut_id')
            ->join('t_items' ,'t_items.item_id','=','t_items_emprunts.item_id')
            ->join('users as property' ,'t_items.id_user','=','property.user_id')
            ->where('property.user_id', '=', $this->authUserId())
            ->where('t_items_emprunts.item_id', '=', $request->item_id);



            if($demandes->exists())
            {
               $emprunt_id = $demandes->get()[0]->emprunt_id;
               //get he all emprunt data by the emprunt id taken from front end
               $item_id = $request->item_id ;
               $emprunt_data = item_emprunt::find($emprunt_id);

                if($request->answer)
                {
                    /**
                     * update the statut d’emprunt 't_items_emprunts.emprunt_statut_id' à 2  de l’id_emprunté(qui estrécupérédu front-end)
                    */
                    $emprunt_data->emprunt_statut_id = 2; //2 attente_validation
                    $resEmprunt = $emprunt_data->save();
                    /**
                     * -update le statut d’item dans la table “t_itemsà”= “emprunté” -> pour qu’ilsoitinvisible dans la recherche public
                     */
                    $item_data = item::find($item_id);
                    $item_data->item_statut_id = 2; //2 attente_validation
                    $resItem = $item_data->save();

                    //verify if the records is updated
                    if($resEmprunt && $resItem)
                    {
                        return
                        response()->json([
                            "statut" => "success",
                            "message" => "the items have been accepted ! ... waiting for the user validation",
                        ]);
                    }
                    else{
                        return    response()->json([
                                "statut" => "falied",
                                "message" => "failed to anwser the user",
                        ]);
                    }

                }else{
                  //if the owner refuse the request delete from emrunts and add to history
                  //in next versions add the refuse time
                    //
                    $emprunt_data->emprunt_statut_id = 3; //3 : refused
                    //$resEmprunt = $emprunt_data->save();
                    //add to history
                    DB::table('t_emprunts_history')->insert([
                        [
                          'item_id' =>$emprunt_data->item_id ,
                         'panier_id' => $emprunt_data->panier_id,
                         'user_id' => $emprunt_data->user_id,
                         'emprunt_statut_id' => $emprunt_data->emprunt_statut_id,
                         'emprunt_date_debut' =>$emprunt_data->emprunt_date_debut ,
                         'emprunt_date_fin' => $emprunt_data->emprunt_date_fin,
                       ]
                    ]);
                    //request delete from emrunts
                    $emprunt_data->delete();
                    //do a function to update the statuts -> then
                    $item_data = item::find($request->item_id);
                    $item_data->item_statut_id = 1; //2 attente_validation
                    $resItem = $item_data->save();
                    //maybe removing the if after ..
                    if(/*$resEmprunt && */$resItem)
                    {
                        return response()->json([
                            "statut" => "success",
                            "message" => "the items have been refused ! ...",
                        ]);
                    }
                }
            }else{
                return   response()->json([
                    "statut" => "failed",
                    "message" => "the item is not belong to the user",
                    "data" => ""
                ]);

            }

        }else{
            return   response()->json([
                "statut" => "failed",
                "message" => $Validator->errors()->toJson(),
                "data" => ""
            ]);
        }
    }

    /**
     * if user confirm the validation
     */
//change this function name
//for the normal user : confrim the validation
    public function user_recieve_item_and_validate(Request $request)
    {
        //here is the code to recieve  the demande and validate
        $Validator = Validator::make($request->all(),[
            "emprunt_id" => "required|numeric" ,
        ]);

        if(! $Validator->fails())
        {


            $validation_verify_recieved = DB::table('t_items_emprunts')
            ->select('t_items_emprunts.emprunt_id' , 'users.name as demandeur','t_items.item_id' , 'property.name as demande_de' , 't_items.item_name' ,
            't_items_emprunts.emprunt_id' ,'t_items_emprunts_statut.emprunt_statut_nom')
            ->join('users' ,'users.user_id','=','t_items_emprunts.user_id')
            ->join('t_items_emprunts_statut' ,'t_items_emprunts.emprunt_statut_id','=','t_items_emprunts_statut.emprunt_statut_id')
            ->join('t_items' ,'t_items.item_id','=','t_items_emprunts.item_id')
            ->join('users as property' ,'t_items.id_user','=','property.user_id')
            ->where('users.user_id', '=', $this->authUserId())
            ->where('t_items_emprunts.emprunt_statut_id','=' ,2)
            ->where('t_items_emprunts.emprunt_id','=',$request->emprunt_id);

            if($validation_verify_recieved->exists())
            {
              //retrive item_id of sql request
              //and update the item statut
                $item_id = $validation_verify_recieved->get()[0]->item_id;
                $this->update_item_statut($item_id,4);

              //update the statut of emprunt_statut to emprunted wich is 4
                $item_emprunt = item_emprunt::find($request->emprunt_id);
                $item_emprunt->emprunt_statut_id = 4; //emprunté
                $item_emprunt->emprunt_date_debut = now(); //emprunté

                /*
                *insert t_items_emprunts emprunt_date_debut = timestamp()
                *code
                *code
                *code
                */

                if($item_emprunt->save())
                {
                    return response()->json([
                        "statut"=>"success",
                        "message" => "the item has been validated !"
                    ]);
                }else{
                    return response()->json([
                        "statut"=>"success",
                        "message" => "failed to change the emprunt statut"
                    ]);
                }
            }else{
                return response()->json([
                    "statut"=>"failed",
                    "message" => "seems that emprunt id belong to another object"
                ]);
            }
        }else{
            return response()->json([
                "statut"=>"failed",
                "message" => $Validator->errors()->toJson()
            ]);
        }

    }

    public function rendre_objet(request $request)
    {
        $Validator = Validator::make($request->all(),[
            "emprunt_id" => "required|numeric" ,
        ]);

        if(! $Validator->fails())
        {
            //d'abord verifier si l'item est en statut emprunté + appartient à cet utilsateur si oui alors
            //make a function of this code
            $validation_emprunte = DB::table('t_items_emprunts')
            ->select('t_items_emprunts.emprunt_id' , 'users.name as demandeur','t_items.item_id' , 'property.name as demande_de' , 't_items.item_name' ,
            't_items_emprunts.emprunt_id' ,'t_items_emprunts_statut.emprunt_statut_nom')
            ->join('users' ,'users.user_id','=','t_items_emprunts.user_id')
            ->join('t_items_emprunts_statut' ,'t_items_emprunts.emprunt_statut_id','=','t_items_emprunts_statut.emprunt_statut_id')
            ->join('t_items' ,'t_items.item_id','=','t_items_emprunts.item_id')
            ->join('users as property' ,'t_items.id_user','=','property.user_id')
            ->where('users.user_id', '=', $this->authUserId())
            ->where('t_items_emprunts.emprunt_statut_id','=' ,4)
            ->where('t_items_emprunts.emprunt_id','=',$request->emprunt_id);

            if($validation_emprunte->exists())
            {
              //change the emrpunt id to 5
              //retrive item_id of sql request
                $item_id = $validation_emprunte->get()[0]->item_id;
                $this->update_item_statut($item_id,1);

              //update the statut of emprunt_statut to emprunted wich is 4
                $item_emprunt = item_emprunt::find($request->emprunt_id);
                //$item_emprunt->emprunt_statut_id = 5; //emprunté_terminé
                $item_emprunt->emprunt_date_fin = now(); //emprunté
                //$item_emprunt->save()
                //fill this history inside emprunts history
                DB::table('t_emprunts_history')->insert([
                    ['item_id' =>$item_emprunt->item_id ,
                     'panier_id' => $item_emprunt->panier_id,
                     'user_id' => $item_emprunt->user_id,
                     'emprunt_statut_id' => $item_emprunt->emprunt_statut_id,
                     'emprunt_date_debut' =>$item_emprunt->emprunt_date_debut ,
                     'emprunt_date_fin' => $item_emprunt->emprunt_date_fin,
                   ]
                ]);



                //then delete
              //insert t_items_emprunts emprunt_date_fin = timestamp()
              if( $item_emprunt->delete() )
              {
                    return response()->json([
                        "statut"=>"success",
                        "message" =>"object is returned succefuly"
                    ]);
              }

            }
            else
            {
              return response()->json([
                  "statut"=>"failed",
                  "message" =>"this object is not loaned"
              ]);
            }
        }else{
          return response()->json([
              "statut"=>"failed",
              "message" => $Validator->errors()->toJson()
          ]);
        }

    }

}
