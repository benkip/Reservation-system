<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_GET['customer'])){
	$cust=$_GET['customer'];
	$qry="select * from reservation where cust_id=$cust";
	$data=getData($qry);
	$arrival=$data[0]['arrival_date'];
	$departure=$data[0]['departure_date'];
	$from=$data[0]['arrival_date'];
	$to=$data[0]['departure_date'];
	//check room availability
	$room=check_availablestandard($from,$to);
	$stand_single=getAvailablerooms($from,$to,1,1);	
	$stand_double=getAvailablerooms($from,$to,1,2);	
	$stand_triple=getAvailablerooms($from,$to,1,3);
	$deluxe_single=getAvailablerooms($from,$to,2,1);
	$deluxe_double=getAvailablerooms($from,$to,2,2);
	$sup_single=getAvailablerooms($from,$to,3,1);
	$sup_double=getAvailablerooms($from,$to,3,2);
	$sup_triple=getAvailablerooms($from,$to,3,3);
}
if(isset($_POST['add'])) {
	$amount=0;
	$cust=$_POST['customer'];
	$qry="select * from reservation where cust_id=$cust";
	$data=getData($qry);
	$arrival=$data[0]['arrival_date'];
	$departure=$data[0]['departure_date'];
	$from=$data[0]['arrival_date'];
	$to=$data[0]['departure_date'];
	$no=$data[0]['order_id'];
	
	$totalamnt=$_POST['total-amount'];
	$payment=$_POST['amount'];
	
	$date=date_create($from);
	$from=date_format($date,"Y-m-d 11:00:00");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d 14:00:00");
	$date_issue=date('Y-m-d');
	
	$room=check_availablestandard($from,$to);
	$stand_single=getAvailablerooms($from,$to,1,1);	
	$stand_double=getAvailablerooms($from,$to,1,2);	
	$stand_triple=getAvailablerooms($from,$to,1,3);
	$deluxe_single=getAvailablerooms($from,$to,2,1);
	$deluxe_double=getAvailablerooms($from,$to,2,2);
	$sup_single=getAvailablerooms($from,$to,3,1);
	$sup_double=getAvailablerooms($from,$to,3,2);
	$sup_triple=getAvailablerooms($from,$to,3,3);
	
	$query="select * from invoice where cust_id=$cust and section_id=5";
	$invoice=getData($query);
	$invoice_no=$invoice[0]['invoice_number'];
	$amnt=$invoice[0]['amount']+$totalamnt;
	$query="update invoice set amount=$amnt where cust_id='".$cust."' and section_id='5'";
	update($query);
	$payid=getPayment(); 
	if($payment>0) {
	//insert amount paid into payment
	$query="insert into payment (pay_id, amount, section_id,cust_id) values ('".$payid."','".$payment."','5','".$cust."')";
	update($query);
	}
	$cust_id=$cust;
	//Standard single rooms
	if(isset($_POST['standard_single'])){		
		$adult_single=$_POST['adult_single'];
		$child_single=$_POST['child_single'];
		$child_singleb=$_POST['child_singleB'];
		$rooms_single=$_POST['stand_single'];
		for($x=0; $x<$rooms_single; $x++) {
			$room_no=$stand_single[$x]['room_no'];
			//$no=getReserveId();
			$query="insert into room_reservation (order_id, room_no,adults,child_a,child_b) values ('".$no."','".$room_no."' , '".$adult_single."','".$child_single."','".$child_singleb."' )";
			//echo $query."<br>";
			
			update($query);
		}
		
	}	
	//Standard double rooms
	if(isset($_POST['standard_double'])){		
		$adult_double=$_POST['adult_double'];
		$child_double=$_POST['child_double'];
		$child_doubleb=$_POST['child_doubleB'];
		$rooms_double=$_POST['stand_double'];
		for($x=0; $x<$rooms_double; $x++) {
			$room_no=$stand_double[$x]['room_no'];
			//$no=getReserveId();
			$query="insert into room_reservation (order_id, room_no, adults,child_a, child_b) values ('".$no."','".$room_no."' , '".$adult_double."','".$child_double."','".$child_doubleb."' )";
			
			update($query);
		}
		
	
	}
	//Standard Triple rooms
	if(isset($_POST['standard_triple'])){		
		$adult_triple=$_POST['adult_triple'];
		$child_triple=$_POST['child_triple'];
		$child_tripleb=$_POST['child_tripleB'];
		$rooms_triple=$_POST['stand_triple'];
		for($x=0; $x<$rooms_triple; $x++) {
			$room_no=$stand_triple[$x]['room_no'];
			//$no=getReserveId();
			$query="insert into room_reservation (order_id, room_no, adults,child_a, child_b) values ('".$no."','".$room_no."' , '".$adult_triple."','".$child_triple."','".$child_tripleb."' )";
			
			update($query);
		}
		
	}
	//Deluxe Single rooms
	if(isset($_POST['deluxe_single'])){		
		$adult_deluxesingle=$_POST['adult_deluxesingle'];
		$child_deluxesingle=$_POST['child_deluxesingle'];
		$child_deluxesingleb=$_POST['child_deluxesingleB'];
		$rooms_delsingle=$_POST['del_single'];
		for($x=0; $x<$rooms_delsingle; $x++) {
			$room_no=$deluxe_single[$x]['room_no'];
			//$no=getReserveId();
			$query="insert into room_reservation (order_id, room_no, adults,child_a, child_b) values ('".$no."','".$room_no."' , '".$adult_deluxesingle."','".$child_deluxesingle."','".$child_deluxesingle."' )";
			update($query);
		}
		
	}
	//Deluxe double rooms
	if(isset($_POST['deluxe_double'])){		
		$adult_deluxedouble=$_POST['adult_deluxedouble'];
		$child_deluxedouble=$_POST['child_deluxedouble'];
		$child_deluxedoubleb=$_POST['child_deluxedoubleB'];
		$rooms_deldouble=$_POST['del_double'];		
		for($x=0; $x<$rooms_deldouble; $x++) {
			$room_no=$deluxe_double[$x]['room_no'];
			//$no=getReserveId();
			$query="insert into room_reservation (order_id, room_no, adults,child_a,child_b) values ('".$no."','".$room_no."' , '".$adult_deluxedouble."','".$child_deluxedouble."','".$child_deluxedoubleb."' )";
			update($query);
		}
		
	}
	//Superior double rooms
	if(isset($_POST['sup_double'])){		
		$adult_supdouble=$_POST['adult_supdouble'];
		$child_supdouble=$_POST['child_supdouble'];
		$child_supdoubleb=$_POST['child_supdoubleB'];
		$rooms_supdouble=$_POST['superior_double'];
		
		for($x=0; $x<$rooms_supdouble; $x++) {
			$room_no=$sup_double[$x]['room_no'];
			$query="insert into room_reservation (order_id, room_no,adults,child_a,child_b) values ('".$no."','".$room_no."' , '".$adult_supdouble."','".$child_supdouble."','".$child_supdoubleb."' )";
			
			update($query);
		}
	}
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
<li class="active"><a href="addreserve.php">Add Reservation</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Add Room</h1>
<div class="main-content">
<div class="data-form">

   <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
   <input type="hidden" name="customer" value="<?php echo $cust; ?>">
   <?php  if(count($stand_single)>0 || count($stand_double)>0 || count($stand_triple)>0 || count($deluxe_single)>0 || count($deluxe_double)>0 || count($sup_double)>0)  { 
    //check available standard rooms
	
   ?>
    
<input type="hidden" name="room_type"  value="<?php //echo $_POST['room_type']; ?>"  >
<input type="hidden" name="room_description"  value="<?php //echo $_POST['description']; ?>"  >
<label>Arrival date : </label><input type="text" value="<?php echo $arrival; ?>" readonly>
<label>Departure date :</label><input type="text" value="<?php echo $departure; ?>" readonly>
<!--<label>Room Type :</label>--><input type="hidden" name="cat" value="<?php //$cat=getRoomType($_POST['room_type']);  echo $cat[0]['category'];?>" disabled> <?php  ?>
<!--<label>Room Description :</label>--><input type="hidden" name="size"  value="<?php //$size=getRoomSize($_POST['description']); echo $size[0]['room_description']; ?>" disabled>

<label>Guest Name :</label><?php $name=getCustName($cust); ?><input type="text" value="<?php echo $name[0]['cust_name']; ?>" readonly>
<label>Total Amount :</label><input type="number" name="total-amount" required />
<label>Amount paid :</label><input type="number" name="amount" required />

    <?php if(count($stand_single)>0 || count($stand_double)>0 || count($stand_triple)>0) { ?>
    <table cellspacing="0">
    <caption>STANDARD ROOMS</caption>
    <tr height="30" style="background:#94b52c;"><td>Room type</td>
    <td colspan="3">Max. Occupancy</td>
    <td>Rooms</td></tr>
    <tr height="20">
    <td>&nbsp;</td>
    <td>Adults</td>
    <td>Children(5-12 yrs)</td>
    <td>Children(below 5 yrs)</td>
    
    </tr>
     <?php if(count($stand_single)>0) { ?>
    <tr>
    <td><input type="checkbox" name="standard_single" value="single" />Single Room </td>
    <td><select name="adult_single"><option value="0">0</option><option value="1">1</option> </select></td>
    <td><select name="child_single"><option value="0">0</option></select></td>
    <td><select name="child_singleB"><option value="0">0</option></select>
     </td>
    <td><select name="stand_single"> <?php foreach($stand_single as $i=>$value) : ?>
	 <option value="<?php echo $i+1; ?>"><?php echo $i+1; ?></option><?php endforeach; ?></select></td>
    </tr><?php } ?>
    <?php if(count($stand_double)>0) { ?>
    <tr>
    <td><input type="checkbox" name="standard_double" value="double" />Double Room</td>
    <td><select name="adult_double"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select></td>
    <td><select name="child_double"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select></td>
    <td><select name="child_doubleB"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select>
     </td>
    <td><select name="stand_double"><?php foreach($stand_double as $i=>$value) : ?>
	 <option value="<?php echo $i+1; ?>"><?php echo $i+1; ?></option><?php endforeach; ?></select></td>
    </tr>
    <?php } ?>
    <?php if(count($stand_triple)>0) { ?>
    <tr>
    <td><input type="checkbox" name="standard_triple" value="triple" />Triple Room</td>
    <td><select name="adult_triple"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>
    <td><select name="child_triple"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>
    <td><select name="child_tripleB"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>
     </td>
    <td><select name="stand_triple"><?php foreach($stand_triple as $i=>$value) : ?>
	 <option value="<?php echo $i+1; ?>"><?php echo $i+1; ?></option><?php endforeach; ?></select></td>
    </tr><?php } ?></table> <?php } ?>
    <div class="room-package">
    <?php 
	if(count($deluxe_single)>0 || count($deluxe_double)>0 )  { ?>
    
    <table cellspacing="0">
    <caption>DELUXE ROOMS</caption>
    <tr height="30" style="background:#94b52c;"><td>Room type</td>
    <td colspan="3">Max. Occupancy</td>
    <td>Rooms</td>
 	</tr>
    <tr height="20">
    <td>&nbsp;</td>
    <td>Adults</td>
    <td>Children(5-12 yrs)</td>
    <td>Children(below 5 yrs)</td>
    <td>&nbsp;</td>
    </tr>
     <?php if(count($deluxe_single)>0) { ?>
    <tr>
    <td><input type="checkbox" name="deluxe_single" value="single" />Single Room</td>
    <td><select name="adult_deluxesingle"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>
    <td><select name="child_deluxesingle"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>
    <td><select name="child_deluxesingleB"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>
     </td>
    <td><select name="del_single"><?php foreach($deluxe_single as $i=>$value) : ?>
	 <option value="<?php echo $i+1; ?>"><?php echo $i+1; ?></option><?php endforeach; ?></select></td>
    </tr>
    <?php } ?>
    <?php if(count($deluxe_double)>0) { ?>
    <tr>
    <td><input type="checkbox" name="deluxe_double" value="double" />Double Room</td>
    <td><select name="adult_deluxedouble"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>
    <td><select name="child_deluxedouble"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select></td>
    <td><select name="child_deluxedoubleB"><option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option></select>
     </td>
    <td><select name="del_double"><?php foreach($deluxe_double as $i=>$value) : ?>
	 <option value="<?php echo $i+1; ?>"><?php echo $i+1; ?></option><?php endforeach; ?></select> </td>
    </tr><?php } ?></table> <?php } ?>
    
    <?php 
	if(count($sup_double)>0 )  { ?>    
    <table cellspacing="0">
    <caption>SUPERIOR ROOMS</caption>
    <tr height="30" style="background:#94b52c;"><td>Room type</td>
    <td colspan="3">Max. Occupancy</td>
    <td>Rooms</td>
 	</tr>
    <tr height="20">
    <td>&nbsp;</td>
    <td>Adults</td>
    <td>Children(5-12 yrs)</td>
    <td>Children(below 5 yrs)</td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="sup_double" value="double" />Double Room</td>
    <td><select name="adult_supdouble"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select></td>
    <td><select name="child_supdouble"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td>
    <td><select name="child_supdoubleB"><option value="0">0</option><option value="1">1</option><option value="2">2</option></select><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>
     </td>
    <td><select name="superior_double"><?php foreach($sup_double as $i=>$value) : ?>
	 <option value="<?php echo $i+1; ?>"><?php echo $i+1; ?></option><?php endforeach; ?></select> </td>
    </tr></table> <?php } ?>
     
<div class="controls">
<a href="addreserve.php"><input type="button" value="Back" class="back"></a>
<input type="reset" value="Reset" class="form-reset">
<input type="submit" value="Confirm Reservation" class="form-submit" name="add">
</div>
</form>
 <?php  
	} else {
		echo "<h3>No Rooms Available</h3>";
	} ?>
</div>
</div><!--end of main-content-->
</div><!--end of content-->
</body>
</html>
<?php } ?>