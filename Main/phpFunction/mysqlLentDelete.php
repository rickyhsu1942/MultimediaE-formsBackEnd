<?php
	require "mysqlconf.php";

	if ($_POST['sqlLentId']) {
		$lentId = $_POST['sqlLentId'];
	} else {
		$lentId = "";
	}

	if ($_POST['sqlFormId']) {
		$formId = $_POST['sqlFormId'];
	} else {
		$formId = "";
	}

	if ($_POST['sqlEquipmentNumber']) {
		$equipmentNumber = $_POST['sqlEquipmentNumber'];
	} else {
		$equipmentNumber = "";
	}

	if ($_POST['sqlEquipmentQuantity']) {
		$equipmentQuantity = $_POST['sqlEquipmentQuantity'];
	} else {
		$equipmentQuantity = "";
	}

	if ($_POST['sqlReturnUndertaker']) {
		$isReturn = ture;
	} else {
		$isReturn = false;
	}


	// 刪除lent
	$sql = "DELETE FROM lent WHERE lentId = '".$lentId."'";
	$sqlResult = mysql_query($sql) or die('MySQL query error(DELETE)');

	// 修正器材數量(補正刪除後，實際數量的借用器材與狀態)
	$sqlSelectOterEquipment = "SELECT * FROM otherproperty WHERE equipmentNumber='".$equipmentNumber."'";
	$sqlResult = mysql_query($sqlSelectOterEquipment) or die('MySQL query error:'.mysql_error($link));
	while($row = mysql_fetch_array($sqlResult)) {
		$lentQuantity = $row["lentQuantity"] - $equipmentQuantity;
		
		// 如果已經歸還代表已經處理過數量
		if ($lentQuantity >= 0 && !$isReturn) {
			$sqlEquipmentNumber = "UPDATE otherproperty SET lentQuantity=".$lentQuantity." WHERE equipmentNumber='".$equipmentNumber."'";
			$sqlResult = mysql_query($sqlEquipmentNumber) or die('MySQL query error:'.mysql_error($link));
		}
	}
	if (!$isReturn) {
		$sqlPropertyState = "UPDATE property SET equipmentState=1 WHERE equipmentNumber='".$equipmentNumber."'";
		$sqlResult = mysql_query($sqlPropertyState) or die('MySQL query error:'.mysql_error($link));
	}

	// 查詢lent是否還有其他相同單號，沒有的話就刪除借用表單對應的資料(代表借用表單已無借東西，不用留存)
	$sqlSelectlent = "SELECT * FROM lent WHERE formid = '".$formId."'";
	$sqlSelectResult = mysql_query($sqlSelectlent) or die('MySQL query error(SELECT)');
	$num = @mysql_num_rows($sqlSelectResult);
	if ($num < 1) {
		if (substr($formId, 0, 1) == "A") { 
			// 一般借用表單
			$sqlDeleteStandard = "DELETE FROM general WHERE formId = '".$formId."'";
		} else if (substr($formId, 0, 1) == "B") {
			// 展覽借用表單
			$sqlDeleteStandard = "DELETE FROM event WHERE formId = '".$formId."'";
			$sqlDeleteTeam = "DELETE FROM team WHERE formId = '".$formId."'";
			mysql_query($sqlDeleteTeam) or die('team MySQL query error(DELETE)');
		} else if (substr($formId, 0, 1) == "C") {
			// 其他部門借用表單
			$sqlDeleteStandard = "DELETE FROM department WHERE formId = '".$formId."'";
		} else if (substr($formId, 0, 1) == "D") {
			// 維修表單
			$sqlDeleteStandard = "DELETE FROM repair WHERE formId = '".$formId."'";
		}
		mysql_query($sqlDeleteStandard) or die('MySQL query error(DELETE)');
	}

	if ($sqlResult) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: ".mysql_error($link);
	}
?> 