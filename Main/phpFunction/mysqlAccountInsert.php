<?php
	require "mysqlconf.php";

	if ($_POST['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_POST['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}

	//查詢account的密碼是否正確
	$sqlSelectlentaccount = "SELECT * FROM account WHERE account = '".$sqlCommandUpdateData["account"]."'";
	$sqlSelectResult = mysql_query($sqlSelectlentaccount) or die('MySQL query error(SELECT)');
	$num = @mysql_num_rows($sqlSelectResult);
	if ($num < 1) {
		//更改密碼
		$sql = "INSERT INTO account (account,password,authority) VALUES('".$sqlCommandUpdateData["account"]."','".$sqlCommandUpdateData["password"]."',2)";
		$sqlResult = mysql_query($sql) or die('MySQL query error(Insert)');
	} else {
		echo "此帳號已重複";
		break;
	}

	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error insert record: ".mysql_error($link);
	}
?> 