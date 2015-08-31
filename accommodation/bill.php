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
<li class="active"><a href="bill.php">Orders</a></li>
<li><a href="addorder.php">Add Order</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Orders</h1>
<div class="main-content">
<div class="data-form">

<table id="tablesorter-demo" class="tablesorter" cellspacing="0" >
<thead>
<tr class="table-header" ><th width="2"><input type="checkbox" name="all" >  </th>
<th>Section</th>
<th>Customer Details</th>
<th>Total Amount</th>
<th>Amount Paid</th>
<th>Username</th>
<th>Actions</th></tr></thead>
<tbody>
<?php
$order_date=date('Y-m-d');
$query="select * from invoice where date_issued='".$order_date."'";
$bill=getData($query);
/*$query="select invoice_number, invoice.section_id, invoice.amount  from invoice, payment where date_issued='".$order_date."' and invoice.section_id=payment.section_id" ;
$bill=getData($query);*/

foreach($bill as $i=>$value):
?>
<tr class="table-content" ><td><input type="checkbox" name="bill[]" value="<?php echo $value['invoice_number']; ?>" /></td>
<td><?php $sect=getSectionName($value['section_id']); echo $sect[0]['section_name']; ?></td>
<td><?php
$cust=getCustName($value['cust_id']); echo $cust[0]['cust_name'];
?></td>
<td><?php echo $value['amount']; ?></td>
<td><?php $qry="select sum(amount) as amount from payment where cust_id='".$value['cust_id']."' and section_id='".$value['section_id']."'";  $amt=getData($qry);  echo $amt[0]['amount']; ?></td>
<td><?php $user=getUser($value['user_id']); echo $user[0]['username']; ?></td>
<td>&nbsp;</td>
</tr></form>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div><!--end of main-content-->
</div><!--end of content-->
<!--<div id="footer" class="footer">
</div>-->
</body>
</html>
<?php } ?>