<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_GET['order'])) {
	$order=$_GET['order'];
	$data=getReserve($order);
$from=$data[0]['arrival_date'];
$to=$data[0]['departure_date'];
}

if(isset($_POST['add'])) {
	
	$from=$_POST['arrival_date'];
	$to=$_POST['departure_date'];
	$order=$_POST['order'];
	$book=$_POST['book-date'];
	$tp=$_POST['room_type'];
	$des=$_POST['room_description'];
	$no=$_POST['room_no'];
	//$children=$_POST['children'];
	$fname=$_POST['fname'];
	$mname="";
	$lname=$_POST['lname'];
	$email=$_POST['email'];
	$tel=$_POST['phone'];
	$cust_id=$_POST['cust_id'];
	$adult=$_POST['adults'];
	$childa=$_POST['child_a'];
	$status=$_POST['status'];
	$date=date_create($from);
	$from=date_format($date,"Y-m-d");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d");
	$date=date_create($book);
	$book=date_format($date,"Y-m-d H:i:s");

	//update reservation
	$query="update reservation set adults='".$adult."', child_a='".$childa."', status='".$status."', user_id='".$_SESSION['user_id']."', booking_date='".$book."' where order_id='".$order."'";
	update($query);
	//update Customer details
	
	$query="update customer set fname='".$fname."', mname='".$mname."', lname='".$lname."', email_address='".$email."', phone_no='".$tel."' where cust_id='".$cust_id."'";
	update($query);
	
	header('Location: reservation.php');
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
<li><a href="reservation.php">Reservations</a></li>
<li class="active"><a href="updatereservation.php">Update Reservation</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Update Reservation</h1>
<div class="main-content">
<div class="data-form">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 

<label>Room Number : </label><input type="text" name="room_no"  value="<?php echo $data[0]['room_no']; ?>" readonly  >
<?php $roominfo=getRoom($data[0]['room_no']); ?>
<label> Room Description :</label><label><?php echo $roominfo[0]['category'].'&nbsp;'.$roominfo[0]['room_description']; ?></label>
<input type="hidden" name="order"  value="<?php echo $_GET['order']; ?>"  >
<input type="hidden" name="book-date"  value="<?php echo $data[0]['booking_date']; ?>"  >
<input type="hidden" name="room_description"  value="<?php echo $roominfo[0]['room_id']; ?>"  >
<input type="hidden" name="room_type"  value="<?php echo $roominfo[0]['cat_id']; ?>"  >
<label>Arrival date : </label><input type="text" name="arrival_date" value="<?php echo $from; ?>"  readonly >
<label>Departure date :</label><input type="text" name="departure_date"  value="<?php echo $to; ?>" readonly >
<label>Adults :</label><input type="text" name="adults" value="<?php echo $data[0]['Adults']; ?>" required />
<label>Children :</label><input type="text" name="child_a" value="<?php echo $data[0]['Child_A']; ?>" required />
<label>Status :</label><select name="status"><option value="active" <?php if($data[0]['status']=='Active') { echo 'selected'; }?>>Active</option><option value="Cancelled"<?php if($data[0]['status']=='Cancelled') { echo 'selected'; }?>>Cancelled</option></select>
<?php $customer=getCust($data[0]['cust_id']); ?>
<input type="hidden" name="cust_id"  value="<?php echo $data[0]['cust_id']; ?>"  >
<label>First Name :</label><input name="fname" type="text" value="<?php echo $customer[0]['fname']; ?>" required />
<label>Last Name :</label> <input name="lname" type="text" value="<?php echo $customer[0]['lname']; ?>" required />
<label>Email Address :</label><input type="email" name="email" value="<?php echo $customer[0]['email_address']; ?>" required />
<label>Phone Number :</label><input type="tel" name="phone" value="<?php echo $customer[0]['phone_no']; ?>" required />

     
<div class="controls">
<a href="reservation.php"><input type="button" value="Back" class="back"></a>
<input type="reset" value="Reset" class="form-reset">
<input type="submit" value="Confirm Reservation" class="form-submit" name="add">
</div>
</form>
</div>
</div><!--end of main-content-->
</div><!--end of content-->
</body>
</html>
<?php } ?>