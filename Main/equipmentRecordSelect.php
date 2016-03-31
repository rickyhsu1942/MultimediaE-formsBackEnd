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
	var equipmentLentRecord;

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
			type:"GET",
			url:"phpfunction/mysqlEquipmentSelect.php",
			data:{sqlCommandKey:CommandKey, sqlCommandValue:CommandValue},
		}).done(function(data) {
			console.log(data);
			property = JSON.parse(data);
			loadSqlEquipmentLentRecordData(1, "lent.formId", "equipmentNumber", <?php print("\"".$equipmentNumber."\""); ?>);
		});
	}

	//載入MySQL器材借用資料
	var loadSqlEquipmentLentRecordData = function(page,CommandOrder,CommandKey,CommandValue) {
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlEquipmentLentRecordSelect.php",
			data:{currentPage:page, sqlCommandKey:CommandKey, sqlCommandValue:CommandValue, sqlCommandOrder:CommandOrder},
		}).done(function(data) {
			console.log(data);
			$("#content").empty(); //清空原本的content
			equipmentLentRecord = JSON.parse(data);
			var totalPages = 0;
			var totalRows = 0;
			var tableHtml = "";

			if (property.length > 0) {
				tableHtml += "<table align='center' border=1 style='width:100%'>" + 
							 "<tr><td colspan='5' align='center'><img id='imageFile' src='equipmentImages/"+ property[0].imageName +"' style='width:200px;height:200px'></td></tr>" +
							 "<tr><th align='center'>器材名稱</th><th align='center'>借用單號</th><th align='center'>借用者</th><th align='center'>借用日期</th><th align='center'>歸還日期</th></tr>";
				for (var index in equipmentLentRecord) {
					totalPages = equipmentLentRecord[index].totalPages;
					totalRows = equipmentLentRecord[index].totalRows;
					tableHtml += "<tr><td align='left'><input disabled style='border:0px;background:transparent;' type='textfield' id='equipmentNumber' value='" + equipmentLentRecord[index].equipmentNumber + "' /></td>" + 
							     "<td align='left'><input disabled style='border:0px;background:transparent;' type='textfield' id='equipmentNumber' value='" + equipmentLentRecord[index].formId + "' /></td>" + 
								 "<td align='left'><input disabled style='border:0px;background:transparent;' type='textfield' id='student' value='" + equipmentLentRecord[index].studentId + "  " + equipmentLentRecord[index].name + "' /></td>" +
								 "<td align='left'><input disabled style='border:0px;background:transparent;' type='textfield' id='lendDate' value='" + equipmentLentRecord[index].lendDate + "' /></td>" +
								 "<td align='left'><input disabled style='border:0px;background:transparent;' type='textfield' id='returnDate' value='" + equipmentLentRecord[index].returnDate + "' /></td></tr>";
				}
				tableHtml += "</table>";
			}
			//頁數Table
			tableHtml += "<table border=0 align='center'><tr>"
			tableHtml += "<td><button onclick='searchClicked(1)'>第一頁</button></td>"
			tableHtml += "<td>頁數："
			for (var index = 1; index <= totalPages; index++) {
				if (index == page) {
					tableHtml += "<a onclick='searchClicked(" + index + ")'> [" + index + "] </a>"
				} else {
					tableHtml += "<a onclick='searchClicked(" + index + ")'> " + index + " </a>"
				}
			}
			tableHtml += "</td>"
			tableHtml += "<td><button onclick='searchClicked(" + totalPages + ")'>最後頁</button></td>"
			tableHtml += "</tr></table>"
			$("#content").append(tableHtml);
		});
	}

	function searchClicked(page) {
		loadSqlEquipmentData(page, "lent.formId", "equipmentNumber", <?php print("\"".$equipmentNumber."\""); ?>);
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
			<font style="font-size:20px; font-weight:bold;">器材歷史紀錄 - 查詢<font>
		</div><br>
		<div id="content">
			<font style="font-size:20px; font-weight:bold;">資料載入中...</font>
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