<?php
/**
 * 计全支付扫码支付
 */
if(!defined('IN_PLUGIN'))exit();
@header('Content-Type: text/html; charset=UTF-8');

$sitename=htmlspecialchars(base64_decode($_GET['sitename']));

//引入配置文件
require_once(PAY_ROOT."inc/jeepay.config.php");
require_once(PAY_ROOT."inc/jeepay.sign.php");
require_once(PAY_ROOT."inc/jeepay.submit.php");

//开始配置
$jeepay_submit['currency']='cny';           //货币代码，不可少
//$jeepay_submit['channelExtra']=json_encode(array('payDataType'=>'codeImgUrl'));//渠道参数设置成：二维码地址
$jeepay_submit['mchOrderNo']=TRADE_NO;//本地系统订单号
$jeepay_submit['wayCode']='QR_CASHIER';//支付方法（聚合扫码-用户扫商家）暂且为固定值
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

$code_url=$url['url'];
//暂时页面文件就这样了，等后续有时间在该
require_once(PAY_ROOT."page/wx.code.php");