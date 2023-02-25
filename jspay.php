<?php
//微信jsapi支付
if(!defined('IN_PLUGIN'))exit();
@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));
//引入配置文件
require_once(PAY_ROOT."inc/jeepay.config.php");
require_once(PAY_ROOT."inc/jeepay.sign.php");
require_once(PAY_ROOT."inc/jeepay.submit.php");

//关于微信openid的缓存问题
if(empty($_GET['channelUserId'])){
    $jeepay_oauth=$jeepay_submit;
    $jeepay_oauth['ifCode']='AUTO';           //货币代码，不可少
    $jeepay_oauth['redirectUrl']=$siteurl.'pay/jeepay/jspay/'.TRADE_NO.'/?d=1';//同步跳转地址
    $jeepay_oauth['reqTime']=time().'000';//13位时间戳（偷懒版）
    $jeepay_oauth['sign']=jeepay_md5_sign($jeepay_oauth,$jeepay['key'])['sign'];
    $query=http_build_query($jeepay_oauth);
    echo "<script>window.location.href='{$jeepay['appurl']}api/channelUserId/jump?{$query}';</script>";
}

//开始配置
$jeepay_submit['currency']='cny';           //货币代码，不可少
$jeepay_submit['channelExtra']=json_encode(array('openid'=>$_GET['channelUserId']));//渠道参数设置成：openid
$jeepay_submit['mchOrderNo']=TRADE_NO;//本地系统订单号
$jeepay_submit['wayCode']='WX_JSAPI';//支付方法
$jeepay_submit['amount']=strval($order['money']*100);//金额，单位为分
$jeepay_submit['subject']=$order['name'];//商品标题
$jeepay_submit['body']=$order['name'];//商品描述
$jeepay_submit['notifyUrl']=$conf['localurl'].'pay/jeepay/notify/'.TRADE_NO.'/?id=1';//异步通知地址
$jeepay_submit['returnUrl']=$siteurl.'pay/jeepay/return/'.TRADE_NO.'/?id=1';//同步跳转地址
$jeepay_submit['reqTime']=time().'000';//13位时间戳（偷懒版）

//MD5加签(结果是访问的url path)
$sign=jeepay_md5_sign($jeepay_submit,$jeepay['key'])['sign'];

//构建跳转地址
$url=jeepay_get_qr($jeepay_submit,$sign);
if($url['code']!=1)sysmsg($url['msg']);

$jsApiParameters=$url['url'];
$_SESSION[$trade_no.'_wxpay'] = $jsApiParameters;

if($_GET['d']==1){
    $redirect_url='data.backurl';

}else{
    $redirect_url='\'/pay/jeepay/ok/'.TRADE_NO.'/\'';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <link href="//cdn.staticfile.org/ionic/1.3.2/css/ionic.min.css" rel="stylesheet" />
</head>
<body>
<div class="bar bar-header bar-light" align-title="center">
    <h1 class="title">微信安全支付</h1>
</div>
<div class="has-header" style="padding: 5px;position: absolute;width: 100%;">
    <div class="text-center" style="color: #a09ee5;">
        <i class="icon ion-information-circled" style="font-size: 80px;"></i><br>
        <span>正在跳转...</span>
        <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
        <script src="//cdn.staticfile.org/layer/2.3/layer.js"></script>
        <script>
            $(document).on('touchmove',function(e){
                e.preventDefault();
            });
            //调用微信JS api 支付
            function jsApiCall()
            {
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    <?php echo $jsApiParameters; ?>,
                    function(res){
                        if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                            loadmsg();
                        }
                        //WeixinJSBridge.log(res.err_msg);
                        //alert(res.err_code+res.err_desc+res.err_msg);
                    }
                );
            }

            function callpay()
            {
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else{
                    jsApiCall();
                }
            }
            // 检查是否支付完成
            function loadmsg() {
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "/getshop.php",
                    timeout: 10000, //ajax请求超时时间10s
                    data: {type: "wxpay", trade_no: "<?php echo TRADE_NO?>"}, //post数据
                    success: function (data, textStatus) {
                        //从服务器得到数据，显示数据并继续查询
                        if (data.code == 1) {
                            layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.01,time: 15000});
                            window.location.href=<?php echo $redirect_url?>;
                        }else{
                            setTimeout("loadmsg()", 2000);
                        }
                    },
                    //Ajax请求超时，继续查询
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        if (textStatus == "timeout") {
                            setTimeout("loadmsg()", 1000);
                        } else { //异常
                            setTimeout("loadmsg()", 4000);
                        }
                    }
                });
            }
            window.onload = callpay();
        </script>
    </div>
</div>
</body>
</html>