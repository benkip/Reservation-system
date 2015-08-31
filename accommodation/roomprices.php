<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_GET['cat']) && isset($_GET['id'])){
	$type=$_GET['cat'];
	$size=$_GET['id'];
	$query="delete from room_rates where room_id='".$size."' and cat_id='".$type."'";
	if(update($query)) {
		$error='Could not delete the record!';
	}
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
	<script src="javascript/jquery.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/js/chili/chili-1.8b.js"></script>
	<script type="text/javascript" src="javascript/tablesorter/docs/js/docs.js"></script>
	<script type="text/javascript">
	$(function() {		
		$("#tablesorter-demo").tablesorter({sortList:[[1,0],[1,0]], widgets: ['zebra']});
		$("#options").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}}});
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
<li class="active"><a href="roomprices.php">Rates</a></li>
<li><a href="addroomprices.php">Add Rate</a></li>
<li><a href="season.php">Seasons</a></li>
<li><a href="addseason.php">Add Season</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1> Rates </h1>
<div class="main-content">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<div class="error-bar"><?php if(isset($error)) {	echo $error; }?> </div>
<div class="table-search">

<input type="text" id="search" placeholder="Type to search">
</div>
<!--<input type="submit" name="update" value="Update" /><input type="submit" name="delete" value="Delete" />-->
<table id="tablesorter-demo" class="tablesorter" cellspacing="0">
<thead>
<tr class="table-header"><th width="20"><input type="checkbox" name="all">  </th>
<th width="270">Room Classification</th>
<th>Rates</th>
<th>Season</th>
<th>Actions</th></tr>
</thead>
<tbody>
<?php
$numrows =count(getRates());
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
$rooms=getRateData($offset,$rowsperpage);
foreach($rooms as $i=>$value):
$link='updateprice.php?id='.$value['room_id'].'& cat='.$value['cat_id'];
$del=$_SERVER['PHP_SELF'].'?id='.$value['room_id'].'& cat='.$value['cat_id'];?>
<tr class="table-content"><td width="20"><input type="checkbox" name="room[]" value="<?php echo $value['room_id']; ?>" /></td>
<td><?php echo $value['category']."&nbsp;".$value['room_description']; ?></td>
<td><?php echo $value['rate']; ?></td>
<td><?php echo $value['season_name']; ?></td>
<td><a href="<?php echo $link; ?>"><img src="images/edit.png"></a><a href="<?php echo $del; ?>"><img src="images/delete.png"></a></td></tr>
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
</div><!--end of main-content-->
</div><!--end of content-->

</body>
</html>
<?php } ?>