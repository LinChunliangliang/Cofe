<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreCafeRequest;
use App\Models\CafePhoto;
use App\Utilities\GaodeMaps;
use App\Utilities\Tagger;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cafe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

class CafesController extends Controller
{

    public function getCafes(){


        $cafes=Cafe::with('brewMethods')->with('userLike')->with(['tags'=>function($query){
            $query->select('name');
        }])->get();
//        dd($cafes);
        return response()->json($cafes);

    }

    public function postNewCafe(Request $request){   // Cafe $cafe



//        dd($request->all());
//        dd($request->get('website'));
//        dump($request->all());
        $request->validate([
            'name'=>'required|max:10',
            'location.*.address'=>'required',
            'location.*.city'=>'required',
            'location.*.state'=>'required',
            'location.*.zip'=>'required|regex:/\b\d{6}\b/',
            'location.*.brew_methods'=>'sometimes|array',
            'website'=>'sometimes|url'

        ],[
            'name.required'=>'咖啡店名字不能为空',
            'name.max'=>'咖啡店名字不能超过10个字符',
            'location.*.address.required'=>'咖啡店的地址不能为空',
            'location.*.city.required'=>'咖啡店的所在城市不能为空',
            'location.*.state.required'=>'咖啡店的省份不能为空',
            'location.*.zip.required'=>'咖啡店邮编不能为空',
            'location.*.zip.regex'=>'无效的邮编',
            'location.*.brew_methods.array'=>'无效的冲泡方式',
            'website.url'=>'无效的咖啡店网址'

        ]);


        //已添加的咖啡店
        $addedCafes=[];
        //所有位置信息
        $locations=$request->get('locations');
        $locations=json_decode($locations);
//        dump($locations);
//        exit();

        //总店信息
        $parentCafe=new Cafe();

//        $locations[0]=$locations[0]->toArray();
        $locations[0]=(array)$locations[0];
//        dd($locations[0]);

        $parentCafe->name=$request->get('name');
        //分店位置名称
        $parentCafe->location_name=$locations[0]['name'] ? : '';
        //分店地址
        $parentCafe->address=$locations[0]['address'];
        //分店地址
        $parentCafe->city=$locations[0]['city'];
        //分店地址
        $parentCafe->state=$locations[0]['state'];
        //分店地址
        $parentCafe->zip=$locations[0]['zip'];

        $coordinates=GaodeMaps::geocodeAddress($parentCafe->address,$parentCafe->city,$parentCafe->state);
        //经度
        $parentCafe->latitude=$coordinates['lat'];
        //纬度
        $parentCafe->longitude=$coordinates['lng'];

        //烘培师
        $parentCafe->roaster=$request->get('roaster')?1:0;
        //咖啡店网址
        $parentCafe->website=$request->get('website');

        //描述信息
        $parentCafe->description=$request->get('description')?:'';
        //添加者
        $parentCafe->added_by=$request->user()->id;
        //保存
        /////////////
        $parentCafe->save();

        //保存图片
        $photo=$request->file('picture');
//        dd($photo);


        if($photo && $photo->isValid()){
            $path=storage_path('app/public/photos/photos-id='.$parentCafe->id);

            if(!file_exists($path)){
                mkdir($path);
            }
//            dump(121212);
            //文件名
            $filename=time().'-'.$photo->getClientOriginalName();

            //保存文件到目标目录
            $photo->move($path,$filename);

            //ruku
            $cafePhoto=new CafePhoto();
            $cafePhoto->cafe_id=$parentCafe->id;
            $cafePhoto->uploaded_by=Auth::user()->id;
            $cafePhoto->file_url=$path.DIRECTORY_SEPARATOR.$filename;

            $cafePhoto->save();



        }
//        $cafe_id=$parentCafe->id;

//        dd($res);

//        dd($res);

        //获取cafe_id


        //冲泡方法
//        dd($locations[0]['methodsAvailable']);

        $brewMethods=$locations[0]['methodsAvailable'];

        //标签信息
        $tags=$locations[0]['tags'];

        //dd($brewMethods);
//        $brewMethod=[];

       // foreach ($brewMethods as $key => $v){
            //$brewMethod[$key]=[$v,$cafe_id];
            //$res=$parentCafe->brewMethods()->sync($brewMethod[$key]);

         //   $res=$parentCafe->brewMethods()->sync([$cafe_id,$v]);
            //dump($brewMethod[$key]);
//            dump($res);
        //}

        //dump($brewMethod);
        /////////////////////
        $parentCafe->brewMethods()->sync($brewMethods);

        Tagger::tagCafe($parentCafe,$tags,$request->user()->id);


        //冲泡方法通过 sync 方法以关联关系方式存储

        // 将当前咖啡店数据推送到已添加咖啡店数组
        array_push($addedCafes, $parentCafe->toArray());

//        dd($res);

        //子咖啡店



        if(count($locations)>1){
            for($i=1;$i<count($locations);$i++){
                $locations[$i]=(array)$locations[$i];

                $cafe=new Cafe();
                $cafe->parent=$parentCafe->id;
                $cafe->name=$request->get('name');
                $cafe->location_name=$locations[$i]['name']?:'';
                $cafe->address=$locations[$i]['address'];
                $cafe->city=$locations[$i]['city'];

                $cafe->state=$locations[$i]['state'];
                $cafe->zip=$locations[$i]['zip'];

                $coordinates=GaodeMaps::geocodeAddress($cafe->address,$cafe->city,$cafe->shate);

                $cafe->latitude=$coordinates['lat'];
                $cafe->longitude=$coordinates['lng'];

                $cafe->roaster=$request->get('roaster')!=''?1:0;
                $cafe->website=$request->get('website');
                $cafe->description=$request->get('description')?:'';
                $cafe->added_by=$request->user()->id;
                //存
//                dd($cafe);
//                dump($cafe);
                $res=$cafe->save();


//                dump($res);
                //存
                $cafe->brewMethods()->sync($locations[$i]['methodsAvailable']);
                //标签
                Tagger::tagCafe($cafe,$locations[$i]['tags'],$request->user()->id);
                array_push($addedCafes,$cafe->toArray());
            }
        }
        return response()->json($parentCafe,201);


    }
    public function getCafe($id){

        $cafe=Cafe::where('id','=',$id)->with('brewMethods')->with('userLike')->with('tags')->first();
//        dump($cafe);exit();
        return response()->json($cafe);
    }

    //添加喜欢咖啡功能
    public function postLikeCafe($cafeID){
        $cafe=Cafe::where('id','=',$cafeID)->first();
//        dump($cafe);exit();

//        $cafe->likes()->sync();
       $cafe->likes()->attach(Auth::user()->id,[
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);


        return response()->json(['cafe_like'=>true],201);
    }
    public function deleteLikeCafe($cafeID){
        $cafe=Cafe::where('id','=',$cafeID)->first();
        $cafe->likes()->detach(Auth::user()->id);

        return response(null,204);
    }
    //添加标签
    public function postAddTags(Request $request,$cafeID){
        //从请求中获取标签信息
        $tags=$request->input('tags');
        $cafe=Cafe::find($cafeID);
        //处理新增标签并建立标签和咖啡店之前的关联
        Tagger::tagCafe($cafe,$tags,Auth::user()->id());
        //返回标签
        $cafe=Cafe::where('id','=',$cafeID)->with('brewMethods')->with('userLike')->with('tags')->first();

        return response()->json($cafe,201);


    }
    //删除标签
    public function deleteCafeTag($cafeID,$tagID){
        DB::table('cafes_users_tags')->where('cafe_id',$cafeID)->where('tag_id',$tagID)->where('user_id',Auth::user()->id)->delete();
        return response(null,204);
    }





}
