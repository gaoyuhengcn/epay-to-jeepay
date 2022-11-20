<?php
/**
 * 计全支付（隐藏式）回调文件
 */
if(!defined('IN_PLUGIN'))exit();

//引入配置文件
require_once(PAY_ROOT."inc/jeepay.config.php");
require_once(PAY_ROOT."inc/jeepay.sign.php");
require_once(PAY_ROOT."inc/jeepay.submit.php");

//本着不相信returnurl的真实性，所以这个案例调用查询订单URL
$tradeno = TRADE_NO;//回调地址内置的订单号

//当然，不信任上边带的数据，也就自然可以不用验签了

//1.查询订单
$data=jeepay_query($tradeno);
if($data['code']==0)sysmsg('无法访问，上端系统提示'.$data['msg']);
$data=$data['data'];

//第二步，过滤失败订单
$trade_status = $data['state'];                    //订单状态，2为成功
if($trade_status!='2'){
    echo 'faild(pay fail)';
    die();
}

//第三步，查重
$my_trade_no = daddslashes($data['mchOrderNo']);  //本系统订单号
$pay_trade_no = daddslashes($data['payOrderId']); //payment订单号
$money = $data['amount'];                         //金额

//如果上边系统下来的订单号=本地系统的订单号，金额相等，支付状态为0（未支付）
if($my_trade_no == TRADE_NO && $money==strval($order['money']*100) && $order['status']==0){
    //将支付状态变成1（已支付）
    if($DB->exec("update `pre_order` set `status` ='1' where `trade_no`='$my_trade_no'")){
        //写入上边的订单号和现在的日期
        $DB->exec("update `pre_order` set `api_trade_no` ='$pay_trade_no',`endtime` ='$date',`date` =NOW() where `trade_no`='$my_trade_no'");
        processOrder($order);
    }
}

//输出成功
echo 'success';
