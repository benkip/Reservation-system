<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_POST['update'])) {
	$no=$_POST['room_number'];
	$name=$_POST['name'];
	$tp=$_POST['room_type'];
	$des=$_POST['description'];
	$query="update room set name='".$name."' , cat_id='".$tp."' , room_id='".$des."' where room_no='".$no."'";
	updateRoom($query);
	header('Location: room.php');
}
if(isset($_GET['number'])){
	$number=$_GET['number'];
	$rooms=getRoom($number);
}
if(isset($_POST['add'])) {
	$no=getUserId();
	$name=$_POST['name'];
	$email=$_POST['email'];
	$pwd=$_POST['pwd1'];
	$pwd=md5($pwd);
	$query="insert into users values ('".$no."','".$email."' , '".$name."' , '".$pwd."' )";
	UpdateRoom($query);
	header('Location: users.php');
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
<script src="javascript/jquery.js"></script>
<script>
$(function() {
		$("#submit").click(function(){
        $(".error").hide();
        var hasError = false;
        var passwordVal = $("#password").val();
        var checkVal = $("#password-check").val();
        if (passwordVal == '') {
            $("#password").after('<span class="error">Please enter a password.</span>');
            hasError = true;
        } else if (checkVal == '') {
            $("#password-check").after('<span class="error">Please re-enter your password.</span>');
            hasError = true;
        } else if (passwordVal != checkVal ) {
            $("#password-check").after('<span class="error">Passwords do not match.</span>');
            hasError = true;
        }
        if(hasError == true) {return false;}
    });
});
</script>
</head>

<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li><a href="users.php">Users</a></li>
<li class="active"><a href="adduser.php">Add User</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1> Add User</h1>
<div class="main-content">
<div class="data-form">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>User Id</label><input type="text" name="room_number" value="<?php echo getUserId(); ?>" disabled>
<label>User Name </label><input type="text" name="name"  required>
<label>Email Address </label> <input type="email" name="email" value="email address">
<label>Password </label><input type="password" name="pwd1" id="password">
<label>Confirm Password </label><input type="password" name="pwd2" id="password-check">
<div class="controls">
<input type="reset" value="Clear" class="form-reset">
<input type="submit" value="Save"  name="add" class="form-submit" id="submit">
</div>
</form>
</div>
</div><!--end of main-content-->
</div><!--end of content-->

</body>
</html>
<?php } ?>