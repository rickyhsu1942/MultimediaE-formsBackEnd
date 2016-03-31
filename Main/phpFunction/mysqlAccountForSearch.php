<?php
	require "mysqlconf.php";

	//抓取SQL前端條件語法
	$sqlCommands = "";
	if (isset($_POST['sqlCommandKey']) && isset($_POST['sqlCommandValue'])) {
		$sqlCommandKey = $_POST['sqlCommandKey'];
		$sqlCommandVaule = $_POST['sqlCommandValue'];
		$sqlCommands = " WHERE ".$sqlCommandKey." LIKE '%".$sqlCommandVaule."%'";
	}
	if (isset($_POST['sqlCommandOrder'])) {
		$sqlCommands = $sqlCommands ." ORDER BY ".$_POST['sqlCommandOrder'];
	}

	$sql = "SELECT * FROM account ".$sqlCommands;

	$sqlResult = mysql_query($sql) or die('MySQL query error');

	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$jsonProperty[] = array("account" => $row["account"], "authority" => urlencode($row["authority"]));
	}

	echo urldecode(json_encode($jsonProperty));
?> 