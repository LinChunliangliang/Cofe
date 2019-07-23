<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\EditUserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    //
    public function getUser(){

        return Auth::guard('api')->user();
    }
    public function delLogout(Response $response){
        $cookie = cookie('laravel_session', '');
        return $response->cookie($cookie);
    }

    public function putUser(Request $request){
        //修改用户信息
        $user=Auth::user();
//        dd($request);

        $favoriteCoffee=$request->input('favorite_coffee');
        $flavorNotes = $request->input('flavor_notes');
        $profileVisibility = $request->input('profile_visibility');
        $city = $request->input('city');
        $state = $request->input('state');

        if($favoriteCoffee){
            $user->favorite_coffee=$favoriteCoffee;
        }
        if($flavorNotes){
            $user->flavor_notes=$flavorNotes;
        }
        if(!is_null($profileVisibility)){
            $user->profile_visibility=$profileVisibility;
        }
        if($city){
            $user->city=$city;
        }
        if($state){
            $user->state=$state;
        }


        $user->save();

        return response()->json(['user_updated'=>true],201);



    }

}
