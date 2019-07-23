<?php
/**
 * Created by PhpStorm.
 * User: linchunliang
 * Date: 2019-07-09
 * Time: 23:59
 */
namespace App\Utilities;

use GuzzleHttp\Client;

class GaodeMaps{

    public static function geocodeAddress($address,$city,$state){
        //省，市，区，详细地址
//        dump($address);
//        dump($city);
//        dump($state);
        $address=urlencode($state . $city . $address);
//        $address=$state.$city.$address;
//        //Web服务API KEY
//        $apiKey=config('services.gaode.ws_api_key');
        $apiKey='227e630c73601ebd2cad48545a91a127';
//        dump($apiKey);

//        dump($address);
        //构建地理编码API请求URL，默认返回json格式响应
        $url='https://restapi.amap.com/v3/geocode/geo?address='.$address.'&key='.$apiKey;
//        $url = 'https://restapi.amap.com/v3/geocode/geo?address=' . $address . '&key=' . $apiKey;
//        dump($url);
        //创建Guzzle HTTP客户端发起请求 Guzzle是一个php的http客户端，可以轻而易举的发送请求

        $client=new Client();

        //发送请求并且获取响应数据

        $geocodeResponse=$client->get($url)->getBody();
//        dump($geocodeResponse);
        $geocodeData=\GuzzleHttp\json_decode($geocodeResponse);
//        dump($geocodeData);
        //初始化地理编码位置
        $coordinates['lat']=null;
        $coordinates['lng']=null;

        //如果响应数据不为空则解析出经纬度
        if(!empty($geocodeData) && $geocodeData->status && isset($geocodeData->geocodes) && isset($geocodeData->geocodes[0])){
            list($latitude,$longitude)=explode(',',$geocodeData->geocodes[0]->location);
            $coordinates['lat']=$latitude; //经度
            $coordinates['lng']=$longitude; //纬度
        }
//        dump($coordinates);
        return $coordinates;
    }

}