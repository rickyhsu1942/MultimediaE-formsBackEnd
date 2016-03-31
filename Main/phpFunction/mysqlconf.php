<?
	//建立連線
	$link = mysql_connect("10.25.6.201","md","nutc4410") or die("連接資料庫失敗".mysql_error());

	//選擇資料庫
	mysql_select_db("mdnutc") or die("選擇資料庫失敗");
	mysql_query("set names utf8");

	/*
	echo $link."<br>";
	if($link) echo "YES";
	else echo "NO";
	*/
?>