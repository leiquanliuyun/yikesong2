<?php
namespace app\common\lib\lbs;
use think\cache\driver\Redis;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/18
 * Time: 12:53
 */
class LbsServer extends Redis {

    /**@var \Redis*/
    protected $handler;
    protected $key = 'server_lbs' ;

    public function __construct(array $options = [])
    {
        $config = config('cache');
        parent::__construct($config);

    }

    /**
     * 设置用户经纬度
     * @param $user_id mixed 用户ID
     * @param $lat     mixed 经度
     * @param $lng     mixed 纬度
     * @return bool    设置成功返回true
    */
    public function setLbs( $user_id , $lat , $lng ){

        if( !empty($this->getLbsByUserId( $user_id )) ){
            $this->handler->zRem( $this->key , $user_id );
        }

        $lng = substr( $lng , 0 , 11 );
        $lat = substr( $lat , 0 , 11 );
        if($this->handler->rawCommand('geoadd', $this->key, $lng , $lat, $user_id)){
            return true;
        }
        return false;
    }

    /**
     * 通过user_id 获取经纬度
     * @param $user_id mixed 用户的ID
     * @return mixed
    */
    public function getLbsByUserId( $user_id ){
        $lbs = $this->handler->rawCommand( 'geopos' , $this->key , $user_id );
        return empty($lbs) ? false : empty($lbs[0]) ? false : ['lng'=>$lbs[0][0],'lat'=>$lbs[0][1]];
    }

    /**
     * 通过指定经纬度，给定范围内获取周边的经纬度
     * @param $lat mixed 经度
     * @param $lng mixed 纬度
     * @param $radius mixed 范围大小
     * @param $unit string 单位：m|km|ft|mi
     * @return  array|bool
    */
    public function getLbsByRadius( $lat , $lng , $radius , $unit = 'km'){
        $data = $this->handler->rawCommand('GEORADIUS' , $this->key , $lng , $lat , $radius , $unit , 'WITHDIST');
        if( empty($data) ){
            return false;
        }
        $res = [];
        foreach ( $data as $val ){
            array_push( $res ,
                ['user_id'=>$val[0] , 'distance'=>$val[1]]
            );
        }
        // 从近到远进行排序
        $res = array_sort($res , 'distance' , SORT_ASC , SORT_NUMERIC);
        return $res;
    }

    /**
     * 计算两点地理坐标之间的距离
     * @param  mixed $longitude1 起点经度
     * @param  mixed $latitude1  起点纬度
     * @param  mixed $longitude2 终点经度
     * @param  mixed $latitude2  终点纬度
     * @param  Int     $unit       单位 1:米 2:公里
     * @param  Int     $decimal    精度 保留小数位数
     * @return Decimal
     */
    public function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2){

        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if($unit==2){
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);

    }

}