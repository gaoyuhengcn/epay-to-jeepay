# epay-to-jeepay
这个插件的作用是让你的彩虹易支付程序可以将计全支付（jeepay.com）开源版作为你的支付方式。



### 程序支持的支付渠道

wxpay(微信支付)

alipay(支付宝支付)

jeepay(聚合支付)

推荐选择jeepay聚合支付，但是你需要在“后台->支付接口->支付方式”里面添加 调用值为“jeepay“的支付方法。



### 程序支持的易支付版本 

2022.02版本，推荐使用Blokura老哥发布的版本

[Blokura/Epay: 2020.02彩虹易支付原版开源 (github.com)](https://github.com/Blokura/Epay)



### 程序支持的支付方式

目前为止，这个程序只支持聚合扫码交易，电脑是弹出聚合二维码供扫描，手机是直接跳转到这个二维码内的网页上。

后续将开发出别的方式，比如wap支付......



### 程序安装方法

首先，你需要

```
git clone https://github.com/gaoyuhengcn/epay-to-jeepay.git
```

然后将epay-to-jeepay重命名为jeepay

```
mv epay-to-jeepay jeepay
```

最后，将jeepay目录上传到epay的plugins目录下，形成以下目录结构

```
epay.example.com
	plugins
		wxpay
		wxpaysl
		alipay
		aliold
		jeepay
			inc
			page
			submit.php
			notify.php
			config.inc
			qrcode.php
			return.php
			......
		jdpay
		......
	admin
	user
	index.php
	submit.php
	submit2.php
	.......
		
```



### 程序的使用

1.后台->支付接口->支付插件里，刷新插件列表

2.后台->支付接口->支付方式里，添加 调用值为“jeepay“的支付方法。

3.后台->支付接口->支付通道里，添加支付插件为计全支付的支付通道。

4.点击配置秘钥，注意，pay域名要写你payment服务的域名，包含协议名和/，例如：

```
https://pat.example.com
```



### 开源协议

apache 2.0

