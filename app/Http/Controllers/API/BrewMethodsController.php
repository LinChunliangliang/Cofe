<?php

namespace App\Http\Controllers\API;

use App\Models\BrewMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrewMethodsController extends Controller
{
    //
    /**
     * 获取所有冲泡方法以及拥有该冲泡方法的咖啡店数目
     *
     * 请求API: /api/v1/brew-methods
     * 请求方法: GET
     */
    public function getBrewMethods(){
        //
        //获取所有包含咖啡店数目的冲泡方法
        //不加载关联关系的情况下统计关联结果数目，可以使用 withCount 方法
        $data=BrewMethod::withCount('cafes')->get();
//        dump($data);

        return response()->json($data);
    }
}
