<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    //定义like方法多对多关系
    public function likes(){
        return $this->belongsToMany(User::class,'users_cafes_likes','cafe_id','user_id');
    }
    //定义冲泡方法多对多关系
    public function brewMethods(){
        return $this->belongsToMany(BrewMethod::class,'cafes_brew_methods','cafe_id','brew_method_id');
    }
    //关联分店
    public function children(){
        return $this->hasMany(Cafe::class,'parent','id');
    }
    //归属总店
    public function parent(){
        return $this->hasOne(Cafe::class,'id','parent');
    }

    public function userLike(){
        return $this->belongsToMany(User::class,'users_cafes_likes','cafe_id','user_id')->where('user_id',auth()->id());
    }

    public function tags(){
        return$this->belongsToMany(Tag::class,'cafes_users_tags','cafe_id','tag_id');
    }

    public function photos(){
        return $this->hasMany(CafePhoto::class,'id','cafe_id');
    }

}
