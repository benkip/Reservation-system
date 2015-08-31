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
if(isset($_POST['bill'])){
	$items=$_POST['item'];
	$quantity=$_POST['quantity'];
	$section=$_POST['section'];
	$date=date_create($_POST['order_date']);
	$order_date=date_format($date,"Y-m-d");
	$query="select * from orders where order_date='".$order_date."' and room_no='".$_POST['room']."'";
	$state=getData($query);
	$query="select * from orders";
	$x=count(getData($query))+1;;
	if(count($state)==0) {
		$qry="insert into orders (order_id,order_date, room_no,amount) values('".$x."','".$order_date."','".$_POST['room']."','".$_POST['amount']."')";
		mysql_query($qry);
	} else {
		$tot=$_POST['amount']+$state[0]['amount'];
		$qry="update orders set amount='".$tot."' where order_id='".$state[0]['order_id']."'";
		mysql_query($qry);
	}
	//get order_id
	$query="select * from orders where order_date='".$order_date."' and room_no='".$_POST['room']."'";
	$res=getData($query);
	$order_id=$res[0]['order_id'];
	//insert data into item_orders
	for($i=0; $i<count($items); $i++) {
		$query="insert into item_orders (order_id,item_id,quantity,section_id) values('".$order_id."','".$items[$i]."','".$quantity[$i]."','".$section[$i]."')";
		mysql_query($query);
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
<li class="active"><a href="orders.php">Orders</a></li>
<li><a href="item.php">Items</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Orders</h1>
<div class="main-content">
<form action="additem.php" method="post" >
<label>Order Date </label> <input type="date" id="from" name="order_date" value="<?php echo date('d-m-Y'); ?>">
<label>Room </label><?php 
$query="select * from room where status='available'"; 
$dt=getData($query); ?>
<select name="room"><?php foreach($dt as $val): ?>
<option value="<?php echo $val['room_no']; ?>"><?php echo $val['room_no']; ?></option><?php endforeach; ?>
<input type="submit" value="Order" name='order'>
</form>
 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<!--<input type="submit" name="update" value="Update" /><input type="submit" name="delete" value="Delete" />-->
<table id="tablesorter-demo" class="tablesorter" border="0" cellpadding="0" cellspacing="1">
<thead>
<tr class="table-header">
<th>Room_no</th>
<th>Date </th>
<th>Amount </th>
<th>Actions</th></tr>
</thead>
<tbody>
<?php

$numrows =count(getOrders());
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
$order=getOrdersData($offset,$rowsperpage,date('Y-m-d'));
foreach($order as $i=>$value):
$link='vieworder.php?number='.$value['room_no']; ?>
<tr class="table-content">
<td><?php echo $value['room_no']; ?></td>
<td><?php echo $value['order_date']; ?></td>
<td><?php echo $value['amount']; ?></td>
<td><a href="javascript: void(0)" onClick="window.open('<?php echo $link; ?>','Add Items','width=400, height=500'); return false;"><img src="images/view.png" width="20"></a><a href="#"><img src="images/edit.png"></a><a href="#"><img src="images/delete.png"></a></td></tr>
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