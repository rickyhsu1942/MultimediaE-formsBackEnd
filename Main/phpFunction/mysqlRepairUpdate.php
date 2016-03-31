<?php
	require "mysqlconf.php";
	
	if ($_GET['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_GET['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}
	
	$sql = "UPDATE repair SET company='".$sqlCommandUpdateData["company"]."', name='".$sqlCommandUpdateData["name"]."', 
			phoneNumber = '".$sqlCommandUpdateData["phoneNumber"]."', estimateReturnDate = '".$sqlCommandUpdateData["estimateReturnDate"]."',
			repairDate='".$sqlCommandUpdateData["repairDate"]."', repairUndertaker = '".$sqlCommandUpdateData["repairUndertaker"]."' WHERE formId='".$sqlCommandUpdateData["formId"]."'";
	$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));

	for ($i=0; $i < count($sqlCommandUpdateData["equipmentNumbers"]); $i++) { 
		$sqlEquipmentNumber = "UPDATE lent SET returnDate='".$sqlCommandUpdateData["equipmentNumbers"][$i]["returnDate"]."',
		returnUndertaker='".$sqlCommandUpdateData["equipmentNumbers"][$i]["returnUndertaker"]."' WHERE lentId=".$sqlCommandUpdateData["equipmentNumbers"][$i]["lentId"];
		$sqlResult = mysql_query($sqlEquipmentNumber) or die('MySQL query error:'.mysql_error($link));
	}

	if ($sqlResult) {
		echo 1;
	} else {
		echo "(Repair)Error updating record: ".mysql_error($link);
	}
	
	
?> 