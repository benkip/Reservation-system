<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_POST['delete'])) {
	$data=$_POST['order'];
	
	for($i=0; $i<count($data); $i++) {
		$query="delete from reservation where order_id='".$data[$i]."'";
		update($query);
	}
}
if(isset($_GET['order'])) {
	$data=$_GET['order'];
		
		$query="delete from customer where cust_id='".$data."'";
		
		update($query);

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
    
        
	<script type="text/javascript" src="javascript/tablesorter/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/js/chili/chili-1.8b.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/docs/js/docs.js"></script>
	<script type="text/javascript">
	$(function() {		
		//$("#tablesorter-demo").tablesorter({sortList:[[4,1],[4,0]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}}});
	});	
	</script>
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
		
		$( "#to" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
				$( "#from" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
		$from.add($to).change(function () {
        var dayFrom = $from.datepicker('getDate');
        var dayTo = $to.datepicker('getDate');
        if (dayFrom && dayTo) {
            var days = calcDaysBetween(dayFrom, dayTo);

            $('#nights').val(days);
        }
    });

    function calcDaysBetween(startDate, endDate) {
        return (endDate - startDate) / (1000 * 60 * 60 * 24);
    }
	$( "#calender" ).datepicker({
		minDate:0,
		numberOfMonths: 2,
		/*onSelect: function (date) {
                var date2 = $('#from').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#to').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#to').datepicker('option', 'minDate', date2);
       },*/
		showButtonPanel: false
	});
	var $rows = $('#tablesorter-demo tbody tr');
$('#search').keyup(function() {
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    
    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
        return !~text.indexOf(val);
    }).hide();
});
	
});
</script>
</head>

<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li class="active"><a href="reservation.php">Reservations</a></li>
<li><a href="checkout.php">Check Out</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Reservations</h1>
<div class="main-content">
<div class="data-form">

<form action="reserve.php" method="post" >
<label>Arrival date </label> <div class="cal"><input type="date" id="from" name="arrival_date" value="<?php echo date('d-m-Y'); ?>"></div>
<label>Departure date </label><div class="cal"><input type"date" id="to" name="departure_date" value="<?php echo date('d-m-Y'); ?>">
</div>
<div class="controls">
<input type="reset" name="clear" value="Reset" class="form-reset"  >
<input type="submit" name="reserve" value="Proceed" class="form-submit"  >
</div>
<div class="table-search">
<a href="excel.php">Export to excel</a>


<input type="text" id="search" placeholder="Type to search">
</div>
<table id="tablesorter-demo" class="tablesorter" cellspacing="0" >
<thead>
<tr class="table-header" ><th>Add Rooms  </th>
<th>Rooms</th>
<th>Customer Details</th>
<th>Contacts</th>
<th>Arrival_date</th>
<th>Departure_date</th>
<th>Adults</th>
<th>Children</th>
<th>Voucher</th>
<th>Username</th>
<th>Actions</th></tr></thead>
<tbody>
<?php

$numrows =count(getRes());
// number of rows to show per page
$rowsperpage = 10;
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
$reserve=getResData($offset,$rowsperpage);
foreach($reserve as $i=>$value):
$lin='payment.php?number='.$value['invoice_number'];
$link='reservation.php?order='.$value['cust_id'];
$update='updatereservation.php?order='.$value['order_id'];
$voucher='voucher.php?number='.$value['cust_id'];
$add='newroom.php?customer='.$value['cust_id'];?>
<tr class="table-content" ><td><a href="<?php echo $add; ?>">Add Room</a></td>
<td><?php $room=getBook($value['order_id']);  $qry="select room_no from room_reservation where order_id=".$value['order_id']; $dt=getData($qry); echo count($dt); ?></td>
<td><?php echo $value['cust_name']; ?></td>
<td><?php echo $value['phone_no']; ?></td>
<td><?php echo date_format(date_create($value['arrival_date']), "Y-m-d"); ?></td>
<td><?php echo date_format(date_create($value['departure_date']), "Y-m-d"); ?></td>
<td><?php echo $room[0]['adults']; ?></td>
<td><?php $d=$room[0]['child_a']+$room[0]['child_b']; echo $d; ?></td>
<td><a href="<?php echo $voucher; ?>" target="_blank"><img src="images/voucher.png" width="40"></td>
<td><?php echo $value['username']; ?></td>
<td><a href="<?php echo $lin; ?>"><img src="images/view.png" width="20"></a><a href="<?php echo $update; ?>"><img src="images/edit.png"></a><a href="<?php echo $link; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><img src="images/delete.png"></a></td></tr>
<?php endforeach; ?>
</tbody>
</table>
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

                

</form>


</div><!--end of main-content--->
</div><!--end of content-->

</body>
</html>
<?php } ?>