<?php
/**
 * Created by PhpStorm.
 * User: linchunliang
 * Date: 2019-07-07
 * Time: 11:51
 */

namespace app\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;


class AuthenticationController extends Controller
{
    public function getSocialRedirect($account)
    {
        try {
            return Socialite::with($account)->redirect();

        } catch (\InvalidArgumentException $e) {
            return redirect('/');
        }
    }

    public function getSocialCallback($account)
    {
//        $account为第三方过来的方式
        //从第三方OAuth回调中获取用户信息
        $socialUser = Socialite::with($account)->user();
        //在本地users表中查询该用户是否已经存在
/*dump($account);
        dump($socialUser);exit;*/
        $user = User::where('provider_id', '=', $socialUser->id)->where('provider','=',$account)->first();
        if($user==null){
            //如果用户不存在，则存入数据库
            $newUser=new User();
            $newUser->name=$socialUser->getName();
            $newUser->email=$socialUser->getEmail()==''?'':$socialUser->getEmail();
            $newUser->avatar=$socialUser->getAvatar();
            $newUser->password='';
            $newUser->provider=$account;
            $newUser->provider_id=$socialUser->getId();

            $newUser->save();

            $user=$newUser;


        }
        //手动登陆该用户
        Auth::login($user);

        //登陆成功后将用户重定向到首页
        return redirect('/#/home');
    }
}