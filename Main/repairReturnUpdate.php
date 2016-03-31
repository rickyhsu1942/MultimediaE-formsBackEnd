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
	var repair;

	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}

	//網頁載入完成時
	$(document).on("ready",function() {
		loadSqlRepairData("repair.formId",<?php print("\"".$formId."\""); ?>);
	});

	//載入MySQL送修表單
	var loadSqlRepairData = function(CommandKey,CommandValue) {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlRepairSelect.php",
			data:{sqlCommandKey:CommandKey, sqlCommandValue:CommandValue},
		}).done(function(data) {
			console.log(data);
			//$("#content").empty(); //清空原本的content
			repair = JSON.parse(data);
			var tableHtml = "";
			if (repair.length > 0) {
				document.getElementById("company").value = repair[0].company;
				document.getElementById("name").value = repair[0].name;
				document.getElementById("phoneNumber").value = repair[0].phoneNumber;
				document.getElementById("estimateReturnDate").value = repair[0].estimateReturnDate;
				document.getElementById("repairDate").value = repair[0].repairDate;
				document.getElementById("repairUndertaker").value = repair[0].repairUndertaker;
				var contentTable = document.getElementById("contentTable");
				for (var index in repair) {
					// TR 器材
					var equipmentNumberTR = document.createElement("TR");
					equipmentNumberTR.setAttribute("id","equipmentNumberTR");
					document.getElementById("contentTable").appendChild(equipmentNumberTR);
					// TD for checkbox
					var checkboxTD = document.createElement("TD");
					checkboxTD.setAttribute("rowspan","2");
					checkboxTD.align = "center";
					equipmentNumberTR.appendChild(checkboxTD);
					// return checkbox
					var returnCheckbox = document.createElement("INPUT");
					returnCheckbox.setAttribute("type","checkbox");
					returnCheckbox.id = "returnCheckbox" + index;
					if (repair[index].returnUndertaker != "" && repair[index].returnUndertaker != null) {
						returnCheckbox.setAttribute("disabled","disabled");
						checkboxTD.appendChild(document.createTextNode("已歸還"));
						returnCheckbox.hidden = true;
					}
					checkboxTD.appendChild(returnCheckbox);
					// TD for textEquipentNumber
					var equipmentNumberContentTD = document.createElement("TD");
					equipmentNumberTR.appendChild(equipmentNumberContentTD);
					// textEquipentNumber
					var equipmentNumberContentText = document.createElement("INPUT");
					equipmentNumberContentText.setAttribute("type","text");
					equipmentNumberContentText.setAttribute("disabled","disabled");
					equipmentNumberContentText.style.border = "0px";
					equipmentNumberContentText.style.background = "transparent";
					equipmentNumberContentText.value = repair[index].equipmentNumber;
					equipmentNumberContentText.id = "equipmentNumber" + index;
					equipmentNumberContentTD.appendChild(document.createTextNode("器材名稱："));
					equipmentNumberContentTD.appendChild(equipmentNumberContentText);
					// hidden lentId
					var lentIdHidden = document.createElement("INPUT");
					lentIdHidden.setAttribute("type","hidden");
					lentIdHidden.value = repair[index].lentId;
					equipmentNumberContentTD.appendChild(lentIdHidden);
					// TH 數量
					var equipmentQuantityTitleTH = document.createElement("TH");
					equipmentQuantityTitleTH.appendChild(document.createTextNode("歸還數量："));
					equipmentQuantityTitleTH.align = "right"
					equipmentNumberTR.appendChild(equipmentQuantityTitleTH);
					// TD for textEquipmentQuantityContent
					var equipmentQuantityContentTD = document.createElement("TD");
					equipmentNumberTR.appendChild(equipmentQuantityContentTD);
					// textEquipmentQuantityContent
					var equipmentQuantityContentText = document.createElement("INPUT");
					equipmentQuantityContentText.setAttribute("type","text");
					equipmentQuantityContentText.setAttribute("disabled","disabled");
					equipmentQuantityContentText.style.border = "0px";
					equipmentQuantityContentText.style.background = "transparent";
					equipmentQuantityContentText.value = repair[index].equipmentQuantity;
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
					returnDateContentText.value = repair[index].returnDate;
					returnDateContentText.id = "returnDate" + index;
					returnDateContentText.setAttribute("disabled","disabled");
					returnDateContentText.style.background = "transparent";
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
					returnUndertakerContentText.value = repair[index].returnUndertaker;
					returnUndertakerContentText.id = "returnUndertaker" + index;
					returnUndertakerContentText.setAttribute("disabled","disabled");
					returnUndertakerContentText.style.background = "transparent";
					returnUndertakerContentTD.appendChild(returnUndertakerContentText);
				}
				var submitTR = document.createElement("TR");
				document.getElementById("contentTable").appendChild(submitTR);

				var submitTD = document.createElement("TD");
				submitTD.colSpan = "4";
				submitTD.align = "left";
				submitTR.appendChild(submitTD);

				// 輸入歸還承辦人
				var inputReturnUndertakerText = document.createElement("INPUT");
				inputReturnUndertakerText.setAttribute("type","text");
				inputReturnUndertakerText.id = "inputReturnUndertaker"
				submitTD.appendChild(document.createTextNode(" 歸還承辦人："));
				submitTD.appendChild(inputReturnUndertakerText);

				// 輸入歸還日期

				var now = new Date();
				var inputReturnDateText = document.createElement("INPUT");
				inputReturnDateText.setAttribute("type","text");
				inputReturnDateText.id = "inputReturnDate"
				inputReturnDateText.value = now.yyyymmdd();
				submitTD.appendChild(document.createTextNode(" 歸還日期："));
				submitTD.appendChild(inputReturnDateText);

				var submitButton = document.createElement("INPUT");
				submitButton.setAttribute("type","button");
				submitButton.onclick = function(){UpdateSqlPropertyDataAndRepair()};
				submitButton.value = "歸還";
				submitTD.appendChild(submitButton);
			}
		});
	}

	//修改MySQL器材型錄資料
	var UpdateSqlPropertyDataAndRepair = function(){

		if ($('input[type="checkbox"]:checked').length == 0) {
			alert("未有器材被歸還，請先勾選要歸還器材");
			return;
		} else if (!document.getElementById("inputReturnUndertaker").value || !document.getElementById("inputReturnDate").value) {
			alert("尚有欄位未填寫!!");
			return;
		} else if (!dateValidationCheck(document.getElementById("inputReturnDate").value)) {
			// 檢查日期格式是否符合 YYYY-MM-DD
    		alert("請輸入 YYYY-MM-DD 日期格式");
			return;
		}

		for (var index in repair) {
			if (document.getElementById("equipmentQuantity" + index).value > repair[index].equipmentQuantity){
				alert("裝置歸還數量錯誤!!");
				return;
			}
		}

		var aryEquipmentNumber = [];
		for (var index in repair) {
			// 加入已勾選的器材到 equipmentNumbers Object
			if ($('#returnCheckbox' + index)[0].checked) {
				var equipmentNumbers = new Object();
				equipmentNumbers.lentId = repair[index].lentId;
				equipmentNumbers.equipmentNumber = document.getElementById("equipmentNumber" + index).value;
				equipmentNumbers.equipmentQuantity = document.getElementById("equipmentQuantity" + index).value;
				aryEquipmentNumber.push(equipmentNumbers);
			}
		}
		var updateJson = {"equipmentNumbers" : aryEquipmentNumber,
						  "returnDate" :  document.getElementById("inputReturnDate").value,
						  "returnUndertaker" :  document.getElementById("inputReturnUndertaker").value,
						  "formId" : <?php echo json_encode($formId); ?>
						};
		
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlReturnPropertyUpdate.php",
			data:{sqlCommandUpdateData:updateJson}
		}).done(function(data) {
			console.log(data);
			if (data == 1) {
				location.href = 'repairUpdateDelete.php'
				alert("歸還修改成功");
			} else {
				alert("歸還修改失敗" + data);
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

	// 回傳YYYY-MM-DD格式
	Date.prototype.yyyymmdd = function() {         
                                
        var yyyy = this.getFullYear().toString();                                    
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
        var dd  = this.getDate().toString();             
                            
        return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' + (dd[1]?dd:"0"+dd[0]);
   };

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
			<font style="font-size:20px; font-weight:bold;">歸還之器材表單(送修表單) - 歸還/修改<font>
		</div><br>
		<div id="content">
			<table align='center' id="contentTable" border=1 style='width:60%'> 
				<tr><th align='center' colspan='4'><?php print($formId); ?></th></tr>
				<tr><th align='right'>公   司：</th><td align='left' colspan = '3'><input disabled type='textfield' id='company' value='' style='border:0px;background:transparent;' /></td></tr>
				<tr><th align='right'>姓　　名：</th><td align='left' colspan = '3'><input disabled type='textfield' id='name' value='' style='border:0px;background:transparent;' /></td></tr>
				<tr><th align='right'>電話號碼：</th><td align='left'><input disabled type='textfield' id='phoneNumber' value='' style='border:0px;background:transparent;' /></td>
				<th align='right'>預計歸還：</th><td align='left'><input disabled type='textfield' id='estimateReturnDate' value='' style='border:0px;background:transparent;' />天</td></tr>
				<tr><th align='right'>送修日期：</th><td align='left'><input disabled type='textfield' readOnly=true id='repairDate' value='' style='border:0px;background:transparent;' /></td>
				<th align='right'>出借承辦人：</th><td align='left'><input disabled type='textfield' id='repairUndertaker' value='' style='border:0px;background:transparent;' /></td></tr>
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