<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\item ;
use App\User ;
use App\item_emprunt ;
use App\item_emprunt_statut ;
use App\item_panier ;
use App\item_statut ;
use App\panier_statut ;
use App\panier ;

use JWTFactory;
use JWTAuth;
use Validator;
use Response;
//use App\Http\Controllers\AuthController as auther ;

class itemController extends AuthController
{
    /**
    * to create a new item to this current user
    */
     public function create_item(request $request)
     {
        $user = Auth::User()->user_id;
        $validator = validator::make($request->all(),[
            'item_name' => 'required|string',
            'item_description' => 'required|string',
            'item_categ' => 'nullable|numeric'
        ]);

        if($validator->fails())
        {
            return response()->json([
              "statut" =>"failed",
              "message" => $validator->errors()->toJson(),
              "data" => ""
              ]);
        }else{

            $item = item::create([
                'item_name' => $request->get('item_name'),
                'item_description' => $request->get('item_description'),
                'id_user' => $user,
                'item_statut_id' => 1,
                'categorie_id' => $request->get('item_categ'),
            ]);
            return response()->json([
                "statut" =>"success",
                "message"=>"item has been created succefuly"
            ]);
        }

     }

     /**
      * to show all items of all users
      */
      public function show_all_items()
      {
            //inner join table users and t_items to show the item property
            $all_available_items= DB::table("users")
                                ->select('users.name as user',  't_items.item_name','t_items.item_description','t_items.item_id'
                                ,'users.user_id','t_items.item_date_creation','t_items.item_id','t_items.categorie_id'
                                ,'t_categories.categorie_name')
                                ->join('t_items','users.user_id','=','t_items.id_user')
                                ->join('t_items_statut','t_items_statut.item_statut_id','=','t_items.item_statut_id')
                                ->leftJoin('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
                                ->whereIn('t_items_statut.item_statut_id',array(1,3))//1 availabe 3->demanded
                                ->orderBy('t_items.item_date_creation','desc')
                                ->get();

           $all_available_items = array(
               "nombre d'items" => count($all_available_items),
               "data" => $all_available_items
           );
           return $all_available_items;
      }

    public function show_my_items()
    {
        /*
        * my requested items
        */
        //emprunté par moi
        $requseted_fields["emprunte_par_moi"] =  $this->items_demande_sql(4)->get();
        //sent demandes by me and its refused
        //$requseted_fields["items_refuses"]   =  $this->items_demande_sql(3)->get() ;
        //items demandes by me and waiting for reponse
        $requseted_fields["items_en_attente"]   =  $this->items_demande_sql(1)->get();
        //accepted items by owner and waiting for my validation
        $requseted_fields["items_acceptes"]  =  $this->items_demande_sql(2)->get();
        /*
        *fill all my requested items principal array
        */
        $my_items["mes_demandes"] = array(
            "mes_emprunts" =>$requseted_fields["emprunte_par_moi"],
            "items_acceptes"=>$requseted_fields["items_acceptes"],
            "items_en_attente"=>$requseted_fields["items_en_attente"],
            //"items_refuses" =>$requseted_fields["items_refuses"]
        ) ;
        /*
        *
        * my items
        *
        */
        $own_fields["mes_offres"] =  $this->mes_offres_sql(1)->get();

        $own_fields["mes_items_attente_validation"] = $this->mes_offres_sql(2)->get();

        $own_fields["mes_items_attente_reponse"] =  $this->mes_offres_sql(3)->get();

        $own_fields["mes_items_emprunte"] = $this->mes_offres_sql(4)->get();
//history
        $own_fields["items_que_jai_emprunte"] = $this->history_sql(4)->get();

        $own_fields["mes_demandes_refuse"] = $this->history_sql(3)->get();

        $own_fields["items_que_jai_donne"] = $this->history_sql_deux()->get();

        $own_fields["items_que_jai_refuse"] = $this->history_sql_i_refused()->get();
        /*
        *fill all my items principal array
        */
        $my_items["mes_items"] =array(
            "mes_offres" => $own_fields["mes_offres"] ,
            "mes_items_attente_validation" => $own_fields["mes_items_attente_validation"]  ,
            "mes_items_attente_reponse" => $own_fields["mes_items_attente_reponse"]  ,
            "mes_ressources_en_prets" => $own_fields["mes_items_emprunte"]
        ) ;
        $my_items["historique"] =array(
            "mes_emprunts" => $own_fields["items_que_jai_emprunte"] ,
            "mes_ressources_pretees" => $own_fields["items_que_jai_donne"] ,
            "items_que_jai_refuses" => $own_fields["items_que_jai_refuse"] ,
            "mes_demandes_refusees" => $own_fields["mes_demandes_refuse"] ,
        ) ;

        /*
        * return of this function
        */
        return $my_items ;
    }
    //my requested items function sql
    public function items_demande_sql($item_statut_id)
    {
        return  DB::table("users")
        ->select('taken_from.name as demande_de','t_items.item_name','t_categories.categorie_name','t_items.item_description',
        't_items_emprunts_statut.emprunt_statut_nom as demande_statut' ,'t_items.item_id','t_items.categorie_id','t_items_emprunts.emprunt_id')
        ->join('t_items_emprunts','t_items_emprunts.user_id','=','users.user_id' )
        ->join('t_items_emprunts_statut','t_items_emprunts.emprunt_statut_id','=','t_items_emprunts_statut.emprunt_statut_id')
        ->join('t_items','t_items.item_id','=','t_items_emprunts.item_id')
        ->leftJoin('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
        ->join('users as taken_from','t_items.id_user','=','taken_from.user_id')
        ->where('t_items_emprunts.emprunt_statut_id','=',$item_statut_id) //4 stands for emprunted
        ->where('users.user_id','=',Auth::user()->user_id);
    }

    public function history_sql($emprunt_statut_id)
    {
      return  DB::table("t_emprunts_history")
      ->select('taken_from.name as demande_de','t_items.item_name','t_categories.categorie_name',
      't_items.item_id','t_items.categorie_id','t_emprunts_history.emprunt_date_debut','t_emprunts_history.emprunt_date_fin')
      ->join('t_items','t_items.item_id','=','t_emprunts_history.item_id')
      ->join('users','users.user_id','=','t_emprunts_history.user_id')
      ->join('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
      ->join('users as taken_from','t_items.id_user','=','taken_from.user_id')
      ->where('users.user_id','=',Auth::user()->user_id)
      ->where('t_emprunts_history.emprunt_statut_id','=',$emprunt_statut_id);

    }
    public function history_sql_deux()
    {
      return  DB::table("t_emprunts_history")
      ->select('users.name as demandeur','t_items.item_name','t_categories.categorie_name',
      't_items.item_id','t_items.categorie_id','t_emprunts_history.emprunt_date_debut','t_emprunts_history.emprunt_date_fin')
      ->join('t_items','t_items.item_id','=','t_emprunts_history.item_id')
      ->join('users','users.user_id','=','t_emprunts_history.user_id')
      ->join('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
      ->join('users as taken_from','t_items.id_user','=','taken_from.user_id')
      ->where('taken_from.user_id','=',Auth::user()->user_id)
      ->where('t_emprunts_history.emprunt_statut_id','=',4);

    }
    public function history_sql_i_refused()
    {
      return  DB::table("t_emprunts_history")
      ->select('users.name as demandeur','t_items.item_name','t_categories.categorie_name',
      't_items.item_id','t_items.categorie_id','t_emprunts_history.emprunt_date_debut','t_emprunts_history.emprunt_date_fin')
      ->join('t_items','t_items.item_id','=','t_emprunts_history.item_id')
      ->join('users','users.user_id','=','t_emprunts_history.user_id')
      ->join('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
      ->join('users as taken_from','t_items.id_user','=','taken_from.user_id')
      ->where('taken_from.user_id','=',Auth::user()->user_id)
      ->where('t_emprunts_history.emprunt_statut_id','=',3);
    }
    //mes_offres function sql
    public function mes_offres_sql($item_statut_id)
    {
        //to change this varaible then
        return DB::table("users")
        ->select( 'taker.name as demandeur_nom','t_items.item_name as item_nom','t_items.item_description','t_categories.categorie_name as categorie_nom','t_items_statut.item_statut_nom as item_statut',
          't_items.item_id','t_items.categorie_id','t_items_emprunts.emprunt_id','taker.user_id as demandeur_id')
        ->join('t_items','t_items.id_user','=','users.user_id')
        ->leftJoin('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
        ->leftJoin('t_items_emprunts','t_items.item_id','=','t_items_emprunts.item_id')
        ->leftJoin('t_items_statut','t_items_statut.item_statut_id','=','t_items.item_statut_id')
        ->leftJoin('users as taker','t_items_emprunts.user_id','=','taker.user_id')
        ->leftJoin('t_items_emprunts_statut','t_items_emprunts_statut.emprunt_statut_id','=','t_items_emprunts.emprunt_statut_id')
        ->where('users.user_id','=',Auth::user()->user_id)
        ->where('t_items.item_statut_id','=',$item_statut_id);
    }



    //to search an item
    public function search_items(request $request)
    {
        $user = Auth::User()->user_id;

        $validator = validator::make($request->all(),[
            'item_name' => 'string',
            'item_categ' => 'numeric',/*
            'item_start_date' => 'required',
            'item_end_date' => 'required', */
        ]);

        if($validator->fails())
        {
            return  array(
                    "statut" => "falied",
                    "message" =>response()->json($validator->errors()->toJson())
                );
        }else if($user && !$validator->fails())
        {
            $item_categ_search   = $request->get('item_categ');
            $item_name_search   = $request->get('item_name');
           // $request->get('item_categ')
            //search by only name
           if(is_null($item_categ_search))
           {
              $searchByName = item::whereIn('t_items.item_statut_id', array(1,3))
              ->where('item_name','like', $item_name_search  . '%')
              ->get() ;

              return array(
                  "nombre de resultats" => count($searchByName),
                  "data" =>$searchByName
              );
              //search by name and category
           }else if(isset($item_categ_search , $item_name_search))
           {
            $searchByCategorieAndName = item::whereIn('t_items.item_statut_id', array(1,3))
              ->where('item_name','like', $item_name_search  . '%')
                ->where('t_items.categorie_id','=',$item_categ_search)->get();

                return array(
                    "nombre de resultats" => count($searchByCategorieAndName),
                    "data" =>$searchByCategorieAndName
                );
           }//search by categorier
           else if(is_null($item_name_search) && isset($item_categ_search))
           {
               $searchByCategorie = item::whereIn('t_items.item_statut_id', array(1,3))
                ->where('t_items.categorie_id','=',$item_categ_search)->get();

                return array(
                    "nombre de resultats" => count($searchByCategorie),
                    "data" =>$searchByCategorie
                );
           }
        }
    }

    //for debug only to be deleted after
    public function tables()
    {
       $all_available_items["items"] = item::all();
       $all_available_items["item_emprunt"] = item_emprunt::all();
       $all_available_items["item_emprunt_statut"] = item_emprunt_statut::all();
       $all_available_items["item_panier"] = item_panier::all();
       $all_available_items["item_statut"] = item_statut::all();
       $all_available_items["panier_statut"] = panier_statut::all();
       $all_available_items["panier"] = panier::all();

        return $all_available_items;
    }

/******************************************************************************************************
 * item resources
 * ****************************************************************************************************
 */
    public function index()
    {
          //inner join table users and t_items to show the item property
          $all_available_items = DB::table("users")
          ->select('users.name as user',  't_items.item_name','t_items.item_description','t_items.item_id'
          ,'users.user_id','t_items.item_date_creation','t_items.categorie_id','t_categories.categorie_name')
          ->join('t_items','users.user_id','=','t_items.id_user')
          ->join('t_items_statut','t_items_statut.item_statut_id','=','t_items.item_statut_id')
          ->leftJoin('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
          ->whereIn('t_items_statut.item_statut_id',array(1,3))//1 availabe 3->demanded
          ->orderBy('t_items.item_date_creation','asc')
          ->get();

        return array(
            "nombre de resultat" => count($all_available_items),
            "data"=>$all_available_items
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $all_available_items= DB::table("users")
        ->select('users.name as user',  't_items.item_name','t_items.item_description','t_items.item_id'
        ,'users.user_id','t_items.item_date_creation','users.user_adresse')
        ->join('t_items','users.user_id','=','t_items.id_user')
        ->join('t_items_statut','t_items_statut.item_statut_id','=','t_items.item_statut_id')
        ->whereIn('t_items_statut.item_statut_id',array(1,3))//1 availabe 3->demanded
        ->where('t_items.item_id','=',$id);

        if($all_available_items->exists())
        {
            return array(
                "statut"=>"success",
                "message" =>"",
                "data" =>$all_available_items->get()
            );
            ;
        }else{
            return array(
                "statut"=>"failed",
                "message" =>"item not found",
                "data" =>"No data"
            );
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, item $item)
    {
        //
         $update_item = DB::table("t_items")
        ->where('t_items.item_id','=',$item->item_id)
        ->where('t_items.id_user','=',Auth::user()->user_id)
        ->exists();

       if($update_item)
        {
            $validate = validator::make($request->all(),[
                "item_name" =>"required|string",
                "item_description" =>"nullable|String",
            ]);
            if($validate->fails())
            {
                return Response()->json([
                    "error" => $validate->errors(),

                ]);
            }
            $item->item_name = $request->item_name ;
            $item->item_description = $request->item_description ;
            $item->save();
              return Response()->json([
                  "statut" => "success",
                  "message" => $item->item_name." has been succesfuly updated !",
              ]);
        }else{
            return Response()->json([
                "statut" => "failed",
                "message"=>"this item is for another person"
            ]);
        }

        return $item->item_id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete an item
//to modify this code with right params
    $delete_item = DB::table("t_items")
        ->where('t_items.item_id','=',$id)
        ->where('t_items.id_user','=',Auth::user()->user_id)->delete();
         if($delete_item)
         {
             return response()->json([
                "statut" =>"success",
                "message" =>"deleted succefuly !",
             ]);

         }else{
             return response()->json([
                 "statut" =>"failed",
                 "message" =>"item not deleted ..",
             ]);
         }
    }
}
