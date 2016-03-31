<?php
	require "Main/phpFunction/mysqlconf.php";
	$account = $_POST['account'];
	$password = $_POST['password'];

	$sql = "SELECT * FROM account WHERE account ='$account' AND password ='$password'";
	$result = mysql_query($sql);
	$num = @mysql_num_rows($result);
	if ($num >= 1) {
		session_start();
		while($row = mysql_fetch_array($result)) {
			$_SESSION['s_user'] =  $row["account"];
			$_SESSION['s_userAuthority'] =  $row["authority"];
		}

		//For PHP5.3
		/*
		session_register("s_user");
		$s_user = $account;
		*/

		header("Location:/backend/Main/expiredGeneralUpdateDelete.php");
		echo "成功";
	} else {
		echo "<script>alert('登入失敗');location.href = 'login.php'</script>";
	}
?>