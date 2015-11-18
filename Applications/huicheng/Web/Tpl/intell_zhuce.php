<?php
require_once dirname(__DIR__).'/redisData.php';
use \Workerman\Protocols\Http;
HTTP::sessionStart();
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
  <script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
  <script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="js/messenger.min.js"></script>
  <script src="js/messenger-theme-future.js"></script>
</head>
<body>
<div class="container-fluid">
  <div class="row" style="margin-left: 20px; margin-right: 20px;">
    

    <div class="input-group input-group-lg" id="pwd" style="margin-top: 100px;">
      <span class="input-group-addon">
        <span class="icon-key" style="font-size: 20px;"></span>
      </span>
      <input type="password" name="once" class="form-control" placeholder="密码 1~12位">
    </div>
    <div class="input-group input-group-lg" id="pwdcopy" style="margin-top: 20px;">
      <span class="input-group-addon">
        <span class="icon-copy" style="font-size: 20px;"></span>
      </span>
      <input type="password" name="second" class="form-control" placeholder="重复密码">
    </div>
    <div class="input-group input-group-lg" id="pwdcopy" style="margin-top: 20px;">
      <span class="input-group-addon">
        <span class="icon-envelope" style="font-size: 20px;"></span>
      </span>
      <input type="text" name="email" class="form-control" placeholder="邮箱">
    </div>
    <button type="button" class="button button-action button-pill button-large denglu" style="margin-top: 20px;">提交</button>
  </div>
  
</div>
</body>
<script>
  $(function(){

    Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-top',
    theme: 'block'
    };

    // 检测邮箱格式是否正确
    function isEmail(emailadd){

      var emailReg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
      if(emailReg.test(emailadd)){

        return true;

      }else{

        return false;

      }
    };

    function isPassword(pwd){

      var pwdReg = /([a-zA-Z0-9_]){1,12}/;
      var result = pwdReg.test(pwd);
      return result;
    }

    $('button').click(function(){

      var oncepwd = $("input[name='once']").val();
      var secondpwd = $("input[name='second']").val();
      var emailadd = $("input[name='email']").val();
      var username = "<?php echo $_SESSION['username']; ?>";

      // 判断必须项是否为空
      if(oncepwd.length == 0 || secondpwd.length == 0 || emailadd.length == 0){

        Messenger().post({
        message: "密码、邮箱不能为空,请重新输入！",
        type: "error",
        hideAfter: 2,
        hideOnNavigate: true
        });
        return;
      }

      // 检测邮箱格式是否正确
      if(!isEmail(emailadd) || (oncepwd.length > 12)){

        Messenger().post({
        message: "密码、邮箱格式不正确,请重新输入！",
        type: "error",
        hideAfter: 2,
        hideOnNavigate: true
        });
        return;
      }

      // 两次输入密码是否相同
      if(oncepwd !== secondpwd){

        Messenger().post({
        message: "两次输入密码不正确，请重新输入！",
        type: "error",
        hideAfter: 2,
        hideOnNavigate: true
        });
        return;
      }
      
      $.ajax({

        url: "intell_zhuce_admin.php",
        type: "POST",
        data: {'username': username, 'password': oncepwd},
        cache: false,
        success: function(data){

          if(data == "ok"){

            Messenger().post({
            message: "注册成功",
            type: "success",
            hideAfter: 2,
            hideOnNavigate: true
            });
            
            setTimeout("location.href = 'intell_login.php'", 2000);

            return;
          }
          if(data == "error"){

            Messenger().post({
            message: "不能重复注册",
            type: "warning",
            hideAfter: 2,
            hideOnNavigate: true
            });

            return;
          }
        },

        error: function(){

          alert("注册失败，请稍后重试！");
        }
      });
      });
    });
</script>
<?php 
HTTP::sessionWriteClose();
?>
</html>
