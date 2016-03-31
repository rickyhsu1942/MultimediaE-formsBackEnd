<?php
	require "mysqlconf.php";
	
	if ($_GET['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_GET['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}
	
	$sql = "UPDATE general SET department='".$sqlCommandUpdateData["department"]."',
			credentials='".$sqlCommandUpdateData["credentials"]."', studentId='".$sqlCommandUpdateData["studentId"]."',
			name='".$sqlCommandUpdateData["name"]."', phoneNumber = '".$sqlCommandUpdateData["phoneNumber"]."',
			emailAddress='".$sqlCommandUpdateData["emailAddress"]."', deadline = ".$sqlCommandUpdateData["deadline"].",
			lendDate='".$sqlCommandUpdateData["lendDate"]."', lendUndertaker = '".$sqlCommandUpdateData["lendUndertaker"]."' WHERE formId='".$sqlCommandUpdateData["formId"]."'";
	$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));

	for ($i=0; $i < count($sqlCommandUpdateData["equipmentNumbers"]); $i++) { 
		$sqlEquipmentNumber = "UPDATE lent SET equipmentNumber='".$sqlCommandUpdateData["equipmentNumbers"][$i]["equipmentNumber"]."',
		equipmentQuantity=".$sqlCommandUpdateData["equipmentNumbers"][$i]["equipmentQuantity"].",
		returnDate='".$sqlCommandUpdateData["equipmentNumbers"][$i]["returnDate"]."',
		returnUndertaker='".$sqlCommandUpdateData["equipmentNumbers"][$i]["returnUndertaker"]."' WHERE lentId=".$sqlCommandUpdateData["equipmentNumbers"][$i]["lentId"];
		$sqlResult = mysql_query($sqlEquipmentNumber) or die('MySQL query error:'.mysql_error($link));
	}


	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error updating record: ".mysql_error($link);
	}
	
	
?> 