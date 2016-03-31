<?php
	require "mysqlconf.php";
	//抓取目前頁數
	if ($_GET['sqlFormId']) {
		$formId = $_GET['sqlFormId'];
	} else {
		$formId = "";
	}

	//計算出本業開始記錄筆數
	$startRowRecords = ($currentPage - 1) * $pageRowRecords;
	$sql = "DELETE FROM standard WHERE formId = '".$formId."'";

	$sqlResult = mysql_query($sql) or die('MySQL query error');

	if ($sqlResult) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: ".mysql_error($link);
	}
?> 