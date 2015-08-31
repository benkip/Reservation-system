<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_GET['number'])){
	$number=$_GET['number'];
	$rooms=getInvoice($number);
}
if(isset($_POST['add'])) {
	$no=$_POST['invoice'];
	$status=$_POST['status'];
	$issue=$_POST['date_issued'];
	$amount=$_POST['amount'];
	$query="update invoice set date_issued='".$issue."' , amount='".$amount."' , status='".$status."' where invoice_number='".$no."'";
	UpdateRoom($query);
	header('Location:invoices.php');
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
<!--<li><a href="addinvoice.php">Add Invoice</a></li>-->
<li class="active"><a href="updateinvoice.php">Update Invoice</a></li>

</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Update Invoice</h1>
<div class="main-content">
<div class="data-form">
<?php $invo=getInvoice($number); ?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Status</label><select name="status"><option value="pending" <?php if($invo[0]['status']=='pending') echo 'selected'; ?>>Pending</option><option value="paid" <?php if($invo[0]['status']=='paid') echo 'selected'; ?>>Paid</option><option value="cancelled" <?php if($invo[0]['status']=='pending') echo 'cancelled'; ?>>Cancelled</option></select>
<label>Customer Name </label><input type="text" name="name" value="<?php $cust=getCustomer($number); echo $cust[0]['cust_name']; ?>" readonly >
<label>Invoice Number </label><input type="text" name="invoice" value="<?php echo $number; ?>"  readonly >
<label>Date issued </label><input type="date" name="date_issued" value="<?php echo $invo[0]['date_issued']; ?>" >
<label>Amount </label><input type="number" name="amount" value="<?php echo $invo[0]['amount']; ?>">
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
<?php } ?>