<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title>注册</title>
	<link rel="stylesheet" type="text/css" href="css/reg.css">
	<script type="text/javascript" src = "js/md5.js"></script>
	<script type="text/javascript" src = "js/reg.js"></script>
</head>
<body>
	<?php
		require_once("filter.php");
		header('Cache-control: private, must-revalidate');
		session_cache_limiter('private, must-revalidate');
		session_start();
	?>
	<form method="post" action="register.php">
		<h2>注册</h2>
		<div class="reg-info">
			<label>账号: </label><input type="text" name="account">
		</div>

		<div class="reg-info">
			<label>密码: </label><input type="password" class="password" name="password">
		</div>

		<div class="reg-info">
			<label>确认密码: </label><input type="password" name="confirmPW">
		</div>

		<div class="reg-info">
			<label>姓名: </label><input type="text" name="name">
		</div>

		<input type="submit" id="submit" class="btn" value="提交注册">
	
	<?php
	if((isset($_POST["account"])) && (isset($_POST["password"]))) {
		$account = $_POST["account"];
		$password = $_POST["password"];
		$confirmPW = $_POST["confirmPW"];
		$name = $_POST["name"];

		//字符过滤和HTML注入
		$account = strCheck($account);
		$password = strCheck($password);
		$name = strCheck($name);

		$account = postCheck("账号",$account, 3, 10);
		if ($account) {
			$password = postCheck("密码", $password, 3, 50);
			if ($password) {
				$name = postCheck("姓名", $name, 2, 20);
			}
		}
		

		if ($account && $password && $name) {
			if ($password == $confirmPW) {
				//创建对象并打开连接，最后一个参数是选择的数据库名称
				$mysqli = new mysqli('localhost','root','123456','test');
				//检查连接是否成功
				if (mysqli_connect_errno()){
					die('Unable to connect!'). mysqli_connect_error();
				}
				$passwordHash = password_hash($password, PASSWORD_DEFAULT);
				$sql = "INSERT INTO user VALUES ('$account', '$passwordHash', '$name')";

				//执行sql语句，完全面向对象的
				$result = $mysqli->query($sql);
				if ($result) {
					header ( "Location:login.php" );
				}

				else {
					echo "<p>该账户已经存在！</p>";
				}
			}
			
			else {
				echo "<p>两次密码不一致！</p>";
			}
		}
	}
	?>
	</form>
</body>
</html>