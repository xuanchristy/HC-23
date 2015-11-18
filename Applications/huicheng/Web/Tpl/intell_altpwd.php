<?php 
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
      <input type="password" name="oldpwd" class="form-control" placeholder="原密码">
    </div>
    <div class="input-group input-group-lg" id="pwdcopy" style="margin-top: 20px;">
      <span class="input-group-addon">
        <span class="icon-key" style="font-size: 20px;"></span>
      </span>
      <input type="password" name="newpwd" class="form-control" placeholder="新密码 1~12位">
    </div>
    <div class="input-group input-group-lg" id="pwdcopy" style="margin-top: 20px;">
      <span class="input-group-addon">
        <span class="icon-copy" style="font-size: 20px;"></span>
      </span>
      <input type="password" name="copypwd" class="form-control" placeholder="重复密码">
    </div>
    <button type="button" class="button button-action button-pill button-large denglu" style="margin-top: 20px;">确定</button>
  </div>  
</div>
</body>
<script>
	$(function(){

		Messenger.options = {
	    extraClasses: 'messenger-fixed messenger-on-top',
	    theme: 'block'
	    }

	    $('button').click(function(){

	    	var oldpwd = $("input[name='oldpwd']").val();
	    	var newpwd = $("input[name='newpwd']").val();
	    	var copypwd = $("input[name='copypwd']").val();
	    	var username = "<?php echo $_SESSION['username']?>";

	    	if(oldpwd.length == 0 || newpwd.length == 0 || copypwd.length == 0){

	    		Messenger().post({
		        message: "密码不能为空！",
		        type: "error",
		        hideAfter: 2,
		        hideOnNavigate: true
		        });
		        return;
	    	}

	    	if(newpwd !== copypwd){

	    		Messenger().post({
		        message: "两次输入的密码不相同，请重新输入！",
		        type: "error",
		        hideAfter: 2,
		        hideOnNavigate: true
		        });
		        return;
	    	}

	    	$.ajax({

	    		url: "intell_altpwd_admin.php",
	    		type: "POST",
	    		data: {"username": username, "oldpwd": oldpwd, "newpwd": newpwd},
	    		cache: false,
	    		success: function(data){

	    			if(data == "ok"){

	    				Messenger().post({
			            message: "修改成功",
			            type: "success",
			            hideAfter: 2,
			            hideOnNavigate: true
			            });
			            location.href = "intell_login.php";

			            return;
	    			}

	    			if(data == "error"){

	    				Messenger().post({
			            message: "原密码不正确，请重新输入！",
			            type: "error",
			            hideAfter: 2,
			            hideOnNavigate: true
			            });

			            return;
	    			}
	    		},

	    		error: function(){

	    			// code...
	    		}
	    	});
	    });
	});
</script>
</html>