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
	var team;
	var teamCredentials = ["學生證","身分證","健保卡","駕照","其他"];

	//登出
	function logout() {
		location.href = 'phpfunction/logout.php'
	}

	//網頁載入完成時
	$(document).on("ready",function() {
		loadSqlTeamData("team.formId",<?php print("\"".$formId."\""); ?>);
	});

	//載入MySQL展覽單位借用
	var loadSqlTeamData = function(CommandKey,CommandValue) {
		$.ajax({
			type:"GET",
			url:"phpfunction/mysqlTeamSelect.php",
			data:{sqlCommandKey:CommandKey, sqlCommandValue:CommandValue},
		}).done(function(data) {
			console.log(data);
			//$("#content").empty(); //清空原本的content
			team = JSON.parse(data);
			var tableHtml = "";
			if (team.length > 0) {
				document.getElementById("eventName").value = team[0].eventName;
				document.getElementById("eventStartDate").value = team[0].eventStartDate;
				document.getElementById("eventEndDate").value = team[0].eventEndDate;
				document.getElementById("lendDate").value = team[0].lendDate;
				document.getElementById("lendUndertaker").value = team[0].lendUndertaker;

				var buffTeamId;
				var contentTable = document.getElementById("contentTable");

				for (var index in team) {
					// 判斷隊伍組別是否同組，如果不同組則開始建立組別資料
					if (buffTeamId != team[index].teamId) {
						buffTeamId = team[index].teamId
						//----------------------------------
						// TR 隊伍名稱
						var teamNameTR = document.createElement("TR");
						teamNameTR.setAttribute("id","teamNameTR");
						document.getElementById("contentTable").appendChild(teamNameTR);
						// TH 隊伍名稱
						var teamNameTH = document.createElement("TH");
						teamNameTH.setAttribute("colspan","4");
						teamNameTH.setAttribute("bgcolor","#FFCC99");
						teamNameTH.setAttribute("disabled","disabled");
						teamNameTH.id = "teamName" + index
						teamNameTH.appendChild(document.createTextNode(team[index].teamName));
						teamNameTH.value = team[index].teamName;
						teamNameTH.align = "center"
						teamNameTR.appendChild(teamNameTH);
						//----------------------------------
						//----------------------------------
						// TR 學號&學生姓名
						var studentTR = document.createElement("TR");
						studentTR.setAttribute("id","studentTR");
						document.getElementById("contentTable").appendChild(studentTR);
						// TH 學號
						var studentIdTH = document.createElement("TH");
						studentIdTH.appendChild(document.createTextNode("學   號："));
						studentIdTH.align = "right"
						studentTR.appendChild(studentIdTH);
						// TD 學號
						var studentIdTD = document.createElement("TD");
						studentTR.appendChild(studentIdTD);
						// TEXT 學號
						var studentIdText = document.createElement("INPUT");
						studentIdText.setAttribute("type","text");
						studentIdText.setAttribute("disabled","disabled");
						studentIdText.value = team[index].studentId;
						studentIdText.id = "studentId" + index;
						studentIdTD.appendChild(studentIdText);
						// TH 學生姓名
						var studentNameTH = document.createElement("TH");
						studentNameTH.appendChild(document.createTextNode("姓   名："));
						studentNameTH.align = "right"
						studentTR.appendChild(studentNameTH);
						// TD 學生性名
						var studentNameTD = document.createElement("TD");
						studentTR.appendChild(studentNameTD);
						// TEXT 學生性名
						var studentNameText = document.createElement("INPUT");
						studentNameText.setAttribute("type","text");
						studentNameText.setAttribute("disabled","disabled");
						studentNameText.value = team[index].name;
						studentNameText.id = "name" + index;
						studentNameTD.appendChild(studentNameText);
						//----------------------------------
						//----------------------------------
						//TR 手機與證件
						var phoneCredentialsTR = document.createElement("TR");
						phoneCredentialsTR.setAttribute("id","phoneCredentialsTR");
						document.getElementById("contentTable").appendChild(phoneCredentialsTR);
						// TH 手機
						var phoneTH = document.createElement("TH");
						phoneTH.appendChild(document.createTextNode("手   機："));
						phoneTH.align = "right"
						phoneCredentialsTR.appendChild(phoneTH);
						// TD 手機
						var phoneTD = document.createElement("TD");
						phoneCredentialsTR.appendChild(phoneTD);
						// TEXT 手機
						var phoneText = document.createElement("INPUT");
						phoneText.setAttribute("type","text");
						phoneText.setAttribute("disabled","disabled");
						phoneText.value = team[index].phoneNumber;
						phoneText.id = "phoneNumber" + index;
						phoneTD.appendChild(phoneText);
						// TH 證件
						var credentialsTH = document.createElement("TH");
						credentialsTH.appendChild(document.createTextNode("證   件："));
						credentialsTH.align = "right"
						phoneCredentialsTR.appendChild(credentialsTH);
						// TD 證件
						var credentialsTD = document.createElement("TD");
						phoneCredentialsTR.appendChild(credentialsTD);
						// SELECT 證件
						var credentialsSelect = document.createElement("SELECT");
						credentialsSelect.id = "credentialsSelect" + index;
						credentialsSelect.setAttribute("disabled","disabled");
						for (var i in teamCredentials) {
							var credentialsOption = document.createElement("option");
							credentialsOption.text = teamCredentials[i];
							if (teamCredentials[i] == team[index].credentials) {
								credentialsOption.selected = "selected";
							}
							credentialsSelect.add(credentialsOption);
						}	
						credentialsTD.appendChild(credentialsSelect);
					}
					//TR 裝置設備
					var equipmentTR = document.createElement("TR");
					equipmentTR.id = "equipmentTR";
					document.getElementById("contentTable").appendChild(equipmentTR);
					// TD 勾選
					var checkboxTD = document.createElement("TD");
					checkboxTD.setAttribute("rowspan","2");
					checkboxTD.align = "center";
					equipmentTR.appendChild(checkboxTD);
					// CHECKBOX 歸還
					var returnCheckbox = document.createElement("INPUT");
					returnCheckbox.setAttribute("type","checkbox");
					returnCheckbox.id = "returnCheckbox" + index;
					if (team[index].returnUndertaker != "" && team[index].returnUndertaker != null) {
						returnCheckbox.setAttribute("disabled","disabled");
						checkboxTD.appendChild(document.createTextNode("已歸還"));
						returnCheckbox.hidden = true;
					}
					checkboxTD.appendChild(returnCheckbox);
					// TD 器材編號
					var equipmentNumberTD = document.createElement("TD");
					equipmentTR.appendChild(equipmentNumberTD);
					// TEXT 器材編號
					var equipmentNumberText = document.createElement("INPUT");
					equipmentNumberText.setAttribute("type","text");
					equipmentNumberText.setAttribute("disabled","disabled");
					equipmentNumberText.style.background = "transparent";
					equipmentNumberText.value = team[index].equipmentNumber;
					equipmentNumberText.id = "equipmentNumber" + index;
					equipmentNumberTD.appendChild(document.createTextNode("器材名稱："));
					equipmentNumberTD.appendChild(equipmentNumberText);
					// HIDDEN 器材ID
					var lentIdHidden = document.createElement("INPUT");
					lentIdHidden.setAttribute("type","hidden");
					lentIdHidden.value = team[index].lentId;
					equipmentNumberTD.appendChild(lentIdHidden);
					// TH 器材數量
					var equipmentQuantityTH = document.createElement("TH");
					equipmentQuantityTH.appendChild(document.createTextNode("數　　量："));
					equipmentQuantityTH.align = "right"
					equipmentTR.appendChild(equipmentQuantityTH);
					// TD 器材數量
					var equipmentQuantityTD = document.createElement("TD");
					equipmentTR.appendChild(equipmentQuantityTD);
					// TEXT 器材數量
					var equipmentQuantityText = document.createElement("INPUT");
					equipmentQuantityText.setAttribute("type","text");
					equipmentQuantityText.setAttribute("disabled","disabled");
					equipmentQuantityText.style.background = "transparent";
					equipmentQuantityText.value = team[index].equipmentQuantity;
					equipmentQuantityText.id = "equipmentQuantity" + index;
					equipmentQuantityTD.appendChild(equipmentQuantityText);
					//----------------------------------
					//----------------------------------
					//TR 歸還日期與人員
					var returnTR = document.createElement("TR");
					returnTR.setAttribute("id","returnTR");
					document.getElementById("contentTable").appendChild(returnTR);
					// TD 歸還日期
					var returnDateTD = document.createElement("TD");
					returnTR.appendChild(returnDateTD);
					// TEXT 歸還日期
					var returnDateText = document.createElement("INPUT");
					returnDateText.setAttribute("type", "textfield");
					returnDateText.setAttribute("disabled", "disabled");
					returnDateText.style.background = "transparent";
					returnDateText.value = team[index].returnDate;
					returnDateText.id = "returnDate" + index;
					returnDateTD.appendChild(document.createTextNode("歸還日期："));
					returnDateTD.appendChild(returnDateText);
					// TH 歸還承辦人
					var returnUndertakerTH = document.createElement("TH");
					returnUndertakerTH.appendChild(document.createTextNode("歸還承辦人："));
					returnUndertakerTH.align = "right"
					returnTR.appendChild(returnUndertakerTH);
					// TD 歸還承辦人
					var returnUndertakerTD = document.createElement("TD");
					returnTR.appendChild(returnUndertakerTD);
					// TEXT 歸還承辦人
					var returnUndertakerText = document.createElement("INPUT");
					returnUndertakerText.setAttribute("type","text");
					returnUndertakerText.setAttribute("disabled","disabled");
					returnUndertakerText.style.background = "transparent";
					returnUndertakerText.value = team[index].returnUndertaker;
					returnUndertakerText.id = "returnUndertaker" + index;
					returnUndertakerTD.appendChild(returnUndertakerText);
				}
				// 送出欄位
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
				submitButton.onclick = function(){UpdateSqlPropertyDataAndTeam()};
				submitButton.value = "歸還";
				submitTD.appendChild(submitButton);
			}
		});
	}

	//修改MySQL器材型錄資料
	var UpdateSqlPropertyDataAndTeam = function(){

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

		for (var index in team) {
			if (document.getElementById("equipmentQuantity" + index).value > team[index].equipmentQuantity){
				alert("裝置歸還數量錯誤!!");
				return;
			}
		}

		var aryEquipmentNumber = [];
		for (var index in team) {
			// 加入已勾選的器材到 equipmentNumbers Object
			if ($('#returnCheckbox' + index)[0].checked) {
				var equipmentNumbers = new Object();
				equipmentNumbers.lentId = team[index].lentId;
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
				location.href = 'teamUpdateDelete.php'
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
			<font style="font-size:20px; font-weight:bold;">歸還之器材表單(展覽借用表單) - 修改<font>
		</div><br>
		<div id="content">
			<table align='center' id="contentTable" border=1 style='width:60%'> 
				<tr><th align='center' colspan='4'><?php print($formId); ?></th></tr>
				<tr><th align='right'>活動名稱：</th><td align='left' colspan='3'><input disabled type='textfield' style='width:100%;' id='eventName' value='' /></td></tr>
				<tr><th align='right'>活動起始日期：</th><td align='left'><input disabled type='textfield' readOnly=true class='tcal' id='eventStartDate' value='' /></td>
				<th align='right'>活動結束日期：</th><td align='left'><input disabled type='textfield' readOnly=true class='tcal' id='eventEndDate' value='' /></td></tr>
				<tr><th align='right'>出借日期：</th><td align='left'><input disabled type='textfield' readOnly=true class='tcal' id='lendDate' value='' /></td>
				<th align='right'>出借承辦人：</th><td align='left'><input disabled type='textfield' id='lendUndertaker' value='' /></td></tr>
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