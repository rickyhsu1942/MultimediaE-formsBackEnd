<?php
	require "mysqlconf.php";

	if ($_POST['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_POST['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}

	//查詢account的密碼是否正確
	$sqlSelectlentaccount = "SELECT * FROM account WHERE account = '".$sqlCommandUpdateData["account"]."' AND password='".$sqlCommandUpdateData["oldPassword"]."'";
	$sqlSelectResult = mysql_query($sqlSelectlentaccount) or die('MySQL query error(SELECT)');
	$num = @mysql_num_rows($sqlSelectResult);
	if ($num > 0) {
		//更改密碼
		$sql = "UPDATE account SET password = '".$sqlCommandUpdateData["newPassword"]."' WHERE account = '".$sqlCommandUpdateData["account"]."'";
		$sqlResult = mysql_query($sql) or die('MySQL query error(Update)');
	} else {
		echo "密碼輸入錯誤";
		break;
	}

	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error update record: ".mysql_error($link);
	}
?> 