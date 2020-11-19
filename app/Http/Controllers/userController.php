<?php

namespace App\Http\Controllers;
use App\User;
use App\item;
use App\images_users;
use Response;
use  Validator ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class userController extends AuthController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     /*the test to upload it online is ok*/
     public function show_user_photo()
     {
       $user_image =  DB::table("t_images_users")
       ->select('users.user_id','users.name','t_images_users.image_user_src')
       ->join('users','users.user_id','=','t_images_users.image_user_owner_id')
       ->where('users.user_id','=',$this->authUserId());

       //return Storage::url($user_image->get()[0]->image_user_src);
       if($user_image->exists())
       {
         return     array(
                "statut" =>"success",
                "data"=>$user_image->get()
              );
       }else{
         return array(
           "statut" =>"failed",
           "message"=>"this user doesnt has photos"
         );
       }
       //return response()->download($full_image_path);
       //return response()->download(public_path($path_prefix.'91760364_4221483921202499_4781597767925497856_n.jpg'),'wordpress image');

     }

     public function telecharger_photo()
     {
       $user_image =  DB::table("t_images_users")
       ->select('users.user_id','users.name','t_images_users.image_user_src')
       ->join('users','users.user_id','=','t_images_users.image_user_owner_id')
       ->where('users.user_id','=',$this->authUserId());
      // return $full_image_path =$user_image->get();
      if($user_image->exists())
      {       $full_image_path =$user_image->get()[0]->image_user_src;

       return
           response()->download($full_image_path);
      }else
      {
        return array(
          "statut" =>"failed",
          "message"=>"this user doesnt has items"
        );
      }
     }
     public function upload_photo(request $request)
     {
       //$path_prefix = "storage/";
       //another code
       if($request->hasFile('photo'))
       {
         $imageName = $request->photo->getClientOriginalName();
        // return $imageName ;
         //$imageSave = $request->file('photo')->storeAs("public",$imageName);
        // $imageSave = $request->file('photo')->move(public_path("storage/"),$imageName);
         $imageSave = $request->file('photo')->move(public_path("images/"),$imageName);
        //$path = Storage::putFile('/public', $request->file('photo','public'));
        //$path = Storage::putFile('public/', $request->file('photo','public'));
         //$fullPath =public_path(/*$path_prefix.*/$imageName);
         //return basename($path);/*$request->file('photo')->getClientOriginalName()*/;
        // return $this->retrive_size(basename($path));
        //return $imageSave;
         //$prefix_path = 'https://recitrocapi.herokuapp.com/storage/'.$imageName;
         $prefix_path = 'https://recitrocapi.herokuapp.com/images/'.$imageName;
         DB::table('t_images_users')->insert([
           "image_user_owner_id"=> $this->authUserId(),
           "image_user_src"=>$prefix_path
         ]);

         return array(
           "statut" =>"success",
           "message"=>"image uploaded"
         );
       }
       /*$filename ="wp2.png";
       $path = $request->file('photo')->move(public_path("/"),$filename);
       $photoURL = url('/'.$filename);
       return response()->json(["url"=>$photoURL],200);*/
      }
      public function retrive_size($filename)
      {
        //$url = Storage::url('upload.jpg');
        $size = Storage::size($filename);
        return $size;
        //http://127.0.0.1:8000/storage/FnSSTfOp5VISKtdMRNxcvLhgoe77k4L9KR9gIf4X.jpeg
      }

    public function index()
    {

        /*SELECT count(users.user_id) as items_number
         */

        $users = DB::table('users')
        ->select('users.user_id','users.name','users.email','users.user_adresse',DB::raw('count(t_items.item_id) as nombre_item'))
        ->leftJoin('t_items','users.user_id','t_items.id_user')
        ->groupBy('users.user_id')
        ->get();

       // $all_users = user::all()->toArray() ;
        return response()->json([
            "status" => "success",
            "data" =>$users  ,
        ]);
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
        $user_details = DB::table('users')
        ->select('t_items.*','t_items_statut.*')
        ->join('t_items','t_items.id_user','=','users.user_id')
        ->join('t_items_statut','t_items.item_statut_id','=','t_items_statut.item_statut_id')
        ->where('users.user_id','=',$id)
        ->get();
        return response()->json([
            "status" => "success",
            "user_details"=> user::find($id)->toArray(),
            "items" => $user_details
        ]);
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
    public function update(Request $request, User $user)
    {
        //
        $input = $request->all();

        $validate = validator::make($input,[
            "email" =>"required|email",
            "name" =>"required|String",
        ]);

        if($validate->fails())
        {
            return Response()->json([
                "error" => $validate->errors(),

            ]);
        }

        $user->email = $request->email ;
        $user->name = $request->name ;
        $user->save();

        return Response()->json([
            "statut" => "success",
            "message" => $user->name." has been succesfuly updated !",

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $delete = user::find($id);
         $delete->delete();

        return Response()->json([
            "statut" => "success",
            "message" => $delete->name." has been succesfuly deleted !",

        ]);
    }
}
