# epay-to-jeepay
这个插件的作用是让你的彩虹易支付程序可以将计全支付（jeepay.com）开源版作为你的支付方式。



### 程序支持的支付渠道

wxpay(微信支付)

alipay(支付宝支付)



### 程序支持的易支付版本 

2022.02版本，推荐使用Blokura老哥发布的版本

[Blokura/Epay: 2020.02彩虹易支付原版开源 (github.com)](https://github.com/Blokura/Epay)



### 程序支持的支付方式

截止2023年2月25日，程序已支持微信原生jsapi支付，聚合收银台支付，微信扫码支付，支付宝当面付模式。
支付方式优先级：jsapi支付，微信，支付宝扫码支付,聚合收银台



### 关于退款功能
退款功能暂时还没能攻克，请待后续版本发布.......

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

2.后台->支付接口->支付通道里，添加支付插件为计全支付的支付通道。

3.点击配置秘钥，注意，pay域名要写你payment服务的域名，包含协议名和/，例如：

```
https://pat.example.com/
```

4.在配置秘钥界面勾选好支付方式，支付宝个人版建议只勾选支付宝扫码

### 开源协议

apache 2.0

