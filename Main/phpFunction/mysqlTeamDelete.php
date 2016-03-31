<?php
	require "mysqlconf.php";
	//抓取目前頁數
	if ($_POST['sqlTeamId']) {
		$TeamId = $_POST['sqlTeamId'];
	} else {
		$TeamId = "";
	}

	$sql = "DELETE FROM team WHERE teamId = '".$TeamId."'";

	$sqlResult = mysql_query($sql) or die('team MySQL query error');

	if ($sqlResult) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: ".mysql_error($link);
	}
?> 