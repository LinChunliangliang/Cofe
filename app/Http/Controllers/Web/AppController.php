<?php
/**
 * Created by PhpStorm.
 * User: linchunliang
 * Date: 2019-07-07
 * Time: 11:11
 */

namespace app\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class AppController extends Controller{
    public function getApp(){
        return view('app');
    }
 /*   public function getLogin(){
        return view('login');
    }*/
    public function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }
}