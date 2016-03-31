<?php
	require "mysqlconf.php";
	
	if ($_GET['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_GET['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}

	for ($i=0; $i < count($sqlCommandUpdateData["equipmentNumbers"]); $i++) {
		// 修正器材數量(補正刪除後，實際數量的借用器材與狀態)
		$sqlSelectOterEquipment = "SELECT * FROM otherproperty WHERE equipmentNumber='".$sqlCommandUpdateData["equipmentNumbers"][$i]["equipmentNumber"]."'";
		$sqlResult = mysql_query($sqlSelectOterEquipment) or die('MySQL query error:'.mysql_error($link));
		while($row = mysql_fetch_array($sqlResult)) {
			$lentQuantity = $row["lentQuantity"] - $sqlCommandUpdateData["equipmentNumbers"][$i]["equipmentQuantity"];
			if ($lentQuantity >= 0) {
				$sqlEquipmentNumber = "UPDATE otherproperty SET lentQuantity=".$lentQuantity." WHERE equipmentNumber='".$sqlCommandUpdateData["equipmentNumbers"][$i]["equipmentNumber"]."'";
				$sqlResult = mysql_query($sqlEquipmentNumber) or die('MySQL query error:'.mysql_error($link));
			}
		}
		// 寫入歸還承辦人與歸還日期
		$sqlLentReturn = "UPDATE lent SET returnDate='".$sqlCommandUpdateData["returnDate"]."'
		, returnUndertaker='".$sqlCommandUpdateData["returnUndertaker"]."' WHERE lentId=".$sqlCommandUpdateData["equipmentNumbers"][$i]["lentId"];
		$sqlResult = mysql_query($sqlLentReturn) or die('MySQL query error:'.mysql_error($link));
		// 修正有財產器材狀態
		$sqlPropertyState = "UPDATE property SET equipmentState=1 WHERE equipmentNumber='".$sqlCommandUpdateData["equipmentNumbers"][$i]["equipmentNumber"]."'";
		$sqlResult = mysql_query($sqlPropertyState) or die('MySQL query error:'.mysql_error($link));
	}


	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error updating record: ".mysql_error($link);
	}
	
	
?> 