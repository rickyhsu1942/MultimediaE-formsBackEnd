<?php
	session_start();
	if (!$_SESSION['s_user']) {
		header("Location:/backend/login.php");
	}
	//For PHP5.3 
	/* 
	if (!session_is_registered("s_user")) {
		header("Location:/backend/login.php");
	}
	*/

	//接收傳來的參數
	if ($_POST['sqlEquipmentNumber']) {
		$equipmentNumber = $_POST['sqlEquipmentNumber'];
	}
?>
<!-- script -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}


	//修改MySQL
	var UpdateSqlAccountData = function(){
		if (document.getElementById("newPassword").value == "" || document.getElementById("reTypeNewPassword").value == "") {
			alert("有欄位空白");
			return;
		} else if (document.getElementById("reTypeNewPassword").value != document.getElementById("newPassword").value) {
			alert("密碼與確認密碼不符");
			return;
		}

		var updateJson = {"account" : <?php echo "'".$_SESSION['s_user']."'" ?>,
						  "oldPassword" : document.getElementById("oldPassword").value,
						  "newPassword" : document.getElementById("newPassword").value};
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlAccountUpdate.php",
			data:{sqlCommandUpdateData:updateJson}
		}).done(function(data) {
			if (data == 1) {
				location.href = 'expiredLentUpdateDelete.php'
				alert("修改成功");
			} else {
				alert("修改失敗：" + data);
			}
		});
	}
	
	//當確認密碼更動時
	function reTypeNewPasswordChanged() {
		if (document.getElementById("newPassword").value != "" && document.getElementById("reTypeNewPassword").value != "") {
			if (document.getElementById("reTypeNewPassword").value != document.getElementById("newPassword").value) {
				document.getElementById("warningTip").value = "密碼與確認密碼不符!!";
			} else {
				document.getElementById("warningTip").value = "";
			}
		}
	}

</script>
<!DOCTYPE HTML>
<html>
<head>
<title>多媒體電子表單</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!--  jquery plguin -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<!--start manu -->
<link href="css/flexy-menu.css" rel="stylesheet">
<script type="text/javascript" src="js/flexy-menu.js"></script>
<script type="text/javascript">$(document).ready(function(){$(".flexy-menu").flexymenu({speed: 400,type: "horizontal",align: "right"});});</script>
<!--start slider -->
	<script src="js/responsiveslides.min.js"></script>
	  <script>
	    // You can also use "$(window).load(function() {"
		    $(function () {
			      // Slideshow 1
			      $("#slider1").responsiveSlides({
			        maxwidth: 1600,
			        speed: 600
			      });
			});
	  </script>
</head> 
<body>
<script src="js/classie.js"></script>
<!-- start header -->
<div class="header">
<div class="wrap">
	<div class="logo">
		<a href=""><img src="images/logo.jpg" alt="" /></a>
	</div>
	<div class="h_right">
		<table style="width:20%" align="right">
			<tr>
				<td><font color="orange" size="4">Hi,<?php echo $_SESSION['s_user'] ?></font></td>
				<td align="right"><input type="submit" class="button" name="logout" onclick="logout()" value="登出"/></td>
			</tr>
		</table>
		<!-- start nav-->
		<ul class="flexy-menu thick orange">
			<script src="js/headerHtml.js"></script>
		</ul>
	</div>
	<div class="clear"></div>
</div>
</div>

<!-- start main -->
<div class="main_bg">
<div class="wrap">
 <div class="main">
 	<!-- start main_content -->
	<div class="details">
		<div id="contentTitle" align="center">
			<font style="font-size:20px; font-weight:bold;">帳號管理 - 修改密碼<font>
		</div><br>
		<div id="content">
			<table align='center' id="contentTable" border=1 style='width:40%'>
				<tr>
					<th align='left'>帳號</th>
					<td>
						<?php echo $_SESSION['s_user'] ?>
					</td>
				</tr>
				<tr>
					<th align='left'>舊密碼</th>
					<td>
						<input type='password' id='oldPassword' />
					</td>
				</tr>
				<tr>
					<th align='left'>新密碼</th>
					<td>
						<input type='password' id='newPassword' />
					</td>
				</tr>
				<tr>
					<th align='left'>新密碼確認</th>
					<td>
						<input type='password' id='reTypeNewPassword' onchange='reTypeNewPasswordChanged()' />
						<input type='label' id='warningTip' style='border:0px;background:transparent;width:120px;display:inline;color:red;'  value=''/>
					</td>
				</tr>
				<tr>
					<td align='center' colspan='2'>
						<input type='button' value='送出' onclick='UpdateSqlAccountData()' />
					</td>
				</tr>
			</table>
		</div>
 	</div>
	<div class="clear"></div>
	<!-- end main_content -->
 </div>
</div>
</div>
<!-- end main -->


<!-- start footer -->
<div class="footer top">
<div class="wrap">
<div class="footer_main">
	<div class="copy">
	</div>
	<div class="clear"></div>
</div>
</div>
</div>
<!-- end footer -->
</body>
</html>