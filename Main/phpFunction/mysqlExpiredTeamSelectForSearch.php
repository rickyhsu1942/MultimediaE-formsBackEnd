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
		$sqlCommands = " WHERE LEFT(team.formId,1) = '".$sqlCommandLentKind."' AND eventEndDate < CURDATE() ";
		if (isset($_GET['sqlCommandKey']) && isset($_GET['sqlCommandValue'])) {
			$sqlCommandKey = $_GET['sqlCommandKey'];
			$sqlCommandVaule = $_GET['sqlCommandValue'];
			$sqlCommands = $sqlCommands ." AND ".$sqlCommandKey." LIKE '%".$sqlCommandVaule."%'";
		}
	}
	if (isset($_GET['sqlCommandOrder'])) {
		$sqlCommands = $sqlCommands ." ORDER BY ".$_GET['sqlCommandOrder']." DESC";
	}
	//計算出本業開始記錄筆數
	$startRowRecords = ($currentPage - 1) * $pageRowRecords;
	$sql = "SELECT * from team  inner join event on team.formId = event.formId inner join (
				SELECT distinct lent.formId, lent.teamId FROM team inner join lent on team.formId = lent.formId 
				where (returnUndertaker is null OR returnUndertaker='')
			) as unReturnLents on unReturnLents.teamId = team.teamId".$sqlCommands;
	
	//加上限制顯示SQL
	$sqlLimit = $sql." LIMIT ".$startRowRecords.",".$pageRowRecords;

	$sqlResult = mysql_query($sqlLimit) or die('MySQL query error');
	$sqlAllResult = mysql_query($sql) or die('MySQL query error');


	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$jsonProperty[] = array("teamId" => $row["teamId"], "formId" => $row["formId"], "teamName" => urlencode($row["teamName"])
			, "credentials" => urlencode($row["credentials"]), "name" => urlencode($row["name"]), "phoneNumber" => $row["phoneNumber"]
			, "eventName" => urlencode($row["eventName"]), "eventStartDate" => $row["eventStartDate"], "eventEndDate" => $row["eventEndDate"]
			, "lendDate" => $row["lendDate"], "lendUndertaker" => $row["lendUndertaker"], "studentId" => urlencode($row["studentId"])
			, "totalRows" => mysql_num_rows($sqlAllResult), "totalPages" => ceil(mysql_num_rows($sqlAllResult)/$pageRowRecords));
	}

	echo urldecode(json_encode($jsonProperty));
?> 