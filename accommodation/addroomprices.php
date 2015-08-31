<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_POST['delete'])) {
	$data=$_POST['room'];
	
	for($i=0; $i<count($data); $i++) {
		deleteRoom($data[$i]);
	}
}
if(isset($_POST['submit'])) {
	$type=$_POST['room_type'];
	$size=$_POST['description'];
	$rate=$_POST['rate'];
	$season_id=$_POST['season'];
	$query="select * from room_rates where cat_id='".$type."' and room_id='".$size."' and season_id='".$season_id."'";
	$res=getData($query);	
	if(count($res)!=0) {
		$error='The rates already exists in the database!';
	} else {
		$query="insert into room_rates (room_id, cat_id, rate, season_id) values ('".$size."','".$type."','".$rate."','".$season_id."')";
		update($query);
		header('Location: roomprices.php');
	}
	
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
<li class="active"><a href="addroomprices.php">Add Rate</a></li>
<li><a href="season.php">Seasons</a></li>
<li><a href="addseason.php">Add Season</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Add Rate</h1>
<div class="main-content">
<div class="error-bar"><?php if(isset($error)) {	echo $error; }?> </div>
<div class="data-form">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Room Type :</label><select name="room_type"><?php
 $type=getRoomType();
 foreach($type as $value) : ?><option value="<?php echo $value['cat_id']; ?>"><?php echo $value['category']; ?></option><?php endforeach; ?></select>
<label>Room size :</label><select name="description"><?php
 $type=getRoomSize();
 foreach($type as $value) : ?><option value="<?php echo $value['room_id']; ?>"><?php echo $value['room_description']; ?></option><?php endforeach; ?></select>

<label>Rate :</label><input type="text" name="rate" value="0">
<label>Season :</label><select name="season"><?php
 $type=getSeasons();
 foreach($type as $value) : ?><option value="<?php echo $value['season_id']; ?>"><?php echo $value['season_name']; ?></option><?php endforeach; ?></select>
<div class="controls">
<input type="reset" value="Reset" class="form-reset">
<input type="submit" value="Save" name="submit" class="form-submit"> 
</div>
</form>
</div>
</div><!--end of main-content-->
</div><!--end of content-->

</body>
</html>
<?php }?>