<?php
	require "mysqlconf.php";

	if ($_POST['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_POST['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}

	for ($i=0; $i < count($sqlCommandUpdateData); $i++) {
		$sql = "UPDATE account SET authority = '".$sqlCommandUpdateData[$i]["authority"]."' WHERE account = '".$sqlCommandUpdateData[$i]["account"]."'";
		$sqlResult = mysql_query($sql) or die('Authority MySQL query error(Update)');
	}

	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error update record: ".mysql_error($link);
	}
?> 