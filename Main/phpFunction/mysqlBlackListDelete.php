<?php
	require "mysqlconf.php";
	//抓取目前頁數
	if ($_POST['sqlStudentId']) {
		$StudentId = $_POST['sqlStudentId'];
	} else {
		$StudentId = "";
	}

	$sql = "DELETE FROM blackList WHERE studentId = '".$StudentId."'";

	$sqlResult = mysql_query($sql) or die('MySQL query error');

	if ($sqlResult) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: ".mysql_error($link);
	}
?> 