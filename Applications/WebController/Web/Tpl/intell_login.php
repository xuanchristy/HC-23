 <?php
use \Workerman\Protocols\Http;
require_once dirname(__DIR__).'/redisData.php';
require_once dirname(__DIR__).'/transToWxServer.php';
HTTP::sessionStart();

// 判断用户是否来自微信
if(!isset($_GET['code']) && !isset($_SESSION['username'])){

  echo "对不起，你访问的网页不存在！:(";
  HTTP::end();
}

// 得到用户的openid
$_SESSION['username'] = isset($_SESSION['username'])? $_SESSION['username'] : transToWxServer::getOAuth_openid($_GET['code']);

HTTP::sessionWriteClose();
/*require_once 'jssdk.php';

$url = "http://www.hchchchc.com".substr(__FILE__, strpos(__FILE__,'/Tpl'));
$jssdk = new JSSDK($url);
$signPackage = $jssdk->getSignPackage();*/


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
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/buttons.css">
  <link rel="stylesheet" href="css/intell.css">
  <link rel="stylesheet" href="css/messenger.css">
  <link rel="stylesheet" href="css/messenger-theme-block.css">
</head>
<body>
  <!-- 提示开始 -->
    <!-- <div id="help" style="display: block; z-index: 999px; background: #f00; overflow: visible;">
      <div style="height: 100%; width: 100%; background: #fff; float: left;"></div>
      <div style="height: 100%; width: 100%; background: #eee; float: left;"></div>
      <div style="height: 100%; width: 100%; background: #fee; float: left;"></div>
    </div> -->
  <!-- 提示结束 -->
<div class="container-fluid">
  <div class="row">
    <div class="input-group input-group-lg" id="pwd" style="margin-top: 100px;">
      <span class="input-group-addon">
        <span class="icon-key" style="font-size: 20px;"></span>
      </span>
      <input type="password" class="form-control" placeholder="密码">
    </div>
    <div><button id="denglu" type="button" data-loading-text="Loading..." class="button button-action button-pill button-jumbo denglu">登录</button></div>
    <div><a href="intell_zhuce.php" class="button button-highlight button-pill button-large denglu">注册</a></div>
    <p style="margin-top: 20px;"><a href="intell_bind.php">忘记密码</a>&nbsp;&nbsp;<a href="intell_altpwd.php">修改密码</a></p>
  </div>
  
</div>
</body>
<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="js/messenger.min.js"></script>
<script src="js/messenger-theme-future.js"></script>
<script>
  $(function(){

    // $("#help").width(window.innerWidth);
    // $("#help").height(window.innerHeight);

    // 初始化弹出框
    Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-top',
    theme: 'block'
    }

    // 登录
    $('button').click(function(){

    var username = "<?php echo $_SESSION['username']; ?>";
    var password = $('input').val();

    if(password.length !== 0){ //密码不为空

      // 异步检测用户名和密码是否正确
      $.ajax({

        url: "intell_login_admin.php",
        type: "POST",
        data: {'username': username, 'password': password},
        cache: false,
        success: function(data){

          if(data == "ok"){
            
            Messenger().post({
              message: "登入成功",
              type: "success",
              hideAfter: 1,
              hideOnNavigate: true
            });

            setTimeout("location.href = 'intell_list.php'", 1000);
          }
          if(data == "error"){

            Messenger().post({
              message: "密码错误，请重新输入！",
              type: "error",
              hideAfter: 2,
              hideOnNavigate: true
            });
          }
        },

        error: function(){

          alert("没有得到数据");
        }
      });

    }else{ // 密码为空

      Messenger().post({
        message: "密码不能为空！",
        type: "error",
        hideAfter: 2,
        hideOnNavigate: true
      });
    }
    });
  });
</script>
</html>
