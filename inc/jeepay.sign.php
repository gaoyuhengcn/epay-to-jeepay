<?php
/**
 * 计全支付MD5签名
 */

//签名
function jeepay_md5_sign($array)
{
    global $jeepay;
    ksort($array);//对数组升序排列
    $http_build=array_to_path($array);
    $sign=md5($http_build.'&key='.$jeepay['key']);//MD5签名值
    return array(
        'sign'=>$sign,
        'url'=>$http_build.'&sign='.$sign,
        'array'=>$array
    );
}


//验签
function jeepay_md5_vif($data)
{
    $sign=$data['sign'];
    unset($data['sign']);
    $sign2=jeepay_md5_sign($data);
    if ($sign==$sign2){
        return true;
    }else{
        return false;
    }
}


function array_to_path($array){
    $path='';
    foreach ($array as $key => $value) {
        $path .=rawurldecode($key).'='.rawurldecode($value).'&';
    }
    $path = substr($path,0,-1);
    return $path;
}