<?php
	require "mysqlconf.php";

	$sql = "SELECT * FROM equipmentstate ";
	
	$sqlResult = mysql_query($sql) or die('MySQL query error');

	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$jsonProperty[] = array("stateID" => $row["stateID"], "stateName" => urlencode($row["stateName"]));
	}
	echo urldecode(json_encode($jsonProperty));
?> 