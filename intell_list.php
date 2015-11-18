<?php 
require_once dirname(__DIR__).'/redisData.php';
require_once dirname(dirname(dirname(dirname(__DIR__)))).'/Workerman/Autoloader.php';
\Workerman\Autoloader::setRootPath(__DIR__.'/../../');
use \GatewayWorker\Lib\Db;
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
  	<!-- 字体样式css -->
  	<link rel="stylesheet" href="css/font-awesome.min.css">
  	<!-- 按键样式css -->
  	<link rel="stylesheet" href="css/buttons.css">
  	<!-- 开关样式css -->
	<link rel="stylesheet" href="css/bootstrap-switch.css">
	<link rel="stylesheet" href="css/intell_list.css">
	<link rel="stylesheet" href="css/highlight.css">
	<link rel="stylesheet" href="css/intell.css">
	<link rel="stylesheet" href="css/messenger.css">
  	<link rel="stylesheet" href="css/messenger-theme-block.css">

</head>
<body style="margin: 0; padding: 0;">

	<!-- 修改名称模态框开始 -->
<div class="modal fade" id="macnameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   	<div class="modal-dialog">
      	<div class="modal-content">
         	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
            	<h4 class="modal-title" id="myModalLabel">修改设备名称</h4>
         	</div>
        	<div class="modal-body"><input type="text" class="form-control" id="macnameinput" placeholder="自定义名称"></div>
        	<div class="modal-footer" style="text-align: center;">
        		<button type="button" name="isrename" class="button button-action button-pill">确定</button>&nbsp;
            	<button type="button" class="button button-action button-caution button-pill" data-dismiss="modal">取消</button>
        	</div>
    	</div>
 	</div>
</div>
	<!-- 修改名称模态框结束 -->

	<!-- 传输数据模态框开始 -->
<div class="modal fade" id="tranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   	<div class="modal-dialog">
      	<div class="modal-content">
         	<div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
            	<h4 class="modal-title" id="myModalLabel"><span id="trancemacname"></span></h4>
         	</div>
        	<div class="modal-body"><textarea class="form-control" id="tranceinput" rows="3"></textarea></div>
        	<div class="modal-footer" style="text-align: center;">
        		<button type="button" name="istrance" class="button button-action button-pill">发送</button>&nbsp;
            	<button type="button" class="button button-action button-caution button-pill" data-dismiss="modal">取消</button>
        	</div>
    	</div>
 	</div>
</div>
	<!-- 传输数据模态框结束 -->

	<!-- 解除绑定模态框开始 -->
<div class="modal fade" id="unbindModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   	<div class="modal-dialog">
      	<div class="modal-content">
         	<div class="modal-header" style="text-align: center;">
            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
            	<h4 class="modal-title" id="myModalLabel">解除绑定设备</h4>
         	</div>
        	<div class="modal-body" style="text-align: center;"><span id="unbindmacname" style="font-size: 18px;"></span></div>
        	<div class="modal-footer" style="text-align: center;">
        		<button type="button" name="isunbind" class="button button-action button-pill">确定</button>&nbsp;
            	<button type="button" class="button button-action button-caution button-pill" data-dismiss="modal">取消</button>
        	</div>
    	</div>
 	</div>
</div>
	<!-- 解除绑定模态框结束 -->
	<ul class="list-group">
		<?php
			$username = $_SESSION['username'];
			$connectHC = Db::instance('ConnectDb');
			$result = $connectHC->row("SELECT macid FROM `wxserver` WHERE openid='$username'");
			// 得到绑定的MACID数组
			$getmacidlist = explode('/', $result['macid'],-1);
			// 得到绑定的MACID的clientid数组
			/*$getmacidclientlist = array();
			foreach ($getmacidlist as $value) {		
				$result = $connectHC->row("SELECT clientid FROM `wifiserver` WHERE macid='$value'");
				$getmacidclientlist[$value] = $result['clientid'];
				};*/
			// 得到绑定MACID的名称数组
			$getmacnamelist = redisData::hMGet($username, $getmacidlist);
			foreach ($getmacnamelist as $key => $value) {
				# code...
			
			/*$getdata = redisData::allarray($getmacidclientlist, $getmacnamelist);
			var_export($getdata);*/
			?>
	  <li class="list-group-item">
	    <p style="line-height: 40px;" id="<?php echo $key; ?>"><span><?php echo $value; ?></span></p>
		   <div class="kaiguan">
			<button class="button button-box button-primary button-small" name="rename"><i class="glyphicon glyphicon-pencil span-button"></i></button>
	    	<button class="button button-box button-primary button-small" name="trance"><i class="glyphicon glyphicon-globe span-button"></i></button>
	    	<button class="button button-box button-primary button-small" name="unbind"><i class="glyphicon glyphicon-trash span-button"></i></button>
		   	<input type="checkbox" name="my-checkbox" checked/></div>
	  </li>
	  <?php }?>
	</ul>
	<div class="container">
		<div class="row">
			<a href="intell_bind.php" class="button button-action button-pill btn-block"><span class="glyphicon glyphicon-plus"></span>绑定BIND</a>
		</div>		
	</div>
	<div class="container-fluid" style="height: 60px; background: rgba(228,228,228,0.5); box-shadow: 0 -2px 5px 5px #ccc; position: relative; bottom: 0; ">
		
	</div>	
</body>
<script src="http://cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="js/bootstrap-switch.js"></script>
<script src="js/highlight.js"></script>
<script src="js/messenger.min.js"></script>
<script src="js/messenger-theme-future.js"></script>
<script>
$(function(){

	var username = "<?php echo $_SESSION['username']; ?>";
	var macname;
	var macid;

	$("input[name = 'my-checkbox']").bootstrapSwitch({

		onText: "关",
		offText: "开",
		onColor: "danger",
		offColor: "success"
	});

	Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-top',
    theme: 'block'
    }

	// 自定义名称
	$("button[name='rename']").click(function(){

		macname = $(this).parent().siblings("p").children().text();
		macid = $(this).parent().siblings("p").attr("id");
		$('#macnameinput').val(macname);
		$('#macnameModal').modal('show');
	});

	$("button[name='isrename']").click(function(){

		var newmacname = $('#macnameinput').val();
		$('#macnameModal').modal('hide');
		// 同步修改设备名称
		$.ajax({

			url: "intell_list_rename.php",
			type: "POST",
			data: {'username': username, 'macid': macid, 'newmacname': newmacname},
			cache: false,
			async: false,
			success: function(data){

				if(data == "ok"){

					// location.href = "intell_list.php";
					Messenger().post({
		              message: "修改成功！",
		              type: "success",
		              hideAfter: 2,
		              hideOnNavigate: true,		              
		            });

		            setTimeout("location.href = 'intell_list.php'", 2000);
				}
			},
			error: function(){

				// code...
			}
		});
	});

	// 传输数据
	$("button[name='trance']").click(function(){

		var macname = $(this).parent().siblings("p").children().text();
		macid = $(this).parent().siblings("p").attr("id");
		$("#trancemacname").text(macname);
		$("#tranceinput").val("");
		$("#tranceModal").modal('show');
	});

	$("button[name='istrance']").click(function(){

		var texttrance = $("#tranceinput").val();
		$.ajax({

			url: "intell_list_trance.php",
			type: "POST",
			cache: false,
			async: false,
			data: {'username': username, 'macid': macid, 'text': texttrance},
			success: function(data){

				if(data == "101"){

					Messenger().post({
		              message: "发送成功！",
		              type: "success",
		              hideAfter: 1,
		              hideOnNavigate: true,		              
		            });
				}

				if(data == "102"){

					Messenger().post({
		              message: "发送失败，设备与服务器断开",
		              type: "error",
		              hideAfter: 1,
		              hideOnNavigate: true,		              
		            });
				}
			},
			error: function(){

				// code...
			}
		});
	});

	// 解除绑定
	$("button[name='unbind']").click(function(){

		var macname = $(this).parent().siblings("p").children().text();
		macid = $(this).parent().siblings("p").attr("id");
		$('#unbindmacname').text(macname);
		$('#unbindModal').modal('show');
		
	});

	$("button[name='isunbind']").click(function(){

		// 同步解除绑定设备
		$.ajax({

			url: "intell_list_unbind.php",
			type: "POST",
			cache: false,
			async: false,
			data: {'username': username, 'macid': macid},
			success: function(data){

				if(data == "ok"){

					Messenger().post({
		              message: "解绑成功！",
		              type: "success",
		              hideAfter: 2,
		              hideOnNavigate: true,		              
		            });

		            setTimeout("location.href = 'intell_list.php'", 2000);
				}

				if(data == "error"){

					Messenger().post({
		              message: "解绑失败，请稍后重试！",
		              type: "error",
		              hideAfter: 2,
		              hideOnNavigate: true,		              
		            });

		            setTimeout("location.href = 'intell_list.php'", 2000);
				}
			},
			error: function(){

				Messenger().post({
		          message: "操作超时，稍后重试",
		          type: "error",
		          hideAfter: 2,
		          hideOnNavigate: true,		              
		        });

		        setTimeout("location.href = 'intell_list.php'", 2000);
			}
		});
	});
});
</script>
</html>
<?php 
HTTP::sessionWriteClose();
?>