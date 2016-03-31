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
?>
<!-- script -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
	//全域變數
	var general;

	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}

	//網頁載入完成時
	$(document).on("ready",function() {
		loadSqlGeneralData(1,"general.formId");
	});

	//載入MySQL一般借用
	var loadSqlGeneralData = function(page,CommandOrder,CommandKey,CommandValue) {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlGeneralSelectForSearch.php",
			data:{currentPage:page, sqlCommandLentKind:"A", sqlCommandKey:CommandKey, sqlCommandValue:CommandValue, sqlCommandOrder:CommandOrder},
		}).done(function(data) {
			console.log(data);
			$("#content").empty(); //清空原本的content
			general = JSON.parse(data);
			var totalPages = 0;
			var totalRows = 0;
			var number = (page - 1) * 15;
			var tableHtml = "";
			tableHtml = "<table border=1 style='width:100%'><tr><th>勾選</th><th>單號</th><th>器材編號</th><th>借用者學號姓名</th><th>借用日期</th><th>功能</th></tr>";
			for (var index in general) {
				number ++;
				totalPages = general[index].totalPages;
				totalRows = general[index].totalRows;
				tableHtml += "<tr><td align=center><input type='checkbox' name='checkboxName' id='checkbox" + index + "'/></td>" + 
						     "<td>" + general[index].formId + "</td>" + 
						     "<td>" + general[index].equipmentNumber + "</td>" + 
						     "<td>" + general[index].studentId + "，" + general[index].name + "</td>" + 
						     "<td>" + general[index].lendDate + "</td>" + 
						     "<td align=center><form action='generalUpdate.php' method='post'>" +
						     "<input type='submit' value='修改' />" +
						     "<input type='hidden' name='sqlFormId' value='" + general[index].formId + "' /></form>";
				if (general[index].returnUndertaker == "" || general[index].returnUndertaker == null) {
					tableHtml += "<form action='generalReturnUpdate.php' method='post'>" +
						   		 "<input type='submit' value='歸還' />" +
						     	 "<input type='hidden' name='sqlFormId' value='" + general[index].formId + "' /></form>";
				} else {
					tableHtml += "<font color='blue'>已歸還</font>";
				}
				tableHtml += "</td></tr>";
			}
			tableHtml += "</table>"
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

	//刪除MySQL
	var deleteSqlLentData = function(lentId, formId, equipmentNumber, equipmentQuantity, returnUndertaker) {
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlLentDelete.php",
			data:{sqlLentId:lentId, 
				  sqlFormId:formId, 
				  sqlEquipmentNumber:equipmentNumber,
				  sqlEquipmentQuantity:equipmentQuantity,
				  sqlReturnUndertaker:returnUndertaker},
		}).done(function(data) {
			var result = data;
			console.log(result);
			searchClicked(1);
		});
	}

	//搜尋
	function searchClicked(page) {
		var orderSelect =  document.getElementById("orderSelect");
		var searchTextfield = document.getElementById("searchTextfield");
		var searchSelect =  document.getElementById("searchSelect");
		document.getElementById("checkboxAll").checked = false;
		loadSqlGeneralData(page, orderSelect.options[orderSelect.selectedIndex].id,searchSelect.options[searchSelect.selectedIndex].id,searchTextfield.value);
	}

	//全部勾選
	function allCheckboxCheckedClicked(obj,name) {
		var checkboxs = document.getElementsByName(name);
		for (var i = checkboxs.length - 1; i >= 0; i--) {
			checkboxs[i].checked = obj.checked;
		}
	}

	//刪除
	function deleteClicked() {
		var checkboxs = document.getElementsByName("checkboxName");
		for (var i = 0; i < checkboxs.length; i++) {
			if (checkboxs[i].checked) {
				deleteSqlLentData(general[i].lentId, general[i].formId, general[i].equipmentNumber, general[i].equipmentQuantity, general[i].returnUndertaker);
			}

		}
	}
 
	//修改
	function updateClicked(buttonIndex) {
		//宣告<form>
		var theForm;
		theForm = document.createElement("from");
		theForm.action = "equipmentUpdate.php";
		theForm.method = "post"
		//宣告POST參數
		var inputData;
		inputData = document.createElement("input");
		inputData.type = "hidden";
		inputData.name = "sqlEquipmentNumber";
		inputData.value = general[buttonIndex].formId;
		//將宣告參數放入<form>中
		theForm.appendChild(inputData);
		document.getElementById("hiddenFormContainer").appendChild(theForm);
		//開始POST出去
		theForm.submit();
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
			<font style="font-size:20px; font-weight:bold;">器材借用表單(一般借用表單) - 修改/刪除<font>
		</div><br>
		<div id="search">
			<table cellpadding="0px" cellspacing="0px" border=0 style="width:100%">
				<tr>
					<td align="left">
						<font>依</font>
						<select id="orderSelect" onchange="searchClicked(1)">
							<option id="general.formId,lentId">單號</option>
							<option id="equipmentNumber,general.formId,lentId">器材編號</option>
							<option id="studentId,general.formId,lentId">借用者學號</option>
							<option id="lendDate,general.formId,lentId">借用者日期</option>
						</select>
						<font>排序</font>
					</td>
					<td align="right">
						<font>依</font>
						<select id="searchSelect" onchange="searchClicked(1)">
							<option id="general.formId">單號</option>
							<option id="equipmentNumber">器材編號</option>
							<option id="studentId">借用者學號</option> 
							<option id="lendDate">借用者日期</option>
						</select>
						<input type="text" id="searchTextfield" style="height: 20px;"/>
						<input type="button" value="搜尋" onclick="searchClicked(1)">
					</td>
				</tr>
				<tr>
					<td align="left" colspan="2">
						<input type="checkbox" id="checkboxAll" onclick="allCheckboxCheckedClicked(this,'checkboxName')" />
						<font>全部勾選</font>
						<input type="button" onclick="deleteClicked()" value="刪除" />
					</td>
				</tr>
			</table>
		</div>
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