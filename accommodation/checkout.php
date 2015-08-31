<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
require_once('library/admin-dashboard.php');
if(isset($_POST['delete'])) {
	$data=$_POST['room'];
	
	for($i=0; $i<count($data); $i++) {
		deleteRoom($data[$i]);
	}
}
if(isset($_POST['submit'])){
	$bal=array();
	$sect=array();
	$bal=$_POST['balance'];
	$sect=$_POST['section'];
	$cust=$_POST['customer'];
	$amount=$_POST['amount'];
	for($x=0; $x<count($bal); $x++) {		
		//Insert Payment in payment Table
		$payid=getPayment();
		$query="insert into payment (pay_id, amount, cust_id,section_id) values ('".$payid."','".$bal[$x]."','".$cust."','".$sect[$x]."')";
		update($query);	
	}
	$qry="update reservation set status='Completed' where cust_id='".$cust."'";
	update($qry);
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
	<script src="javascript/ui/core.js"></script>
    <script src="javascript/ui/widget.js"></script>
    <script src="javascript/ui/datepicker.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/jquery-latest.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/js/chili/chili-1.8b.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/docs/js/docs.js"></script>
	<script type="text/javascript">
	$(function() {		
		$("#tablesorter-demo").tablesorter({sortList:[[1,0],[1,1]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}}});
	});	
	</script>

 <script>
$(function() {
	$(window).load(function() {
        
	$('#main-content').css('height', window.innerHeight);
	});
	
});
</script>
</head>

<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li><a href="reservation.php">Reservations</a></li>
<li class="active"><a href="checkout.php">Check Out</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Guests Checking Out Today</h1>
<div class="main-content">
<div class="data-form">
<?php 
$query="select reservation.order_id, room_no, CONCAT(fname,' ', mname ,' ', lname) as cust_name ,phone_no,reservation.cust_id from reservation, customer, room_reservation where reservation.cust_id=customer.cust_id and room_reservation.order_id=reservation.order_id and departure_date='".date('Y-m-d 14:00:00')."' and status='active'";
$guest=getData($query);
if(!empty($guest)){
?>
<table id="tablesorter-demo" class="tablesorter" cellspacing="0" >
<thead>
<tr class="table-header" >
<th width="2"><input type="checkbox" name="order[]" value="1" /></th>
<th>Room_no</th>
<th>Customer Details</th>
<th>Contacts</th>
<th>Invoice</th>
<th>Actions</th></tr>
</thead>
<?php foreach($guest as $value) : 
	$link='checkout-invoice.php?id='.$value['cust_id'];
	$pay='paybill.php?id='.$value['cust_id']; ?>
<tbody><tr>
<td><input type="checkbox" name="order[]" value="<?php echo $value['order_id']; ?>" /></td>
<td><?php echo $value['room_no']; ?></td>
<td><?php echo $value['cust_name']; ?></td>
<td><?php echo $value['phone_no']; ?></td>
<td><a href="<?php echo $link; ?>" target="_blank"><img src="images/invoice.jpg" width="40"></a></td>
<td><a href="javascript: void(0)" onClick="window.open('<?php echo $pay; ?>','Add Items','width=800, height=500'); return false;"> Check out</a><img src="images/view.png" width="20"><img src="images/edit.png"><img src="images/delete.png"></td>
</tr></tbody>
<?php endforeach; ?>
</table>
<?php }  else {
echo "<h4> No Guest leaving today.</h4>";
}?>

</div>
</div><!--end of main-content-->
</div><!--end of content-->
<!--<div id="footer" class="footer">
</div>-->
</body>
</html>
<?php } ?>