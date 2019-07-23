<?php
/**
 * Created by PhpStorm.
 * User: linchunliang
 * Date: 2019-07-17
 * Time: 19:35
 */

namespace App\Utilities;
use App\Models\Tag;


class Tagger{
    public static function tagCafe($cafe,$tags,$userId){
        //遍历标签数据，分别存储每个标签，并建立其余咖啡店的关联
//        dump($cafe);
//        dump($userId);
//        dump($tags);exit;

        foreach ($tags as $tag){
            $name=trim($tag);

            //
            $newCafeTag=Tag::firstOrNew(array('name'=>$name));

            $newCafeTag->name=$name;
//            dump($newCafeTag);
//            dump($newCafeTag);exit();
            $newCafeTag->save();
//            dump($res);exit();

            //将标签和咖啡店关联起来
            $cafe->tags()->syncWithoutDetaching([$newCafeTag->id=>['user_id'=>$userId]]);



        }
    }
}