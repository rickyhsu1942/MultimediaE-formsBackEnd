<?php
	require "mysqlconf.php";

	$sql = "SELECT * FROM equipmentkind ";
	
	$sqlResult = mysql_query($sql) or die('MySQL query error');

	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$jsonProperty[] = array("kindID" => $row["kindID"], "kindName" => urlencode($row["kindName"]));
	}
	echo urldecode(json_encode($jsonProperty));
?> 