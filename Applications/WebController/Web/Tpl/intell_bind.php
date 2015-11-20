<?php
use \Workerman\Protocols\Http;
require_once dirname(__DIR__).'/redisData.php';
require_once 'jssdk.php';

$url = "http://www.hchchchc.com".substr(__FILE__, strpos(__FILE__,'/Tpl'));
$jssdk = new JSSDK($url);
$signPackage = $jssdk->getSignPackage();
HTTP::sessionStart();
// 判断用户是否登入
/*if(!isset($_SESSION['login'] && $_SESSION['login'] !== "1"){

  // code...
}*/


/*******************测试************************/
// var_dump(redisData::Get('jsapi_ticket'));
// var_dump($signPackage["nonceStr"]);
// var_dump($signPackage["timestamp"]);
// var_dump("http://www.hchchchc.com".substr(__FILE__, strpos(__FILE__,'/Tpl')));

// var_dump($signPackage["rawString"]);
// var_dump($signPackage["signature"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title></title>
  <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/buttons.css">
  <link rel="stylesheet" href="css/intell.css">
  <link rel="stylesheet" href="css/messenger.css">
  <link rel="stylesheet" href="css/messenger-theme-block.css">
  <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
  <script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  <script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="js/messenger.min.js"></script>
  <script src="js/messenger-theme-future.js"></script>
  <script>
  $(function(){

    Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-top',
    theme: 'block'
    }

    /**
     * 检测MACID格式函数
     * @return Boolean
     */
    function isMacid(macid){

      // 检测XX:XX:XX类型macid的正则表达式
      // var macReg = /^([a-zA-Z0-9]){2}:([a-zA-Z0-9]){2}:([a-zA-Z0-9]){2}$/;

      // 检测XXXXXXXXXX类型macid的正则表达式
      var macReg = /^([a-zA-Z0-9]){12}$/;
      var result = macReg.test(macid);
      return result;
    };


    $('button').click(function(){

      // 设备macid
      var macid = $('#macid').val();
      var macidnum = macid.length;
      // 用户微信openid
      var username = "<?php echo $_SESSION['username'];?>";
      // 设备名称
      var macname = $('#macname').val().length == 0?"自定义设备" : $('#macname').val();

      if(macidnum == 0){ //macid为空

        Messenger().post({
          message: "MAC ID不能为空，请重新输入！",
          type: "error",
          hideAfter: 2,
          hideOnNavigate: true
        });

        return;
      }

      if(!isMacid(macid) || (macidnum > 14)){ // macid格式不正确

        Messenger().post({
          message: "MAC ID格式不正确，请重新输入！",
          type: "error",
          hideAfter: 2,
          hideOnNavigate: true
        });

        return;
      }

      if(macname.length > 12){

        Messenger().post({
          message: "设备名称格式不正确，请重新输入！",
          type: "error",
          hideAfter: 2,
          hideOnNavigate: true
        });

        return;
      }

      $.ajax({

        url: "intell_bind_admin.php",
        type: "POST",
        data: {'username': username, 'macname': macname, 'macid': macid},
        cache: false,
        success: function(data){

          if(data == "101"){

            Messenger().post({
              message: "绑定成功！",
              type: "success",
              hideAfter: 2,
              hideOnNavigate: true
            });

            setTimeout("location.href = 'intell_list.php'", 2000);

            return;
          }

          if(data == "001"){

            Messenger().post({
              message: "不能重复绑定！",
              type: "warning",
              hideAfter: 2,
              hideOnNavigate: true
            });

            return;
          }

          if(data == "102"){

            Messenger().post({
              message: "绑定失败，请稍后重试！",
              type: "error",
              hideAfter: 2,
              hideOnNavigate: true
            });

            return;
          }
        },
        error: function(){

          Messenger().post({
            message: "页面请求超时！",
            type: "error",
            hideAfter: 2,
            hideOnNavigate: true
          });

          return;
        }
      });
    });
  });
</script>
</head>
<body>
<div class="container-fluid">
  <div class="row" style="margin-left: 20px; margin-right: 20px;">
    <div class="input-group input-group-lg" style="margin-top: 100px;">
      <input type="text" class="form-control" id="macname" placeholder="名称 最大12位字符" style="border-radius: 5px;">
    </div>
    <div class="input-group input-group-lg" style="margin-top: 20px;">
      <input type="text" class="form-control" id="macid" placeholder="MAC ID">
      <span class="input-group-addon" id="saoma">
        <span class="glyphicon glyphicon-qrcode" style="font-size: 20px;"></span>
      </span>
    </div>
    <button id="bind" type="button" class="button button-caution button-pill button-large denglu" style="margin-top: 20px;">绑定</button>
  </div>  
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

wx.ready(function(){
    
  document.querySelector('#saoma').onclick = function () {
    wx.scanQRCode({
    needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
    scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
    success: function (res) {
    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
    $('#macid').val(result);
    }
    });
  };
});
</script>
</html>
<?php
HTTP::sessionWriteClose();
?>
