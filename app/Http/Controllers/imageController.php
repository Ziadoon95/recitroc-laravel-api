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

class imageController extends Controller
{
    //
    public function create(request $request)
    {
      $path = $request->file('photo')->store('images','s3');
      return $path ;

    }
    public function store(Request $request)
    {

    }
    public function show(image $image)
    {

    }

}
