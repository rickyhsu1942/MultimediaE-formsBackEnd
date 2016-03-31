<?php
	require "mysqlconf.php";
	
	if ($_GET['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_GET['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}
	
	// 更新活動資訊(EVENT)
	$sql = "UPDATE event SET eventName='".$sqlCommandUpdateData["eventName"]."',
			eventStartDate='".$sqlCommandUpdateData["eventStartDate"]."', eventEndDate='".$sqlCommandUpdateData["eventEndDate"]."',
			lendDate='".$sqlCommandUpdateData["lendDate"]."', lendUndertaker = '".$sqlCommandUpdateData["lendUndertaker"]."' WHERE formId='".$sqlCommandUpdateData["formId"]."'";
	$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));
	
	// 更新隊伍資訊(TEAM)
	for ($i=0; $i < count($sqlCommandUpdateData["teams"]); $i++) { 
		$sql = "UPDATE team SET teamName='".$sqlCommandUpdateData["teams"][$i]["teamName"]."', studentId='".$sqlCommandUpdateData["teams"][$i]["studentId"]."', name='".$sqlCommandUpdateData["teams"][$i]["name"]."',
				phoneNumber='".$sqlCommandUpdateData["teams"][$i]["phoneNumber"]."', credentials = '".$sqlCommandUpdateData["teams"][$i]["credentials"]."' WHERE teamId='".$sqlCommandUpdateData["teams"][$i]["teamId"]."'";
		$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));
	}

	// 更新借出裝置資訊(LENT)
	for ($i=0; $i < count($sqlCommandUpdateData["equipments"]); $i++) { 
		$sqlEquipmentNumber = "UPDATE lent SET returnDate='".$sqlCommandUpdateData["equipments"][$i]["returnDate"]."',
		returnUndertaker='".$sqlCommandUpdateData["equipments"][$i]["returnUndertaker"]."' WHERE lentId=".$sqlCommandUpdateData["equipments"][$i]["lentId"];
		$sqlResult = mysql_query($sqlEquipmentNumber) or die('MySQL query error:'.mysql_error($link));
	}

	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error updating record: ".mysql_error($link);
	}
	
	
?> 