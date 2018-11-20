<?php
namespace app\common\lib\lbs;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/18
 * Time: 13:46
 */
/**
 * Encode and decode geohashes
 */
class Geohash {
    private $coding = "0123456789bcdefghjkmnpqrstuvwxyz";
    private $key = '85ef9b6b4f3d1c0b3e4c1c39676e4562';
    private $codingMap = array();

    /**
     * 通过地址信息获取经纬度
     * @param $address string 详细地址信息
     * @param $city_name string 城市名
     * @return array|bool 返回数组代表成功，0位lon , 1位lat
    */
    public function geocoding( $address , $city_name = ''){
        $url = 'http://restapi.amap.com/v3/geocode/geo?';
        $address = $city_name.$address;
        $param = http_build_query([
            'key' => $this->key,
            'address' => $address,
            'city' => $city_name
        ]);
        $remote = curl_request( $url.$param );
        if( empty($remote) ){
            return false;
        }
        $data = json_decode( $remote , true );
        // print_r( $data );
        if( $data['status'] == 0 || $data['count'] == 0 ){
            return false;
        }
        $result = $data['geocodes'][0]; //取第一个结果
        return explode(',' , $result['location']);
    }

    public function Geohash() {
        for($i = 0; $i < 32; $i++) {
            $this->codingMap[substr($this->coding, $i, 1)] = str_pad(decbin($i), 5, "0", STR_PAD_LEFT);
        }
    }

    public function decode($hash) {
        $binary = "";
        $hl = strlen($hash);
        for($i = 0; $i < $hl; $i++) {
            $binary .= $this->codingMap[substr($hash, $i, 1)];
        }

        $bl = strlen($binary);
        $blat = "";
        $blong = "";
        for ($i = 0; $i < $bl; $i++) {
            if ($i%2) {
                $blat = $blat.substr($binary, $i, 1);
            } else {
                $blong = $blong.substr($binary, $i, 1);
            }
        }

        $lat = $this->binDecode($blat, -90, 90);
        $long = $this->binDecode($blong, -180, 180);

        $latErr = $this->calcError(strlen($blat), -90, 90);
        $longErr = $this->calcError(strlen($blong), -180, 180);

        $latPlaces = max(1, -round(log10($latErr))) - 1;
        $longPlaces = max(1, -round(log10($longErr))) - 1;

        $lat = round($lat, $latPlaces);
        $long = round($long, $longPlaces);

        return array($lat,$long);
    }

    public function encode($lat,$long) {
        $plat = $this->precision($lat);
        $latbits = 1;
        $err = 45;
        while($err > $plat) {
            $latbits++;
            $err /= 2;
        }

        $plong = $this->precision($long);
        $longbits = 1;
        $err = 90;
        while($err > $plong) {
            $longbits++;
            $err /= 2;
        }
        $bits = max($latbits,$longbits);
        $longbits = $bits;
        $latbits = $bits;
        $addlong = 1;
        while (($longbits+$latbits) % 5 != 0) {
            $longbits += $addlong;
            $latbits += !$addlong;
            $addlong = !$addlong;
        }
        $blat = $this->binEncode($lat, -90, 90, $latbits);
        $blong = $this->binEncode($long, -180, 180, $longbits);
        $binary = "";
        $uselong = 1;
        while (strlen($blat)+strlen($blong)) {
            if ($uselong) {
                $binary = $binary.substr($blong, 0, 1);
                $blong = substr($blong, 1);
            } else {
                $binary = $binary.substr($blat, 0, 1);
                $blat = substr($blat, 1);
            }
            $uselong = !$uselong;
        }
        $hash = "";
        for ($i = 0; $i < strlen($binary); $i += 5) {
            $n = bindec(substr($binary, $i, 5));
            $hash = $hash . $this->coding[$n];
        }
        return $hash;
    }

    private function calcError($bits, $min, $max) {
        $err = ($max - $min) / 2;
        while ($bits--) {
            $err /= 2;
        }
        return $err;
    }

    private function precision($number) {
        $precision = 0;
        $pt = strpos($number,'.');
        if ($pt !== false ) {
            $precision = -(strlen($number) - $pt - 1);
        }
        return pow(10, $precision) / 2;
    }

    private function binEncode($number, $min, $max, $bitcount) {
        if ($bitcount == 0) {
            return "";
        }
        $uid = ($min + $max) / 2;
        if ($number > $uid) {
            return "1" . $this->binEncode($number, $uid, $max, $bitcount - 1);
        } else {
            return "0" . $this->binEncode($number, $min, $uid, $bitcount - 1);
        }
    }

    private function binDecode($binary, $min, $max) {
        $uid = ($min + $max) / 2;

        if (strlen($binary) == 0) {
            return $uid;
        }
        $bit = substr($binary, 0, 1);
        $binary = substr($binary, 1);

        if ($bit == 1) {
            return $this->binDecode($binary, $uid, $max);
        } else {
            return $this->binDecode($binary, $min, $uid);
        }
    }
}