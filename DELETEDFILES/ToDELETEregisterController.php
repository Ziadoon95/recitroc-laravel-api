<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\panier ;

class registerController extends Controller
{
 
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    //change this function name to register
    protected function validator(request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|max:1',
            'adresse' => 'nullable|string|max:255',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }else{
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'user_role' => $request->get('role'),
                'user_adresse' => $request->get('adresse'),
                'password' => Hash::make($request->get('password')),
            ]);
            //create panier for this user
            
            $create_panier = panier::create([
                'user_id' => $user->user_id,
                'panier_satut_id' => 1,
            ]);
            

            return response()->json([
                "statut" =>"success",
                "data" =>$user->name." has been created !",
            ]);
                
        }
  


    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'user_name' => $data['name'],
            'user_email' => $data['email'],
            'user_password' => Hash::make($data['password']),
        ]);
    }

    /**
     * show all users
     */
    public function show_all_users()
    {
        return User::all();
    }
}
