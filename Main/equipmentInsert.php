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
	var propertyKind;
	var propertyState;
	var imageName;

	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}

	//網頁載入完成時
	$(document).on("ready",function() {
		loadSqlEquipmentKindData();
	});


	//載入MySQL器材種類資料
	var loadSqlEquipmentKindData = function() {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlEquipmentKindSelect.php",
		}).done(function(data) {
			console.log(data);
			propertyKind = JSON.parse(data);
			loadSqlEquipmentStateData();
		});
	}

	//載入MySQL器材狀態資料並顯示新增表格
	var loadSqlEquipmentStateData = function() {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlEquipmentStateSelect.php",
		}).done(function(data) {
			console.log(data);
			$("#content").empty(); //清空原本的content
			propertyState = JSON.parse(data);
			var tableHtml = "";
			if (propertyState.length > 0) {
				tableHtml += "<table align='center' border=1 style='width:75%'>" + 
							 "<tr><td colspan='4' align='center'><img id='imageFile' src='equipmentImages/' style='width:200px;height:200px'></td></tr>" +
							 "<tr><th align='right'>商品名稱：</th><td align='left'><input type='textfield' id='equipmentNumber' value='' /></td>" +
							 "<th align='right'>廠牌型別：</th><td align='left'><input type='textfield' id='company' value='' /></td></tr>" + 
							 "<tr><th align='right' >財產編號：</th><td align='left'><input  type='textfield' id='propertyNumber' value='' /></td>" +
							 "<th align='right' >財產名稱：</th><td align='left'><input type='textfield' id='propertyName' value='' /></td></tr>" +
							 "<tr><th align='right'>狀　　態：</th><td align='left'><select id='stateSelect'>";
				for (var index in propertyState) {
					tableHtml += "<option>" + propertyState[index].stateName + "</option>"
				}
				tableHtml += "</select></td>" +
							 "<th align='right'>種　　類：</th><td align='left'><select id='kindSelect'>";
				for (var index in propertyKind) {
					tableHtml += "<option>" + propertyKind[index].kindName + "</option>"
				}
				tableHtml += "</select></td></tr>" +
							 "<tr><th align='right'>保存年限(月)：</th><td align='left'><input type='textfield' id='deadLine' value='' />個月</td>" +
							 "<th align='right'>取得日期：</th><td align='left'><input type='textfield' id='date' value='' />(格式：104.01.01)</td></tr>" +
							 "<tr><th align='right'>成　　本：</th><td align='left'><input type='textfield' id='costs' value='' /></td>" +
							 "<th align='right'>現　　值：</th><td align='left'><input type='textfield' id='presentValue' value='' /></td></tr>" +
							 "<tr><th align='right'>保管人：</th><td align='left'><input type='textfield' id='custodian' value='' /></td>" +
							 "<th align='right'>使用人：</th><td align='left'><input type='textfield' id='user' value='' /></td></tr>" +
							 "<tr><th align='right'>存放地點：</th><td align='left'><input type='textfield' id='location' value='' /></td>" +
							 "<th>上傳圖片：</th><td><input type='file' name='fileToUpload' id='fileToUpload'/><input type='button' value='上傳檔案' onclick='uploadImage()' />" + 
							 "<input disabled style='border:0px;background:transparent;color:red;' type='textfield' id='fileStatus' value='尚未上傳' /></td></tr>" +
							 "<tr><td align='center' colspan='4'><input type='button' value='新增' onclick='InsertSqlEquipmentData()' /></td></tr></table>";
			}
			$("#content").append(tableHtml);
		});
	}

	//新增MySQL器材型錄資料
	var InsertSqlEquipmentData = function(){
		var insertJson = {"equipmentNumber" : document.getElementById("equipmentNumber").value,
						 "propertyNumber" :  document.getElementById("propertyNumber").value,
						 "equipmentState" :  document.getElementById("stateSelect").selectedIndex + 1,
						 "location" :  document.getElementById("location").value,
						 "deadLine" :  document.getElementById("deadLine").value,
						 "custodian" :  document.getElementById("custodian").value,
						 "company" :  document.getElementById("company").value,
						 "propertyName" :  document.getElementById("propertyName").value,
						 "equipmentKind" :  document.getElementById("kindSelect").selectedIndex + 1,
						 "date" :  document.getElementById("date").value,
						 "costs" :  document.getElementById("costs").value,
						 "presentValue" :  document.getElementById("presentValue").value,
						 "user" :  document.getElementById("user").value,
						 "imageName" : imageName};
		
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlEquipmentInsert.php",
			data:{sqlCommandInsertData:insertJson}
		}).done(function(data) {
			console.log(data);
			if (data == 1) {
				location.href = 'equipmentSelect.php'
				alert("新增成功");
			} else {
				alert("新增失敗" + data);
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
			<font style="font-size:20px; font-weight:bold;">器材型錄表單 - 新增<font>
		</div><br>
		<div id="content">
			<font style="font-size:20px; font-weight:bold;">資料載入中...<font>
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