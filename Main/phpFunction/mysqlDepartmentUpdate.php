<?php
	require "mysqlconf.php";
	
	if ($_GET['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_GET['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}
	
	$sql = "UPDATE department SET department='".$sqlCommandUpdateData["department"]."',
			credentials='".$sqlCommandUpdateData["credentials"]."', name='".$sqlCommandUpdateData["name"]."', 
			phoneNumber = '".$sqlCommandUpdateData["phoneNumber"]."', emailAddress='".$sqlCommandUpdateData["emailAddress"]."', 
			deadline = ".$sqlCommandUpdateData["deadline"].", lendDate='".$sqlCommandUpdateData["lendDate"]."', 
			lendUndertaker = '".$sqlCommandUpdateData["lendUndertaker"]."' WHERE formId='".$sqlCommandUpdateData["formId"]."'";
	$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));

	for ($i=0; $i < count($sqlCommandUpdateData["equipmentNumbers"]); $i++) { 
		$sqlEquipmentNumber = "UPDATE lent SET returnDate='".$sqlCommandUpdateData["equipmentNumbers"][$i]["returnDate"]."',
		returnUndertaker='".$sqlCommandUpdateData["equipmentNumbers"][$i]["returnUndertaker"]."' WHERE lentId=".$sqlCommandUpdateData["equipmentNumbers"][$i]["lentId"];
		$sqlResult = mysql_query($sqlEquipmentNumber) or die('MySQL query error:'.mysql_error($link));
	}

	if ($sqlResult) {
		echo 1;
	} else {
		echo "(Department)Error updating record: ".mysql_error($link);
	}
	
	
?> 