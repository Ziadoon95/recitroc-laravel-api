<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\item ;
use App\User ;
use JWTFactory;
use JWTAuth;
use Validator;
use Response;


class itemApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

          //inner join table users and t_items to show the item property
          $all_available_items["data"] = DB::table("users")                  
          ->select('users.name as user',  't_items.item_name','t_items.item_description','t_items.item_id'
          ,'users.user_id','t_items.item_date_creation','t_items.categorie_id','t_categories.categorie_name')    
          ->join('t_items','users.user_id','=','t_items.id_user')
          ->join('t_items_statut','t_items_statut.item_statut_id','=','t_items.item_statut_id')
          ->leftJoin('t_categories','t_categories.categorie_id','=','t_items.categorie_id')
          ->whereIn('t_items_statut.item_statut_id',array(1,3))//1 availabe 3->demanded
          ->orderBy('t_items.item_date_creation','desc')
          ->get(); 

        return $all_available_items;
        //
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
         $all_available_items["data"] = DB::table("users")                  
        ->select('users.name as user',  't_items.item_name','t_items.item_description','t_items.item_id'
        ,'users.user_id','t_items.item_date_creation','users.user_adresse')    
        ->join('t_items','users.user_id','=','t_items.id_user')
        ->join('t_items_statut','t_items_statut.item_statut_id','=','t_items.item_statut_id')
        ->whereIn('t_items_statut.item_statut_id',array(1,3))//1 availabe 3->demanded
        ->where('t_items.item_id','=',$id)
        ->get(); 

      return $all_available_items;
/*         return response()->json([
            "status" => "success",
            "data" => item::find($id)->toArray(),
        ]); */
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
        //delete an item
//to modify this code with right params
    $delete_item = DB::table("t_items")                     
        ->where('t_items.item_id','=',$id)
        ->where('t_items.id_user','=',Auth::user()->user_id)->delete(); 
         if($delete_item)
         {
             return "deleted";
         }else{
             return "not Deleted";
         }
    }
    }  
