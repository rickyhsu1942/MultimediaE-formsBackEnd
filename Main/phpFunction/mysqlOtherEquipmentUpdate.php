<?php
	require "mysqlconf.php";
	
	if ($_POST['sqlCommandUpdateData']) {
		$sqlCommandUpdateData = $_POST['sqlCommandUpdateData'];
	} else {
		$sqlCommandUpdateData = "";
	}
	
	$sql = "UPDATE otherproperty SET equipmentQuantity=".$sqlCommandUpdateData["equipmentQuantity"].",
			lentQuantity=".$sqlCommandUpdateData["lentQuantity"].",location='".$sqlCommandUpdateData["location"]."',
			imageName='".$sqlCommandUpdateData["imageName"]."' WHERE equipmentNumber='".$sqlCommandUpdateData["equipmentNumber"]."'";
	$sqlResult = mysql_query($sql) or die('MySQL query error:'.mysql_error($link));

	if ($sqlResult) {
		echo 1;
	} else {
		echo "Error updating record: ".mysql_error($link);
	}
	
?> 