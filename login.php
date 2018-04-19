<html>
<head>
	<title>Login</title>
</head>
<body>
	<form action="login.php" method="post">
		<input type="text" placeholder="username" name="uname">
		<button name="submit" type="submit">Login</button>
	</form>
	
</body>
</html>
<?php
ob_start();
session_start();
$names = array("subrata","arka","kunal","sumalya","rupa","sachin","priyanshu");

if(isset($_SESSION['uid']))
	header('Location:index.php');
if(isset($_POST['submit'])){
	foreach($names as $name){
		if($name === $_POST['uname']){
			$_SESSION['uid'] = $name;
			header('Location:chatroom.php');
		}	
	}	
}



