<?php
require_once dirname(__DIR__).'/redisData.php';
require_once 'jssdk.php';

$model = $_GET['model'];
$modeldata = array();
switch($model){

	case 'HC-05':
		$modeldata = array(
			'title'=>"汇承HC-05蓝牙串口模块组CSR主从一体51单片机数据传输", 				//型号
			'jiage'=>26.5, 																//价格
			'src'=>"img/HC-05.jpg", 													//图片
			'model_vcc'=>"3.3~3.6V", 													//供电电压
			'model_size'=>"27mm*13mm*2mm",												//尺寸
			'model_jiekou'=>"UART",														//接口类型
			'model_baud'=>"9600bps",													//默认波特率
			'model_distance'=>"~10m",													//通讯距离
			'class'=>"蓝牙2.0设备、安卓手机"											//支持对象
			);
		break;

	case 'HC-06':
		$modeldata = array(
			'title'=>"汇承HC-06 蓝牙串口数据采集模块 连接51单片机CSR无线透传模组",
			'jiage'=>24.0,
			'src'=>"img/HC-06.jpg",
			'model_vcc'=>"3.3~3.6V",
			'model_size'=>"27mm*13mm*2mm",
			'model_jiekou'=>"UART",
			'model_baud'=>"9600bps",
			'model_distance'=>"~10m",
			'class'=>"蓝牙2.0设备、安卓手机"

			);
		break;

	case 'HC-08':
		$modeldata = array(
			'title'=>"汇承HC-08蓝牙4.0串口模块BLE低功耗cc2540/1苹果安卓主从一体spp",
			'jiage'=>19.0,
			'src'=>"img/HC-08.jpg",
			'model_vcc'=>"3.3~3.6V",
			'model_size'=>"27mm*13mm*2mm",
			'model_jiekou'=>"UART",
			'model_baud'=>"9600bps",
			'model_distance'=>"~60m",
			'class'=>"HC-08、安卓手机、苹果手机"

			);
		break;

	case 'HC-02':
		$modeldata = array(
			'title'=>"汇承HC-02串口转蓝牙模块从机无线数据透传模组连接51单片机",
			'jiage'=>15.0,
			'src'=>"img/HC-02.jpg",
			'model_vcc'=>"3.3~3.6V",
			'model_size'=>"27mm*13mm*2mm",
			'model_jiekou'=>"UART",
			'model_baud'=>"9600bps",
			'model_distance'=>"~10m",
			'class'=>"蓝牙2.0设备、安卓手机"

			);
		break;

	case 'HC-09':
		$modeldata = array(
			'title'=>"低价汇承HC-09串口转蓝牙模块无线数据透传从机连接51单片机组",
			'jiage'=>13.0,
			'src'=>"img/HC-09.jpg",
			'model_vcc'=>"3.3~3.6V",
			'model_size'=>"27mm*13mm*2mm",
			'model_jiekou'=>"UART",
			'model_baud'=>"9600bps",
			'model_distance'=>"~10m",
			'class'=>"蓝牙2.0设备、安卓手机"

			);
		break;

	case 'HC-11':
		$modeldata = array(
			'title'=>"汇承HC-11低功耗433MHZ无线串口模块单片机开发远距离CC1101模组",
			'jiage'=>24.0,
			'src'=>"img/HC-11.jpg",
			'model_vcc'=>"3.3~5V",
			'model_size'=>"27.8mm*14.4mm*4mm",
			'model_jiekou'=>"UART",
			'model_baud'=>"9600bps",
			'model_distance'=>"~40m",
			'class'=>"HC-11系列"

			);
		break;

	case 'HC-12':
		$modeldata = array(
			'title'=>"汇承HC-12 SI4463无线单片机串口模块 433远距离1000M替代蓝牙",
			'jiage'=>26.0,
			'src'=>"img/HC-12.jpg",
			'model_vcc'=>"3.3~5V",
			'model_size'=>"27.8mm*14.4mm*4mm",
			'model_jiekou'=>"UART",
			'model_baud'=>"9600bps",
			'model_distance'=>"~1000m",
			'class'=>"HC-12系列"

			);
		break;

	case 'HC-22':
		$modeldata = array(
			'title'=>"HC-22 串口ESP8266 wifi模块 替代蓝牙无线",
			'jiage'=>16.0,
			'src'=>"img/HC-22.jpg",
			'model_vcc'=>"3.3~3.6V",
			'model_size'=>"27mm*13mm*2mm",
			'model_jiekou'=>"UART",
			'model_baud'=>"9600bps",
			'model_distance'=>"~10m",
			'class'=>"wifi设备"

			);
		break;

	case 'HC-101':
		$modeldata = array(
			'title'=>"汇承HC-101蓝牙手机APP学习型红外线万能遥控器家用电视空调智能",
			'jiage'=>38.0,
			'src'=>"img/HC-101.jpg",
			'model_vcc'=>"5V",
			'model_size'=>"43mm*21mm*11mm",
			'model_jiekou'=>"USB",
			'model_baud'=>"9600bps",
			'model_distance'=>"~10m",
			'class'=>"安卓手机APP、红外家电"

			);
		break;

	case 'HC-05-USB':
		$modeldata = array(
			'title'=>"汇承HC-05-USB 蓝牙串口适配器 USB转TTL模块 PC端组无线透传",
			'jiage'=>36.5,
			'src'=>"img/HC-05-USB.jpg",
			'model_vcc'=>"5V",
			'model_size'=>"43mm*21mm*11mm",
			'model_jiekou'=>"USB",
			'model_baud'=>"9600bps",
			'model_distance'=>"~10m",
			'class'=>"蓝牙2.0模块、安卓手机"

			);
		break;

	case 'HC-06-USB':
		$modeldata = array(
			'title'=>"汇承HC-06-USB转蓝牙串口模块 CSR无线透传电脑端PC端主从一体",
			'jiage'=>34.5,
			'src'=>"img/HC-06-USB.jpg",
			'model_vcc'=>"5V",
			'model_size'=>"43mm*21mm*11mm",
			'model_jiekou'=>"USB",
			'model_baud'=>"9600bps",
			'model_distance'=>"~10m",
			'class'=>"蓝牙2.0模块、安卓手机"

			);
		break;

	case 'HC-08-USB':
		$modeldata = array(
			'title'=>"汇承HC-08-USB转蓝牙串口模块无线透传电脑端PC端主从一体4.0",
			'jiage'=>34.5,
			'src'=>"img/HC-08-USB.jpg",
			'model_vcc'=>"5V",
			'model_size'=>"43mm*21mm*11mm",
			'model_jiekou'=>"USB",
			'model_baud'=>"9600bps",
			'model_distance'=>"~60m",
			'class'=>"HC-08、安卓手机、苹果手机"

			);
		break;

	case 'HC-11-USB':
		$modeldata = array(
			'title'=>"汇承HC-11-USB直插433MHz无线串口CC1101 中远距离PC端模块",
			'jiage'=>33.5,
			'src'=>"img/HC-11-USB.jpg",
			'model_vcc'=>"5V",
			'model_size'=>"43mm*21mm*11mm",
			'model_jiekou'=>"USB",
			'model_baud'=>"9600bps",
			'model_distance'=>"~40m",
			'class'=>"HC-11系列"

			);
		break;

	case 'HC-12-USB':
		$modeldata = array(
			'title'=>"汇承HC-12-USB直插电脑端433无线串口模块 超远距离1000米SI4463",
			'jiage'=>38.0,
			'src'=>"img/HC-12-USB.jpg",
			'model_vcc'=>"5V",
			'model_size'=>"43mm*21mm*11mm",
			'model_jiekou'=>"USB",
			'model_baud'=>"9600bps",
			'model_distance'=>"~1000m",
			'class'=>"HC-12系列"
			);
		break;
}

$url = "http://www.hchchchc.com".substr(__FILE__, strpos(__FILE__,'/Tpl'))."?model=".$model;
$jssdk = new JSSDK($url);
$signPackage = $jssdk->getSignPackage();

/*var_dump(redisData::Get('jsapi_ticket'));
var_dump($signPackage["nonceStr"]);
var_dump($signPackage["timestamp"]);
var_dump("http://www.hchchchc.com".substr(__FILE__, strpos(__FILE__,'/Tpl')));

var_dump($signPackage["rawString"]);
var_dump($signPackage["signature"]);*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title></title>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
	<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/product_arc.css">
</head>
<body>
	<div class="container">
		<div class="row" style="margin-top: 10px;">
			<div class="col-xs-12 col-md-6"><img src="<?php echo $modeldata['src']; ?>" class="img-responsive" alt="这是产品图片"></div>
				<div class="col-xs-12 col-md-6">
						<h4 style="margin-bottom: 5px;"><?php echo $modeldata['title']?></h4>
						<div class="product_sale">
							<span>价格：</span><span class="dollers">￥<?php echo $modeldata['jiage']; ?>元</span>
						</div>
						<table class="table table-striped">
							<tr>
								<td>尺寸：<?php echo $modeldata['model_size']; ?></td>
							</tr>
							<tr>
								<td>接口类型：<?php echo $modeldata['model_jiekou']; ?></td>
							</tr>
							<tr>
								<td>默认波特率：<?php echo $modeldata['model_baud']; ?></td>
							</tr>
							<tr>
								<td>通讯距离：<?php echo $modeldata['model_distance']; ?></td>
							</tr>
							<tr>
								<td>支持对象：<?php echo $modeldata['class']; ?></td>
							</tr>
						</table>
				</div>
		</div>
		<!-- <div class="row" style="padding-left: 15px;">
			<div id="shoucang" class="menu">
				<p class="text-center"><span class="glyphicon glyphicon-star-empty" style="font-size: 20px;"></span></p>
				<p class="text-center"><span>收藏</span></p>
			</div>
			<div id="fenxiang" class="menu">
				<p class="text-center"><span class="glyphicon glyphicon-send" style="font-size: 20px;"></span></p>
				<p class="text-center"><span>分享</span></p>
			</div>
			<div id="zixun" class="menu">
				<p class="text-center"><span class="glyphicon glyphicon-user" style="font-size: 20px;"></span></p>
				<p class="text-center"><span>咨询</span></p>
			</div>
		</div> -->
		<hr>
	</div>
</body>
<script>
    wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: '<?php echo $signPackage["timestamp"];?>',
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
    
    // 所有要调用的 API 都要加到这个列表中

        'scanQRCode', //调用微信扫码功能API
    ]
  });

wx.ready(function () {
    
  //调用微信扫码功能
  document.querySelector('#dianji').onclick = function () {
    wx.scanQRCode({
    needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
    success: function (res) {
    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
    $('input').val(result);
    }
    });
  };
});
</script>
</html>