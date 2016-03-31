<?php
	session_start();

	if (!$_SESSION['s_user']) {
		header("Location:/backend/login.php");
	} else {
		session_destroy();
		echo "<script>alert('已登出');location.href='/backend/login.php'</script>";
	}

	//For PHP 5.3
	/*
	if (!session_is_registered("s_user")) {
	} else {
		session_destroy();
		echo "<script>alert('已登出');location.href='/backend/login.php'</script>";
	}
	*/
?>