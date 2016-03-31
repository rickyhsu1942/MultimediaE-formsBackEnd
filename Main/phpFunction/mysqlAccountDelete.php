<?php
	require "mysqlconf.php";
	//抓取目前頁數
	if ($_POST['sqlAccount']) {
		$Account = $_POST['sqlAccount'];
	} else {
		$Account = "";
	}

	$sql = "DELETE FROM account WHERE account = '".$Account."'";

	$sqlResult = mysql_query($sql) or die('MySQL query error');

	if ($sqlResult) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: ".mysql_error($link);
	}
?> 