<?php
	require "mysqlconf.php";
	
	if ($_GET['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_GET['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}
	
	$sql = "UPDATE agreement SET content='".$sqlCommandUpdateData["agreementContent"]."' WHERE agreementID=1";
	$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));

	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error updating record: ".mysql_error($link);
	}
	
?> 