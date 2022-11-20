<?php
/**
 * 统一下单入口
 */
if(!defined('IN_PLUGIN'))exit();

//引入配置文件
require_once(PAY_ROOT."inc/jeepay.config.php");
require_once(PAY_ROOT."inc/jeepay.sign.php");
require_once(PAY_ROOT."inc/jeepay.submit.php");

//检查imgmode是否支持在直接跳转payment（函数在jeepay.submit.php里）
if(get_imgmode()==1){
    echo "<script>window.location.href='{$siteurl}pay/jeepay/qrcode/{$trade_no}/?d=1';</script>";exit;
}

//开始配置
$jeepay_submit['currency']='cny';           //货币代码，不可少
$jeepay_submit['mchOrderNo']=$trade_no;//本地系统订单号
$jeepay_submit['wayCode']='QR_CASHIER';//支付方法（聚合扫码-用户扫商家）暂且为固定值
$jeepay_submit['amount']=strval($order['money']*100);//金额，单位为分
$jeepay_submit['subject']=$order['name'];//商品标题
$jeepay_submit['body']=$order['name'];//商品描述
$jeepay_submit['notifyUrl']=$conf['localurl'].'pay/jeepay/notify/'.TRADE_NO.'/';//异步通知地址
$jeepay_submit['returnUrl']=$siteurl.'pay/jeepay/return/'.TRADE_NO.'/';//同步跳转地址
$jeepay_submit['reqTime']=time().'000';//13位时间戳（偷懒版）

//MD5加签(结果是访问的url path)
$sign=jeepay_md5_sign($jeepay_submit)['sign'];

//构建跳转地址
$url=jeepay_get_qr($jeepay_submit,$sign);
if($url['code']!=1)sysmsg('无法访问，上端系统提示'.$url['msg']);

echo "<script>window.location.href='{$url['url']}';</script>";exit;
