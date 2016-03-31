<?php
	require "mysqlconf.php";

	if ($_POST['sqlCommandInsertData']) {
		$sqlCommandInsertData = $_POST['sqlCommandInsertData'];
	} else {
		$sqlCommandInsertData = "";
	}
	
	$sql = "INSERT INTO property (propertyNumber, kind, propertyName, company, equipmentNumber, equipmentState, costs, presentValue, date, deadLine, custodian, user, location, imageName)
			VALUES ('".$sqlCommandInsertData["propertyNumber"]."', ".$sqlCommandInsertData["equipmentKind"].", '".$sqlCommandInsertData["propertyName"]."'";
	$sql .= ",'".$sqlCommandInsertData["company"]."', '".$sqlCommandInsertData["equipmentNumber"]."', ".$sqlCommandInsertData["equipmentState"].", ";
	$sql .= "'".$sqlCommandInsertData["costs"]."', '".$sqlCommandInsertData["presentValue"]."', '".$sqlCommandInsertData["date"]."', ";
	$sql .= $sqlCommandInsertData["deadLine"].", '".$sqlCommandInsertData["custodian"]."', '".$sqlCommandInsertData["user"]."', ";
	$sql .= "'".$sqlCommandInsertData["location"]."', '".$sqlCommandInsertData["imageName"]."')";

	$sqlResult = mysql_query($sql) or die('MySQL query error :'.mysql_error($link));
	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error inserting record: ".mysql_error($link);
	}
	
?> 