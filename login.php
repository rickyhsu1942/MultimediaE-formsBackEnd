<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<link href="css/style.css" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		
</head>
<body>
	<div class="main">
				 <!-----start-main---->
				 <h2>多媒體電子表單後台登入</h2>
				 <form method="post" name="login" action="auth.php">
		         	<div class="clear"> </div>
		            <div class="lable-2">
		           		<font color="white">帳號</font><br>
		                <input type="text" name="account" class="text" value="" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '';}">
		                <font color="white">密碼</font>
		                <input type="password" name="password" class="text" value="" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = '';}">
					</div>
					<div class="clear"> </div>
					<div class="submit">
						<input type="submit" value="登入" >
					</div>
					<div class="clear"> </div>
				</form>
		<!-----//end-main---->
	</div>
</body>
</html>