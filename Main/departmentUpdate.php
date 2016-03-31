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
	if ($_POST['sqlFormId']) {
		$formId = $_POST['sqlFormId'];
	}
?>

<link rel="stylesheet" type="text/css" href="calendar/tcal.css" /> <!--日曆css -->
<!-- script -->
<script type="text/javascript" src="calendar/tcal.js"></script> <!--日曆js -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">
	//全域變數
	var department;
	var departmentCredentials = ["學生證","身分證","健保卡","駕照","其他"];

	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}

	//網頁載入完成時
	$(document).on("ready",function() {
		loadSqlDepartmentData("department.formId",<?php print("\"".$formId."\""); ?>);
	});

	//載入MySQL其他單位借用
	var loadSqlDepartmentData = function(CommandKey,CommandValue) {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlDepartmentSelect.php",
			data:{sqlCommandKey:CommandKey, sqlCommandValue:CommandValue},
		}).done(function(data) {
			console.log(data);
			//$("#content").empty(); //清空原本的content
			department = JSON.parse(data);
			var tableHtml = "";
			if (department.length > 0) {
				document.getElementById("department").value = department[0].department;
				for (var index in departmentCredentials) {
					var credentialsOption = document.createElement("option");
					credentialsOption.text = departmentCredentials[index];
					if (departmentCredentials[index] == department[0].credentials) {
						credentialsOption.selected = "selected";
					}
					document.getElementById("credentialsSelect").add(credentialsOption);
				}
				document.getElementById("name").value = department[0].name;
				document.getElementById("phoneNumber").value = department[0].phoneNumber;
				document.getElementById("deadline").value = department[0].deadline;
				document.getElementById("emailAddress").value = department[0].emailAddress;
				document.getElementById("lendDate").value = department[0].lendDate;
				document.getElementById("lendUndertaker").value = department[0].lendUndertaker;

				var contentTable = document.getElementById("contentTable");
				for (var index in department) {
					
					// TR 器材
					var equipmentNumberTR = document.createElement("TR");
					equipmentNumberTR.setAttribute("id","equipmentNumberTR");
					document.getElementById("contentTable").appendChild(equipmentNumberTR);
					// TH 器材編號
					var equipmentNumberTitleTH = document.createElement("TH");
					equipmentNumberTitleTH.setAttribute("rowspan","2");
					equipmentNumberTitleTH.appendChild(document.createTextNode("器材"+ (+index + +1) +"："));
					equipmentNumberTitleTH.align = "right"
					equipmentNumberTR.appendChild(equipmentNumberTitleTH);
					// TD for textEquipentNumber
					var equipmentNumberContentTD = document.createElement("TD");
					equipmentNumberTR.appendChild(equipmentNumberContentTD);
					// textEquipentNumber
					var equipmentNumberContentText = document.createElement("INPUT");
					equipmentNumberContentText.setAttribute("type","text");
					equipmentNumberContentText.setAttribute("disabled","disabled");
					equipmentNumberContentText.style.background = "transparent";
					equipmentNumberContentText.value = department[index].equipmentNumber;
					equipmentNumberContentText.id = "equipmentNumber" + index;
					equipmentNumberContentTD.appendChild(document.createTextNode("器材名稱："));
					equipmentNumberContentTD.appendChild(equipmentNumberContentText);
					// hidden lentId
					var lentIdHidden = document.createElement("INPUT");
					lentIdHidden.setAttribute("type","hidden");
					lentIdHidden.value = department[index].lentId;
					equipmentNumberContentTD.appendChild(lentIdHidden);
					// TH 數量
					var equipmentQuantityTitleTH = document.createElement("TH");
					equipmentQuantityTitleTH.appendChild(document.createTextNode("數　　量："));
					equipmentQuantityTitleTH.align = "right"
					equipmentNumberTR.appendChild(equipmentQuantityTitleTH);
					// TD for textEquipmentQuantityContent
					var equipmentQuantityContentTD = document.createElement("TD");
					equipmentNumberTR.appendChild(equipmentQuantityContentTD);
					// textEquipmentQuantityContent
					var equipmentQuantityContentText = document.createElement("INPUT");
					equipmentQuantityContentText.setAttribute("type","text");
					equipmentQuantityContentText.setAttribute("disabled","disabled");
					equipmentQuantityContentText.style.background = "transparent";
					equipmentQuantityContentText.value = department[index].equipmentQuantity;
					equipmentQuantityContentText.id = "equipmentQuantity" + index;
					equipmentQuantityContentTD.appendChild(equipmentQuantityContentText);

					// TR 歸還
					var returnTR = document.createElement("TR");
					returnTR.setAttribute("id","returnTR");
					document.getElementById("contentTable").appendChild(returnTR);
					// TD for textReturnDateContent
					var returnDateContentTD = document.createElement("TD");
					returnTR.appendChild(returnDateContentTD);
					// textReturnDate
					var returnDateContentText = document.createElement("INPUT");
					returnDateContentText.setAttribute("type", "textfield");
					if (department[index].returnUndertaker == "" || department[index].returnUndertaker == null) {
						returnDateContentText.setAttribute("disabled", "disabled");
						returnDateContentText.style.background = "transparent";
					}
					returnDateContentText.value = department[index].returnDate;
					returnDateContentText.id = "returnDate" + index;
					returnDateContentTD.appendChild(document.createTextNode("歸還日期："));
					returnDateContentTD.appendChild(returnDateContentText);
					// TH 歸還承辦人
					var returnUndertakerTitleTH = document.createElement("TH");
					returnUndertakerTitleTH.appendChild(document.createTextNode("歸還承辦人："));
					returnUndertakerTitleTH.align = "right"
					returnTR.appendChild(returnUndertakerTitleTH);
					// TD for textReturnUndertakerContent
					var returnUndertakerContentTD = document.createElement("TD");
					returnTR.appendChild(returnUndertakerContentTD);
					// textReturnUndertakerContent
					var returnUndertakerContentText = document.createElement("INPUT");
					returnUndertakerContentText.setAttribute("type","text");
					if (department[index].returnUndertaker == "" || department[index].returnUndertaker == null) {
						returnUndertakerContentText.setAttribute("disabled","disabled");
						returnUndertakerContentText.style.background = "transparent";
					}
					returnUndertakerContentText.value = department[index].returnUndertaker;
					returnUndertakerContentText.id = "returnUndertaker" + index;
					returnUndertakerContentTD.appendChild(returnUndertakerContentText);
				}
				var submitTR = document.createElement("TR");
				document.getElementById("contentTable").appendChild(submitTR);

				var submitTD = document.createElement("TD");
				submitTD.colSpan = "4";
				submitTD.align = "center";
				submitTR.appendChild(submitTD);

				var submitButton = document.createElement("INPUT");
				submitButton.setAttribute("type","button");
				submitButton.onclick = function(){UpdateSqlDepartmentData()};
				submitButton.value = "確認";
				submitTD.appendChild(submitButton);
			}
		});
	}

	//修改MySQL其他單位借用
	var UpdateSqlDepartmentData = function(){

		for (var index in department) {
			if (!document.getElementById("returnUndertaker" + index).value && !document.getElementById("returnUndertaker" + index).disabled) {
				alert("尚有欄位未填寫!!");
				return;
			} else if (!dateValidationCheck(document.getElementById("returnDate" + index).value) && !document.getElementById("returnDate" + index).disabled) {
				// 檢查日期格式是否符合 YYYY-MM-DD
    			alert("請輸入 YYYY-MM-DD 日期格式");
				return;
			}
		}

		var aryEquipmentNumber = [];
		for (var index in department) {
			var equipmentNumbers = new Object();
			equipmentNumbers.lentId = department[index].lentId;
			equipmentNumbers.equipmentNumber = document.getElementById("equipmentNumber" + index).value;
			equipmentNumbers.equipmentQuantity = document.getElementById("equipmentQuantity" + index).value;
			equipmentNumbers.returnDate = document.getElementById("returnDate" + index).value,
			equipmentNumbers.returnUndertaker = document.getElementById("returnUndertaker" + index).value,
			aryEquipmentNumber.push(equipmentNumbers);
		}
		var updateJson = {"equipmentNumbers" : aryEquipmentNumber,
						  "department" :  document.getElementById("department").value,
						  "credentials" :  document.getElementById("credentialsSelect").value,
						  "name" :  document.getElementById("name").value,
						  "phoneNumber" :  document.getElementById("phoneNumber").value,
						  "deadline" :  document.getElementById("deadline").value,
						  "emailAddress" :  document.getElementById("emailAddress").value,
						  "lendDate" :  document.getElementById("lendDate").value,
						  "lendUndertaker" :  document.getElementById("lendUndertaker").value,
						  "formId" : <?php echo json_encode($formId); ?>
						};
		
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlDepartmentUpdate.php",
			data:{sqlCommandUpdateData:updateJson}
		}).done(function(data) {
			console.log(data);
			if (data == 1) {
				location.href = 'departmentUpdateDelete.php'
				alert("修改成功");
			} else {
				alert("修改失敗" + data);
			}
		});
	}

	// 檢查日期格式 YYYY-MM-DD
	function dateValidationCheck(str) {
  		var re = new RegExp("^([0-9]{4})[.-]{1}([0-9]{1,2})[.-]{1}([0-9]{1,2})$");
 		var strDataValue;
  		var infoValidation = true;
  		if ((strDataValue = re.exec(str)) != null) {
   			var i;
    		i = parseFloat(strDataValue[1]);
    		if (i <= 0 || i > 9999) { /*年*/
      			infoValidation = false;
    		}
    		i = parseFloat(strDataValue[2]);
    		if (i <= 0 || i > 12) { /*月*/
     			infoValidation = false;
    		}
    		i = parseFloat(strDataValue[3]);
    		if (i <= 0 || i > 31) { /*日*/
    			infoValidation = false;
    		}
  		} else {
    		infoValidation = false;
  		}
  		return infoValidation;
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
			<font style="font-size:20px; font-weight:bold;">歸還之器材表單(其他借用表單) - 歸還/修改<font>
		</div><br>
		<div id="content">
			<table align='center' id="contentTable" border=1 style='width:60%'> 
				<tr><th align='center' colspan='4'><?php print($formId); ?></th></tr>
				<tr><th align='right'>借用單位：</th><td align='left'><input type='textfield' id='department' value='' /></td>
					<th align='right'>抵押證件：</th><td align='left'><select id='credentialsSelect'></select></td></tr>
				<tr><th align='right'>姓　　名：</th><td align='left' colspan='3'><input type='textfield' id='name' value='' /></td></tr>
				<tr><th align='right'>電話號碼：</th><td align='left'><input type='textfield' id='phoneNumber' value='' /></td>
				<th align='right'>借用期限：</th><td align='left'><input type='textfield' id='deadline' value='' />天</td></tr>
				<tr><th align='right'>EMAIL：</th><td align='left' colspan='3'><input type='textfield' id='emailAddress' value='' /></td></tr>
				<tr><th align='right'>出借日期：</th><td align='left'><input type='textfield' readOnly=true class='tcal' id='lendDate' value='' /></td>
				<th align='right'>出借承辦人：</th><td align='left'><input type='textfield' id='lendUndertaker' value='' /></td></tr>
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