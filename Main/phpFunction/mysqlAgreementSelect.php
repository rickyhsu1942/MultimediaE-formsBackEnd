<?php
	require "mysqlconf.php";

	$sql = "SELECT * FROM agreement";

	$sqlResult = mysql_query($sql) or die('MySQL query error');

	//宣告裝入JSON的陣列
	$jsonProperty = array();

	while($row = mysql_fetch_array($sqlResult)) {
		$search = array("\n","\r");
		$jsonProperty[] = array("agreementID" => $row["agreementId"], "agreementContent" => urlencode(str_replace($search, "\\n", $row["content"])));
	}

	echo urldecode(json_encode($jsonProperty));
?> 