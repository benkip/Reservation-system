<?php
require_once('lib.php');
$message="";
if(count($_POST)>0) {
$pwd=md5($_POST["pwd"]);
$query="SELECT * FROM users WHERE username='" . $_POST["usrname"] . "' and password = '".$pwd ."'";
$result = mysql_query($query);
$row  = mysql_fetch_array($result);
if(is_array($row)) {	
$_SESSION["user_id"] = $row['user_id'];
$_SESSION["user_name"] = $row['username'];
$_SESSION["last_acted_on"] = time();
} else {
$message = "Invalid Username or Password!";
}
}
if(isset($_SESSION["user_id"])) {
header("Location:home.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation Module</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
</head>

<body class="admin-login">
<div id="login">
<h3>Administration Panel</h3>
<form action="<?php  echo $_SERVER['PHP_SELF'];?>" method="post">
<p>Username: <input type="text" name="usrname" /></p>
<div class="clear"></div>
<p>Password: <input type="password" name="pwd" /></p>
<div class="clear"></div>
<input type="reset" value="Reset" style="background:#333; width:90px; border-radius:5px; color:#fff; padding:3px;">
<input type="submit" value="Login" style="background:#333; width:90px; border-radius:5px; color:#fff; padding:3px;">

</form>

</div>
</body>
</html>