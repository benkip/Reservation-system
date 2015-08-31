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
if(isset($_POST['add'])){
	$total=$_POST['total'];
	$amount=$_POST['amount'];
	$section=$_POST['section'];
	$order_date=date("Y-m-d");
	$method=$_POST['method'];
	$client=$_POST['client'];
	//Get Invoice invoice id and insert data
	$query="select * from invoice where date_issued='".$order_date."' and section_id='".$section."' and cust_id='".$client."'";
	$state=getData($query);	
	if(empty($state)) {
		$id=getInvoiceId();
		$qry="insert into invoice (invoice_number, date_issued, status, amount,section_id,cust_id,user_id) values ('".$id."','".$order_date."','pending','".$total."','".$section."','".$client."','".$_SESSION['user_id']."')";
		update($qry); 
	} else {
		$id=$state[0]['invoice_number'];
		$total_amnt=$total+$state[0]['amount'];
		$qry="update invoice set amount='".$total_amnt."' where invoice_number='".$id."' and date_issued='".$order_date."'";

		update($qry);
	}
	//Insert Payment in payment Table
	$payid=getPayment();
	$query="insert into payment (pay_id, amount, cust_id,section_id) values ('".$payid."','".$amount."','".$client."','".$section."')";
	update($query);
	header('Location: bill.php');
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
<li><a href="bill.php">Orders</a></li>
<li class="active"><a href="addorder.php">Add Order</a></li>
<li><a href="item.php">Items</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Add Order</h1>
<div class="main-content">
<div class="data-form">
<form action="#" method="post" >
<label>Section :</label><select name="section"><?php $query="select * from section"; $result=getData($query); foreach($result as $val): ?><option value="<?php echo $val['section_id']; ?>"><?php echo $val['section_name']; ?></option><?php endforeach; ?></select>
<label>Client Name:</label><select name="client"><?php $query="select distinct cust_id from reservation where status='active'"; $result=getData($query); foreach($result as $val): ?><option value="<?php echo $val['cust_id']; ?>"><?php $cust=getCustName($val['cust_id']); echo $cust[0]['cust_name']; ?></option><?php endforeach; ?></select>
<label>Total Amount</label><input type="number" name="total">
<label>Amount Paid</label><input type="number" name="amount">
<label>Payment Status</label><select name="payment-status"><option value"1">Paid</option><option value="2">Unpaid</option></select>
<label>Receipt Number</label><input type="text" name"receipt">
<label>Payment Method</label><div class="payment"><input type="radio" name="method" checked><span>Card</span></div><div class="payment"><input type="radio" name="method"><span>Mpesa</span></div><div class="payment"><input type="radio" name="method"><span>Cash</span></div>
<div class="controls">
<input type="reset" value="Reset" class="form-reset">
<input type="submit" value="Confirm Reservation" class="form-submit" name="add">
</div>
</form>

</div>
</div><!--end of main-content-->
</div><!--end of content-->
<!--<div id="footer" class="footer">
</div>-->
</body>
</html>
<?php } ?>