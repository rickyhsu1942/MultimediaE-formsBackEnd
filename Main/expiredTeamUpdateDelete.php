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
	var team;
	var blackList;

	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}

	//網頁載入完成時
	$(document).on("ready",function() {
		loadSqlBlackListData();
	});


	//載入MySQL黑名單
	var loadSqlBlackListData = function() {
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlBlackListForSearch.php",
		}).done(function(data) {
			console.log(data);
			blackList = JSON.parse(data);
			searchClicked(1);
		});
	}

	//載入MySQL展覽借用
	var loadSqlTeamData = function(page,CommandOrder,CommandKey,CommandValue) {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlExpiredTeamSelectForSearch.php",
			data:{currentPage:page, sqlCommandLentKind:"B", sqlCommandKey:CommandKey, sqlCommandValue:CommandValue, sqlCommandOrder:CommandOrder},
		}).done(function(data) {
			console.log(data);
			$("#content").empty(); //清空原本的content
			team = JSON.parse(data);
			var totalPages = 0;
			var totalRows = 0;
			var number = (page - 1) * 15;
			var tableHtml = "";
			var blackListHtml ="";

			tableHtml = "<table border=1 style='width:100%'><tr><th>勾選</th><th>單號</th><th>組員名稱</th><th>借用者姓名學號</th><th>活動名稱</th><th>功能</th></tr>";
			for (var index in team) {

				var buffFormId;
				var formIdColor;
				if (buffFormId != team[index].formId) {
					buffFormId = team[index].formId;
					if (formIdColor == "#BD82DA") {
						formIdColor = "#52A3CC";
					} else {
						formIdColor = "#BD82DA";
					}
				}

				blackListHtml = "<input type='button' value='加入黑名單' onclick='addBlackListClicked(" + index + ")'";
				number ++;
				totalPages = team[index].totalPages;
				totalRows = team[index].totalRows;
				tableHtml += "<tr>";
				tableHtml += "<td align=center><input type='checkbox' name='checkboxName' id='checkbox" + index + "'/></td>" +
							 "<td bgcolor='" + formIdColor + "'>" + team[index].formId + "</td>" +
							 "<td>" + team[index].teamName + "</td>" + 
						     "<td>" + team[index].studentId + "&nbsp;&nbsp;" +team[index].name + "</td>" + 
						     "<td align='center'>" + team[index].eventName + "</td>" + 
						     "<td align=center><form action='teamUpdate.php' method='post'>" +
						     "<input type='submit' value='修改' />" +
						     "<input type='hidden' name='sqlFormId' value='" + team[index].formId + "' /></form>";
				tableHtml += "<form action='teamReturnUpdate.php' method='post'>" +
						   	 "<input type='submit' value='歸還' />" +
						     "<input type='hidden' name='sqlFormId' value='" + team[index].formId + "' /></form>";
				for (var i in blackList) {
					if (blackList[i].studentId.toLowerCase() == team[index].studentId.toLowerCase()) {
						blackListHtml = "&nbsp;&nbsp;&nbsp;<font color='red' style='font-size:10px;'>已加入黑名單</font>"
						break;
					}
				}
				tableHtml += blackListHtml;
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

	//查詢team繫結相關資料
	var selectSqlTeamData = function(CommandValue) {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlTeamSelect.php",
			data:{currentPage:1, sqlCommandLentKind:"B", sqlCommandKey:"team.teamId", sqlCommandValue:CommandValue, sqlCommandOrder:"team.formId"},
		}).done(function(data) {
			var result = data;
			console.log(result);
			var teamForDelete = JSON.parse(data);
			for (var i in teamForDelete) {
				deleteSqlLentData(teamForDelete[i].lentId, teamForDelete[i].formId, teamForDelete[i].equipmentNumber, teamForDelete[i].equipmentQuantity, teamForDelete[i].returnUndertaker, teamForDelete[i].teamId);
			}
		});
	}

	//刪除借出器材資料
	var deleteSqlLentData = function(lentId, formId, equipmentNumber, equipmentQuantity, returnUndertaker, teamId) {
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
			deleteSqlTeamData(teamId);
		});
	}

	//刪除team資料
	var deleteSqlTeamData = function(teamId) {
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlTeamDelete.php",
			data:{sqlTeamId:teamId},
		}).done(function(data) {
			var result = data;
			console.log(result);
			loadSqlBlackListData();
		});
	}


	//搜尋
	function searchClicked(page) {
		var orderSelect =  document.getElementById("orderSelect");
		var searchTextfield = document.getElementById("searchTextfield");
		var searchSelect =  document.getElementById("searchSelect");
		document.getElementById("checkboxAll").checked = false;
		loadSqlTeamData(page, orderSelect.options[orderSelect.selectedIndex].id,searchSelect.options[searchSelect.selectedIndex].id,searchTextfield.value);
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
				selectSqlTeamData(team[i].teamId)
			}
		}
	}

	//加入黑名單
	function addBlackListClicked(buttonIndex) {
		var insertJson = {"studentId" : team[buttonIndex].studentId,
						  "name" : team[buttonIndex].name};
		$.ajax({
			type:"POST",
			url:"phpfunction/mysqlBlackListInsert.php",
			data:{sqlCommandInsertData:insertJson},
		}).done(function(data) {
			if (data == 1) {
				alert("黑名單新增成功");
				loadSqlBlackListData();
			} else {
				alert("黑名單新增失敗" + data);
			}
		});
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
			<font style="font-size:20px; font-weight:bold;">逾期未歸還之器材表單(展覽借用組別) - 修改/刪除<font>
		</div><br>
		<div id="search">
			<table cellpadding="0px" cellspacing="0px" border=0 style="width:100%">
				<tr>
					<td align="left">
						<font>依</font>
						<select id="orderSelect" onchange="searchClicked(1)">
							<option id="team.formId">單號</option>
						</select>
						<font>排序</font>
					</td>
					<td align="right">
						<font>依</font>
						<select id="searchSelect" onchange="searchClicked(1)">
							<option id="team.formId">單號</option>
							<option id="teamName">組別名稱</option>
							<option id="name">借用者姓名</option>
							<option id="eventName">活動名稱</option>
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