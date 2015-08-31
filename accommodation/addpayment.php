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
	$invoice=$_GET['number'];
}
if(isset($_POST['add'])) {
	$no=getPayment();
	$amount=$_POST['amount'];
	$code=$_POST['code'];
	$invoice_id=$_POST['invoice'];

	
	$payment_date=$_POST['payment_date'];
	
	$payment_date=date_format(date_create($payment_date),"Y-m-d");
	$query="insert into payment values ('".$no."','".$payment_date."' , '".$code."' , '".$amount."','".$invoice_id."' )";
	Update($query);
	header('Location:payment.php?number='.$invoice_id.'');
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
 <link rel="stylesheet" href="javascript/themes/base/all.css">
    <script src="javascript/jquery.js"></script>
	<script src="javascript/ui/core.js"></script>
    <script src="javascript/ui/widget.js"></script>
    <script src="javascript/ui/datepicker.js"></script>
     <script>
	$(function() {
		var $from = $("#from"),
        $to = $("#to");
		$( "#datepicker" ).datepicker();
		$( "#from" ).datepicker({
			showOn: "button",
			minDate: 0,
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			onSelect: function (date) {
                var date2 = $('#from').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#to').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#to').datepicker('option', 'minDate', date2);
            }
		});
	});
		</script>
</head>

<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li><a href="invoices.php">Invoices</a></li>
<li><a href="addinvoice.php">Add Invoice</a></li>
<li class="active"><a href="updateinvoice.php">Update Invoice</a></li>

</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>New Payment</h1>
<div class="main-content">
<div class="data-form">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">  
<label>Invoice Number :</label><input type="text" name="invoice_number" value="<?php echo $invoice; ?>"  readonly >
<label>Customer Name</label> <input type="text" name="name" value="<?php $cust=getCustomer($invoice); echo $cust[0]['cust_name']; ?>" readonly >
 <input type="hidden" name="invoice" value="<?php echo $invoice; ?>" >
 <label>Payment Date </label><input type="date" name="payment_date" id="from" value="<?php echo date('m/d/Y'); ?>">
<label>Amount Paid </label><input type="number" name="amount">
<label>Payment Code</label> <input type="text" name="code" required>

<div class="controls">
<input type="reset" value="Reset" class="form-reset">
<input type="submit" value="Save" class="form-submit" name="add">
</div>
</form>
</div>
</div><!--end of main-content-->
</div><!--end of content-->
</body>
</html>
<?php } ?>