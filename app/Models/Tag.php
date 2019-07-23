<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    //用于支持批量赋值
    protected $fillable=[
        'name'
    ];

    public function cafes(){
        return $this->belongsToMany(Cafe::class,'cafes_users_tags','tag_id','user_id');
    }
}
