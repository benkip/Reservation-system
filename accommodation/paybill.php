<?php
require_once('lib.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
</head>

<body>
<div class="main-content">
<form action="checkout.php" method="post">
<?php
if(isset($_GET['id'])){
	$cust=$_GET['id'];
} ?>
<input type="hidden" name="customer" value="<?php echo $cust; ?>">
<div class='voucher'>
<div class='logo-img'><img src='images/logo-voucher.png' width='144'  /></div>
<div class='top-invoice'>
<h4>Guest Details</h4>
<div class='top-item'><label>Guest Name:</label><label> 
 <?php $name=getCustName($cust);
 echo $name[0]['cust_name']; ?>
 </label></div>
<div class='top-item'><label>Arrival Date:</label><label> 
<?php $qry="select * from reservation where cust_id='$cust'";
$result=getData($qry);
$date=date_create($result[0]['arrival_date']);
echo date_format($date,"l, d F Y"); ?></label></div>
<div class='top-item'><label>Room No:</label><label>1,5</label></div>
<div class='top-item'><label>Departure Date:</label><label>
<?php
$date=date_create($result[0]['departure_date']);
echo date_format($date,"l, d F Y"); ?></label></div>
<div class='top-item'><label>Mobile No:</label><label> 
<?php $phone=getCust($cust);
echo $phone[0]['phone_no']; ?></label></div>

<div class='top-item'><label>Email: </label><label> 
<?php echo $phone[0]['email_address']; ?></label></div>
</div>
<div class='invoice-table'>
<table cellspacing='0' border='0'>
<tr><td>Item Description</td><td>Charges</td><td>Credit</td><td>Balance</td></tr>
 <?php $qry="select * from invoice where cust_id=$cust";
$data=getData($qry);
$total=0;
foreach($data as $val):
$sect=getSectionName($val['section_id']);
$id=$val['section_id']; ?>
<input type="hidden" name="section[]" value="<?php echo $id; ?>">
<tr><td><?php echo $sect[0]['section_name']; ?></td><td>
<?php $qy="select sum(amount) as amount from invoice where cust_id=$cust and section_id=$id";
$charge=getData($qy);
$ch=$charge[0]['amount'];
echo number_format($charge[0]['amount'], 2, '.', ','); ?></td><td>
<?php $qy="select sum(amount) as amount from payment where cust_id=$cust and section_id=$id";
$credit=getData($qy);
$cr=$credit[0]['amount'];
echo number_format($credit[0]['amount'], 2, '.', ','); ?></td><td>
<?php $bal=$ch-$cr;
$total=$total+$bal;
echo number_format($bal, 2, '.', ','); ?><input type="hidden" name="balance[]" value="<?php echo $bal; ?>"></td></tr>
<?php endforeach; ?>
<tr><td colspan="3">Total Amount</td><td><?php echo number_format($total, 2, '.', ','); ?></td></tr>
</table>
</div>

</div><!--end of voucher-->
<label>Amount Paid</label><input type="text" name="amount" required>
<input type="submit" name="submit" value="Submit" class="submit" >
</form>
</div>
</body>
</html>