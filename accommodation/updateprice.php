<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_GET['cat']) && isset($_GET['id'])){
	$type=$_GET['cat'];
	$size=$_GET['id'];
	$query=" select rate, category, room_description,room_rates.room_id, room_rates.cat_id  from room_rates, room_type, room_size where room_rates.room_id=room_size.room_id and room_type.cat_id=room_rates.cat_id and room_rates.cat_id='".$type."' and room_rates.room_id='".$size."'";
	$res=getData($query);
	foreach($res as $value) :
		$type=$value['category'];
		$size=$value['room_description'];
		$id=$value['room_id'];
		$cat=$value['cat_id'];
		$rate=$value['rate'];
		
	endforeach;	
}
if(isset($_POST['submit'])) {
	$price=$_POST['rate'];
	$id=$_POST['id'];
	$cat=$_POST['cat'];
	$query="update room_rates set rate='".$price."' where room_id='".$id."' and cat_id='".$cat."'";
	update($query);
	header('Location: roomprices.php');
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
<li><a href="roomprices.php">Rates</a></li>
<li><a href="addroomprices.php">Add Rate</a></li>
<li class="active"><a href="updateprice.php">Update Rate</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1> Update Rate</h1>
<div class="main-content">
<div class="error-bar"><?php if(isset($error)) {	echo $error; }?> </div>
<div class="data-form">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Room Type :</label><input type="text" value="<?php echo $type ?>" readonly>
<label>Room size :</label><input type="text" value="<?php echo $size; ?>" readonly>
<label>Rate</label><input type="text" name="rate" value="<?php echo $rate; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="cat" value="<?php echo $cat; ?>">
<div class="controls">
<input type="reset" value="Reset" name="reset" class="form-reset"> 
<input type="submit" value="Save" name="submit" class="form-submit"> 
</div>
</form>
</div>
</div><!--end of main-content-->
</div><!--end of content-->
</body>
</html>
<?php } ?>