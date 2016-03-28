<?php

?>
<html> 
<head> 
<title>Sign-In</title> 
</head> 

<body id="body-color"> 
	<div id="Sign-In"> 
		<fieldset style="width:30%">
		<legend>LOG-IN HERE</legend> 
			<form method="POST" action="connectivity.php">
			User <br>
			<input type="text" name="user" size="40">
			<br> Password 
			<br>
			<input type="password" name="pass" size="40">
			<br> 
			<input id="button" type="submit" name="submit" value="Log-In"> 
			</form> 
		</fieldset> 
	</div> 
</body> 
</html> 
<?php define('DB_HOST', 'localhost'); define('DB_NAME', 'practice'); define('DB_USER','root'); define('DB_PASSWORD',''); $con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error()); $db=mysql_select_db(DB_NAME,$con) or die("Failed to connect to MySQL: " . mysql_error()); /* $ID = $_POST['user']; $Password = $_POST['pass']; */ function SignIn() { session_start(); //starting the session for user profile page if(!empty($_POST['user'])) //checking the 'user' name which is from Sign-In.html, is it empty or have some text { $query = mysql_query("SELECT * FROM UserName where userName = '$_POST[user]' AND pass = '$_POST[pass]'") or die(mysql_error()); $row = mysql_fetch_array($query) or die(mysql_error()); if(!empty($row['userName']) AND !empty($row['pass'])) { $_SESSION['userName'] = $row['pass']; echo "SUCCESSFULLY LOGIN TO USER PROFILE PAGE..."; } else { echo "SORRY... YOU ENTERD WRONG ID AND PASSWORD... PLEASE RETRY..."; } } } if(isset($_POST['submit'])) { SignIn(); } ?>

Read more: http://mrbool.com/how-to-create-a-login-page-with-php-and-mysql/28656#ixzz3x9mMzYd6
