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
	if (isset($_GET['sqlCommandKey']) && isset($_GET['sqlCommandValue'])) {
		$sqlCommandKey = $_GET['sqlCommandKey'];
		$sqlCommandVaule = $_GET['sqlCommandValue'];
		$sqlCommands = "WHERE ".$sqlCommandKey." = '".$sqlCommandVaule."'";
	}
	if (isset($_GET['sqlCommandOrder'])) {
		$sqlCommands = $sqlCommands ." ORDER BY ".$_GET['sqlCommandOrder'];
	}
	//計算出本業開始記錄筆數
	$startRowRecords = ($currentPage - 1) * $pageRowRecords;
	$sql = "SELECT * FROM event inner JOIN team on event.formId=team.formId inner JOIN lent on team.teamId=lent.teamId ".$sqlCommands;
	//加上限制顯示SQL
	$sqlLimit = $sql." LIMIT ".$startRowRecords.",".$pageRowRecords;
	$sqlResult = mysql_query($sqlLimit) or die('MySQL query error');
	$sqlAllResult = mysql_query($sql) or die('MySQL query error');

	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$jsonProperty[] = array("teamId" => $row["teamId"], "formId" => $row["formId"], "teamName" => urlencode($row["teamName"])
			, "credentials" => urlencode($row["credentials"]), "studentId" => urlencode($row["studentId"]), "name" => urlencode($row["name"]), "phoneNumber" => $row["phoneNumber"]
			, "eventName" => urlencode($row["eventName"]), "eventStartDate" => $row["eventStartDate"], "eventEndDate" => $row["eventEndDate"]
			, "lendDate" => $row["lendDate"], "lendUndertaker" => $row["lendUndertaker"], "returnDate" => $row["returnDate"], "returnUndertaker" => $row["returnUndertaker"]
			, "equipmentNumber" => urlencode($row["equipmentNumber"]), "equipmentQuantity" => $row["equipmentQuantity"], "lentId" => $row["lentId"]
			, "totalRows" => mysql_num_rows($sqlAllResult), "totalPages" => ceil(mysql_num_rows($sqlAllResult)/$pageRowRecords));
	}

	echo urldecode(json_encode($jsonProperty));
?> 