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
<li><a href="orders.php">Orders</a></li>
<li class="active"><a href="item.php">Items</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Items</h1>
<div class="main-content">
<div class="leisure-park">
<h3>Leisure Park</h3>
 <table cellspacing="0">
 <tr>
 <td>Item</td>
 <td>Price</td>
 </tr><tr>
 <td>Soda</td>
 <td>100</td>
 </tr>
 <tr><td>Ice Cream</td>
 <td>80</td></tr>
 </table>
</div>
<div class="accommodation-park">
<h3> Accommodation Park</h3>
	 <table cellspacing="0">
  <tr>
 <td>Item</td>
 <td>Price</td>
 </tr><tr>
 <td>Soda</td>
 <td>100</td>
 </tr>
 <tr><td>Ice Cream</td>
 <td>80</td></tr>
 </table>
 </div>
</div><!--end of main-content-->
</div><!--end of content-->
<!--<div id="footer" class="footer">
</div>-->
</body>
</html>
<?php } ?>