<?php
/**
 * jeepay 请求函数集
 */

function jeepay_get_qr($array,$sign)
{
    global $jeepay;
    $array['sign']=$sign;//加入签名变量集
    $unifiedOrder_url=$jeepay['appurl'].'api/pay/unifiedOrder';//统一下单接口域名
    $data=post($unifiedOrder_url,$array);
    $data=json_decode($data,1);
    if ($data['code']!=0){
        //处理失败
        return array(
            'code'=>0,
            'msg'=>$data['msg'],
        );
    }else{
        return array(
            'code'=>1,
            'url'=>$data['data']['payData']
        );
    }
}

//查询订单
function jeepay_query($tradeno){
    global $jeepay_submit,$jeepay;
    //构建参数
    $jeepay_submit['mchOrderNo']=$tradeno;
    $jeepay_submit['reqTime']=time().'000';
    //签名
    $sign=jeepay_md5_sign($jeepay_submit)['sign'];

    //请求
    $jeepay_submit['sign']=$sign;//加入签名变量集
    $unifiedOrder_url=$jeepay['appurl'].'api/pay/query';//查询订单接口域名
    $data=post($unifiedOrder_url,$jeepay_submit);
    $data=json_decode($data,1);

    //结果
    if ($data['code']!=0){
        //处理失败
        return array(
            'code'=>0,
            'msg'=>$data['msg'].'(sign1='.$sign.'sign2='.$data['sign'].')',
        );
    }else{
        return array(
            'code' => 1,
            'data' => $data['data']
        );
        }

}

//获取类别
function get_imgmode(){
    if(checkmobile()!=true){
        //电脑配置
        $img_mode='1';//设定二维码模式
    }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        //微信配置
        $img_mode='0';//设定二维码模式
    }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
        //支付宝配置
        $img_mode='0';//设定二维码模式
    }else{
        //手机其他浏览器
        $img_mode='1';//设定二维码模式
    }
    return $img_mode;
}


//http post接口类
function post($url, $post_data) {
    $postdata = http_build_query($post_data);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        ),
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}