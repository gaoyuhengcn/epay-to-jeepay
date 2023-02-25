<?php
if(!defined('IN_PLUGIN'))exit();

if(checkmobile()==true){
    //判断为手机环境
    if($order['type']==1){
            //支付方式为支付宝支付
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'alipayclient') !== false) {
                //当客户在支付宝环境下，使用综合收银台
                echo "<script>window.location.href='/pay/jeepay/auto/{$trade_no}/?d=1';</script>";
            } else {
                //当客户在其他浏览器环境时,使用码支付
                echo "<script>window.location.href='/pay/jeepay/qrcode/{$trade_no}/?d=1';</script>";
            }
    }elseif($order['type']==2) {
            //支付方式为微信支付
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                //当客户在微信环境下
                if(in_array('1',$channel['apptype'])){
                    //如果jsapi的话，使用jsapi
                    echo "<script>window.location.href='/pay/jeepay/jspay/{$trade_no}/?d=1';</script>";
                }
                echo "<script>window.location.href='/pay/jeepay/auto/{$trade_no}/?d=1';</script>";
            }
            //当客户在其他浏览器环境时，使用码支付
            echo "<script>window.location.href='/pay/jeepay/qrcode/{$trade_no}/?d=1';</script>";
    }elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        //未说明支付方式，但是支付环境是微信
        if(in_array('1',$channel['apptype'])){
            //如果jsapi的话，使用jsapi
            echo "<script>window.location.href='/pay/jeepay/jspay/{$trade_no}/?d=1';</script>";
        }
        echo "<script>window.location.href='/pay/jeepay/auto/{$trade_no}/?d=1';</script>";
    }elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'alipayclient') !== false){
        //未说明支付方式，但是支付环境是支付宝
        echo "<script>window.location.href='/pay/jeepay/auto/{$trade_no}/?d=1';</script>";
    }else{
        //在其他浏览器中支付
        echo "<script>window.location.href='/pay/jeepay/qrcode/{$trade_no}/?d=1';</script>";
    }
}else{
    //电脑环境
    echo "<script>window.location.href='/pay/jeepay/qrcode/{$trade_no}/?d=1';</script>";
}