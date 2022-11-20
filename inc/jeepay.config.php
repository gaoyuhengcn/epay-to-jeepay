<?php
/**
 * 配置交换文件
 * @author 虎恒恒吖<gaoyuheng@yuheng.hl.cn>
 */

//系统默认配置
$jeepay_submit['version']='1.0';            //版本号
$jeepay_submit['signType']='MD5';           //签名方式

//支付（提交）的配置
$jeepay_submit['mchNo']=$channel['appmchid'];  //商户号
$jeepay_submit['appId']=$channel['appid'];  //应用ID

//其他配置
$jeepay['appurl']=$channel['appurl'];       //payapi地址
$jeepay['key']=$channel['appkey'];             //应用私钥