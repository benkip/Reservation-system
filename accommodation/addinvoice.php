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
	header('Location: room.php');
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
<li><a href="invoices.php">Invoices</a></li>
<li class="active"><a href="addinvoice.php">Add Invoice</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1> Add Invoice</h1>
<div class="main-content">
<div class="data-form">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Status</label><select name="status"><option value="pending">Pending</option><option value="paid">Paid</option><option value="cancelled">Cancelled</option></select>
<label>Customer Name </label><select name="customer"><?php $customer=getCustomers();
 foreach($customer as $value) : ?><option value="<?php echo $value['cust_id']; ?>"><?php echo $value['cust_name']; ?></option>
 <?php endforeach; ?></select>
<label>Invoice Number </label><input type="text" name="name" value="<?php echo count(getInvoices())+1; ?>"  disabled>
<label>Date issued </label> <select name="room_type" class="select" ><?php
 $type=getRoomType();
 foreach($type as $value) : ?><option value="<?php echo $value['cat_id']; ?>"><?php echo $value['category']; ?></option>
 <?php endforeach; ?></select>
<label>Description </label><select name="description" class="select"><?php
 $type=getRoomSize();
 foreach($type as $value) : ?><option value="<?php echo $value['room_id']; ?>"><?php echo $value['room_description']; ?></option>
<?php endforeach; ?></select>
<div class="controls">
<input type="reset" value="Clear" class="form-reset">
<input type="submit" value="Save"  name="add" class="form-submit">
</div>
</form>
</div>
</div><!--end of main-content-->
</div><!--end of content-->

</body>
</html>
<?php }?>