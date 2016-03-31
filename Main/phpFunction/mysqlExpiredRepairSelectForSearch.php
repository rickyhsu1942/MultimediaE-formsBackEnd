<?php
	require "mysqlconf.php";
	//宣告一頁的顯示數量
	$pageRowRecords = 15;
	//抓取目前頁數
	if ($_GET['currentPage']) {
		$currentPage = $_GET['currentPage'];
	} else {
		$currentPage = 1;
	}
	//抓取SQL前端條件語法
	$sqlCommands = "";
	if (isset($_GET['sqlCommandLentKind'])) {
		$sqlCommandLentKind = $_GET['sqlCommandLentKind'];
		$sqlCommands = " WHERE LEFT(repair.formId,1) = '".$sqlCommandLentKind."' AND estimateReturnDate < CURDATE() AND (returnUndertaker is null OR returnUndertaker='') ";
		if (isset($_GET['sqlCommandKey']) && isset($_GET['sqlCommandValue'])) {
			$sqlCommandKey = $_GET['sqlCommandKey'];
			$sqlCommandVaule = $_GET['sqlCommandValue'];
			$sqlCommands = $sqlCommands ."AND ".$sqlCommandKey." LIKE '%".$sqlCommandVaule."%'";
		}
	}
	if (isset($_GET['sqlCommandOrder'])) {
		$sqlCommands = $sqlCommands ." ORDER BY ".$_GET['sqlCommandOrder'];
	}
	//計算出本業開始記錄筆數
	$startRowRecords = ($currentPage - 1) * $pageRowRecords;
	$sql = "SELECT * FROM repair LEFT JOIN lent ON repair.formId = lent.formId ".$sqlCommands;
	//加上限制顯示SQL
	$sqlLimit = $sql." LIMIT ".$startRowRecords.",".$pageRowRecords;
	//echo $sqlLimit;

	$sqlResult = mysql_query($sqlLimit) or die('MySQL query error');
	$sqlAllResult = mysql_query($sql) or die('MySQL query error');


	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$jsonProperty[] = array("formId" => $row["formId"], "company" => urlencode($row["company"]), "name" => urlencode($row["name"])
			, "phoneNumber" => $row["phoneNumber"], "estimateReturnDate" => $row["estimateReturnDate"], "repairDate" => $row["repairDate"], "returnDate" => $row["returnDate"]
			, "repairUndertaker" => urlencode($row["repairUndertaker"]), "returnUndertaker" => urlencode($row["returnUndertaker"])
			, "equipmentNumber" => urlencode($row["equipmentNumber"]), "equipmentQuantity" => $row["equipmentQuantity"], "lentId" => $row["lentId"]
			, "totalRows" => mysql_num_rows($sqlAllResult), "totalPages" => ceil(mysql_num_rows($sqlAllResult)/$pageRowRecords));
	}

	echo urldecode(json_encode($jsonProperty));
?> 