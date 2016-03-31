<?php
	require "mysqlconf.php";
	//抓取目前頁數
	if ($_GET['sqlEquipmentNumber']) {
		$EquipmentNumber = $_GET['sqlEquipmentNumber'];
	} else {
		$EquipmentNumber = "";
	}

	//計算出本業開始記錄筆數
	$startRowRecords = ($currentPage - 1) * $pageRowRecords;
	$sql = "DELETE FROM property WHERE equipmentNumber = '".$EquipmentNumber."'";

	$sqlResult = mysql_query($sql) or die('MySQL query error');

	if ($sqlResult) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: ".mysql_error($link);
	}
?> 