<?php
	require "mysqlconf.php";
	
	if ($_POST['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_POST['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}
	
	$sql = "UPDATE property SET equipmentNumber='".$sqlCommandUpdateData["equipmentNumber"]."',
			equipmentState=".$sqlCommandUpdateData["equipmentState"].", location='".$sqlCommandUpdateData["location"]."',
			deadLine=".$sqlCommandUpdateData["deadLine"].", custodian = '".$sqlCommandUpdateData["custodian"]."',
			imageName='".$sqlCommandUpdateData["imageName"]."' WHERE propertyNumber='".$sqlCommandUpdateData["propertyNumber"]."'";
	$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));

	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error updating record: ".mysql_error($link);
	}
	
?> 