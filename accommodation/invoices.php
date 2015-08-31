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
<li class="active"><a href="invoices.php">Invoices</a></li>
<li><a href="account-accomm.php">Accommodation</a></li>
<li><a href="account-sport.php">Sports Bar</a></li>
<li><a href="account-ride.php">Rides</a></li>
<li><a href="account-court.php">Food Court</a></li>
<li><a href="account-lounge.php">Lounge</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Invoices</h1>
<div class="main-content">

 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<!--<input type="submit" name="update" value="Update" /><input type="submit" name="delete" value="Delete" />-->
<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
<thead>
<tr class="table-header"><th width="10"><input type="checkbox" name"all">  </th>
<th>Invoice Number</th>
<th>Status</th>
<th>Date Issued</th>
<th>Amount Paid</th>
<th>Balance</th>
<th>Total Amount</th>
<th>Actions</th></tr>
</thead>
<tbody>
<?php

$numrows =count(getInvoices());
// number of rows to show per page

// find out total pages
$totalpages = ceil($numrows / $rowsperpage);

// get the current page or set a default
if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
   // cast var as int
   $currentpage = (int) $_GET['currentpage'];
} else {
   // default page num
   $currentpage = 1;
} // end if

// if current page is greater than total pages...
if ($currentpage > $totalpages) {
   // set current page to last page
   $currentpage = $totalpages;
} // end if
// if current page is less than first page...
if ($currentpage < 1) {
   // set current page to first page
   $currentpage = 1;
} // end if
// the offset of the list, based on current page 
$offset = ($currentpage - 1) * $rowsperpage;
$invoices=getInvoiceData($offset,$rowsperpage);
foreach($invoices as $i=>$value):
$link='payment.php?number='.$value['invoice_number'];
$update='updateinvoice.php?number='.$value['invoice_number'];?>
<tr class="table-content"><td><input type="checkbox" name="invoice[]" value="<?php echo $value['invoice_number']; ?>" /></td>
<td><?php echo $value['invoice_number']; ?></td>
<td><?php echo $value['status']; ?></td>
<td><?php echo $value['date_issued']; ?></td>
<td><?php $paid=getAmountPaid($value['invoice_number']); echo $paid[0]['paid']; ?></td>
<td><?php $bal=$value['amount']-$paid[0]['paid']; echo $bal; ?></td>
<td><?php echo $value['amount']; ?></td>
<td><a href="<?php echo $link; ?>"><img src="images/view.png" width="20"></a><a href="<?php echo $update; ?>"><img src="images/edit.png"></a><a href="#"><img src="images/delete.png"></a></td></tr>
<?php endforeach; ?>
</tbody>
</table>
<input type="submit" name="update" value="Update" /><input type="submit" name="delete" value="Delete" />
<select name="export"><option value="pdf">pdf</option></select><button>Export</button></td>
<?php $range = 3;
// if on page 1
if ($currentpage ==1 ) {
   // get next page
   $nextpage = 1;
    // echo forward link for next page 
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage' class='page-far-left'></a> ";
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage' class='page-left'></a> ";
} else if ($currentpage > 1) {
	$nextpage = 1;
	echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage' class='page-far-left'></a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage' class='page-left'></a> ";
} // end if 
 echo "<div class='page-info' Page <strong>$currentpage</strong> / $totalpages</div>";
/*//loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalpages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo "Page <strong>$currentpage</strong> / $totalpages";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x' >$x</a> ";
      } // end else
   } // end if 
} // end for*/
                 
// if not on last page, show forward and last page links        
if ($currentpage == $totalpages) {
   // get next page
   $nextpage = $totalpages;
    // echo forward link for next page 
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage' class='page-right'></a> ";
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages' class='page-far-right'></a> ";
} else if ($currentpage < $totalpages) {
   // get next page
   $nextpage = $currentpage+1 ;
    // echo forward link for next page 
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage' class='page-right'></a> ";
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages' class='page-far-right'></a> ";
}?>
<select name="rows" id="rows" onChange="changeRows()"><option value="5">number of rows</option>
					<option value="10">10</option>
                    <option value="15">15</option>
</select>

</form>
</div><!--end of main-content-->
</div><!--end of content-->
<!--<div id="footer" class="footer">
</div>-->
</body>
</html>
<?php } ?>