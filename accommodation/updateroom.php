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
	$no=getRoomId();
	$name=$_POST['name'];
	$tp=$_POST['room_type'];
	$des=$_POST['description'];
	$query="insert into room values ('".$no."','".$name."' , '".$tp."' , '".$des."' )";
	UpdateRoom($query);
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
</head>

<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li><a href="room.php">Rooms</a></li>
<li><a href="addroom.php">Add Room</a></li>
<li class="active"><a href="addroom.php">Update Room</a></li>

</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Update Room</h1>
<div class="main-content">
<div class="data-form">
<?php 
if(isset($rooms)) {
foreach($rooms as $val) : ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">  
<label>Room Number :</label><input type="text" name="room_number" class="item" value="<?php echo $val['room_no']; ?>" readonly >
<label>Name</label> <input type="text" name="name" class="item" value="<?php echo $val['name']; ?>">
<label>Room Type </label><select name="room_type" class="item"><?php
 $type=getRoomType();
 foreach($type as $value) : ?><option value="<?php echo $value['cat_id']; ?>" <?php if($value['cat_id']==$val['cat_id']){ echo "selected"; } ?>><?php echo $value['category']; ?></option>
 <?php endforeach; ?></select>
<label>Description</label> <select name="description" class="item"><?php
 $type=getRoomSize();
 foreach($type as $value) : ?><option value="<?php echo $value['room_id']; ?>" <?php if($value['room_id']==$val['room_id']){ echo "selected"; } ?>><?php echo $value['room_description']; ?></option>
 <?php endforeach; ?></select>
 

<?php endforeach;?>
<div class="controls">
<input type="reset" value="Reset" class="form-reset">
<input type="submit" value="Save" class="form-submit" name="update">
</div>
</form><?php } ?>
</div>
</div><!--end of main-content-->
</div><!--end of content-->
</body>
</html>
<?php } ?>