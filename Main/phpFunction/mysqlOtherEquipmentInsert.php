<?php
	require "mysqlconf.php";

	if ($_POST['sqlCommandInsertData']) {
		$sqlCommandInsertData = $_POST['sqlCommandInsertData'];
	} else {
		$sqlCommandInsertData = "";
	}
	
	$sql = "INSERT INTO otherproperty (equipmentNumber, equipmentQuantity, lentQuantity, location, imageName)
			VALUES ('".$sqlCommandInsertData["equipmentNumber"]."', ".$sqlCommandInsertData["equipmentQuantity"].", ".$sqlCommandInsertData["lentQuantity"];
	$sql .= ",'".$sqlCommandInsertData["location"]."', '".$sqlCommandInsertData["imageName"]."')";

	$sqlResult = mysql_query($sql) or die('MySQL query error :'.mysql_error($link));
	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error inserting record: ".mysql_error($link);
	}
	
?> 