<?php

namespace App\Http\Controllers;
use App\Http\Controllers\ajouterPanierController ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\item ;
use App\User ;
use App\item_panier ;
use App\panier ;
use App\item_emprunt ;
use Vaildator ;
use Response ;
use Illuminate\Http\Request;

class items_panierApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //show this actif panier
        $items_panier = DB::table('users')
        ->select('users.user_id','t_items.item_id','t_items.item_name','t_items.item_description','t_items.item_date_creation',
        'from_user.user_id as from_user_id','from_user.name as from_user_name')
        ->join('t_paniers','t_paniers.user_id','=','users.user_id')
        ->join('t_items_paniers','t_items_paniers.panier_id','=','t_paniers.panier_id')
        ->join('t_items','t_items_paniers.item_id','=','t_items.item_id')
        ->join('users as from_user','from_user.user_id','=','t_items.id_user')
        ->where('users.user_id','=',Auth::user()->user_id)
        ->where('t_paniers.panier_satut_id','=',1);//active panier

        if($items_panier->exists())
        {
            return    response()->json([
                "nombre d'items"=> count($items_panier->get()),
                "data" => $items_panier->get()->toArray()
             ]);
        }else{
            return    response()->json([
                "nombre d'items"=>0,
                "data" => "there is no item"
             ]);
        }

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
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //can modify this code and extends another class to get the panier id

        $panier_id = User::find(Auth::user()->user_id)->paniers->where('panier_satut_id','=',1)->first();
        //return $panier_id->panier_id ;
         $delete_item = DB::table("t_items_paniers")
        ->where('t_items_paniers.item_id','=',$id)
        ->where('t_items_paniers.panier_id','=',$panier_id->panier_id)
        ->delete();

        if($delete_item)
        {
            return response()->json([
                "statut" => "success" ,
                "message" => "item has been removed from panier"
            ]);
        }else{
            return response()->json([
                "statut" => "failed" ,
                "message" => "failed to remove this item"
            ]);
        }
    }
}
