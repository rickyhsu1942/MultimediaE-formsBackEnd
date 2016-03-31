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
	//全域變數
	var property;
	var imageName;

	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}

	//網頁載入完成時
	$(document).on("ready",function() {
		loadSqlEquipmentData("equipmentNumber",<?php print("\"".$equipmentNumber."\""); ?>);
	});

	//載入MySQL器材型錄資料
	var loadSqlEquipmentData = function(CommandKey,CommandValue) {
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlOtherEquipmentSelect.php",
			data:{sqlCommandKey:CommandKey, sqlCommandValue:CommandValue},
		}).done(function(data) {
			console.log(data);
			$("#content").empty(); //清空原本的content
			property = JSON.parse(data);
			var tableHtml = "";
			if (property.length > 0) {
				tableHtml += "<table align='center' border=1 style='width:50%'>" + 
							 "<tr><td colspan='2' align='center'><img id='imageFile' src='equipmentImages/"+ property[0].imageName +"' style='width:200px;height:200px'></td></tr>" +
							 "<tr><th align='right'>器材編號：</th><td align='left'><input disabled style='border:0px;background:transparent;' type='textfield' id='equipmentNumber' value='" + property[0].equipmentNumber + "' /></td></tr>" +
							 "<tr><th align='right'>器材數量：</th><td align='left'><input type='textfield' id='equipmentQuantity' value='" + property[0].equipmentQuantity + "' /></td></tr>" +
							 "<tr><th align='right'>借出數量：</th><td align='left'><input type='textfield' id='lentQuantity' value='" + property[0].lentQuantity + "' /></td></tr>" +
							 "<tr><th align='right'>存放地點：</th><td align='left'><input type='textfield' id='location' value='" + property[0].location + "' /></td></tr>" +
							 "<tr><th>上傳圖片：</th><td><input type='file' name='fileToUpload' id='fileToUpload'/><input type='button' value='上傳檔案' onclick='uploadImage()' />" + 
							 "<input disabled style='border:0px;background:transparent;color:red;' type='textfield' id='fileStatus' value='尚未上傳' /></td></tr>" +
							 "<tr><td align='center' colspan='2'><input type='button' value='確認' onclick='UpdateSqlOtherEquipmentData()' /></td></tr></table>";
			}
			$("#content").append(tableHtml);
		});
	}

	//修改MySQL器材型錄資料
	var UpdateSqlOtherEquipmentData = function(){
		var updateJson = {"equipmentNumber" : document.getElementById("equipmentNumber").value,
						  "equipmentQuantity" :  document.getElementById("equipmentQuantity").value,
						  "lentQuantity" :  document.getElementById("lentQuantity").value,
						  "location" :  document.getElementById("location").value,
						  "imageName" : imageName};
		
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlOtherEquipmentUpdate.php",
			data:{sqlCommandUpdateData:updateJson}
		}).done(function(data) {
			console.log(data);
			if (data == 1) {
				location.href = 'otherEquipmentUpdateDelete.php'
				alert("修改成功");
			} else {
				alert("修改失敗" + data);
			}
		});
	}

	//上傳圖片
	var uploadImage = function () {
    	var file_data = $('#fileToUpload').prop('files')[0];   
    	var form_data = new FormData();                  
    	form_data.append('fileToUpload', file_data);  
		$.ajax({
			url:"phpfunction/fileUpload.php",
   			dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                     
            type: 'post',
			success:function (data) {
				console.log(data);
				var responseData = JSON.parse(data);
				imageName = "";

				if (typeof(responseData[0].error) != "undefined") {
					if (responseData[0].error != "") {
						alert(responseData[0].error);
						$("#fileStatus").val("上傳失敗");
						$("#fileStatus").css("color","red");
					} else {
						alert(responseData[0].msg);
						$("#fileStatus").val("上傳成功");
						$("#fileStatus").css("color","blue");
						$("#imageFile").attr("src","equipmentImages/" + responseData[0].fileName);
						imageName = responseData[0].fileName;
					}
				}
			},
			error:function (data, status, error) {
				alert(error);
				$("#fileStatus").val("上傳失敗");
				$("#fileStatus").css("color","red");
			}
		})
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
			<font style="font-size:20px; font-weight:bold;">無財產器材型錄表單 - 修改<font>
		</div><br>
		<div id="content"></div>
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