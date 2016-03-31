<?php
	require "mysqlconf.php";
	//宣告一頁的顯示數量
	$pageRowRecords = 15;
	//抓取目前頁數
	if ($_POST['currentPage']) {
		$currentPage = $_POST['currentPage'];
	} else {
		$currentPage = 1;
	}
	//抓取SQL前端條件語法
	$sqlCommands = "";
	if (isset($_POST['sqlCommandKey']) && isset($_POST['sqlCommandValue'])) {
		$sqlCommandKey = $_POST['sqlCommandKey'];
		$sqlCommandVaule = $_POST['sqlCommandValue'];
		$sqlCommands = "WHERE ".$sqlCommandKey." = '".$sqlCommandVaule."'";
	}
	if (isset($_POST['sqlCommandOrder'])) {
		$sqlCommands = $sqlCommands ." ORDER BY ".$_POST['sqlCommandOrder'];
	}
	//計算出本業開始記錄筆數
	$startRowRecords = ($currentPage - 1) * $pageRowRecords;
	$sql = "SELECT * FROM otherproperty ".$sqlCommands;
	//加上限制顯示SQL
	$sqlLimit = $sql." LIMIT ".$startRowRecords.",".$pageRowRecords;

	$sqlResult = mysql_query($sqlLimit) or die('MySQL query error');
	$sqlAllResult = mysql_query($sql) or die('MySQL query error');

	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$jsonProperty[] = array("equipmentNumber" => urlencode($row["equipmentNumber"]), "equipmentQuantity" => $row["equipmentQuantity"]
			, "lentQuantity" => $row["lentQuantity"], "location" => urlencode($row["location"]), "imageName" => urlencode($row["imageName"])
			, "totalRows" => mysql_num_rows($sqlAllResult), "totalPages" => ceil(mysql_num_rows($sqlAllResult)/$pageRowRecords));
	}

	echo urldecode(json_encode($jsonProperty));
?> 