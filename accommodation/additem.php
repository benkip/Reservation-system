<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
if(isset($_POST['order'])) {
$room=$_POST['room'];
$order_date=$_POST['order_date'];
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Accommodation</title>
  <link  href="css/style.css" type="text/css" rel="stylesheet">
  <link href="css/order.css" rel="stylesheet" type="text/css">
  
  <script src="javascript/jquery.js"></script>
 <script>
$(document).ready(function(){
$('#print').click(function(e) {
	window.print();
	 
});
$('.button').click(function(e) {
	var amount=parseInt(document.getElementById('amount').value);
    var price = $(this).parent().find('.price').val();
    //alert(price);
	var quantity = $(this).parent().find('.quantity').val();
	//alert(quantity);
	var items = $(this).parent().find('.item_name').val();
	//alert(item);
	var id = $(this).parent().find('.item_id').val();
	var section = $(this).parent().find('.section').val();
	var total=price*quantity;
	amount=amount+total;
	document.getElementById('amount').value=amount;
	var d=document.getElementById('tablebill');
    d.innerHTML+='<tr><td>'+items+'</td><td>'+price+'</td><td>'+quantity+'</td><td>'+total+'</td></tr>';
	d.innerHTML+='<input type="hidden" name="item[]" value="'+id+'" /><input type="hidden" name="quantity[]" value="'+quantity+'" /><input type="hidden" name="section[]" value="'+section+'" />';
    //DO your AJAX call here
    return false;
});
});
</script>
</head>
<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li><a href="orders.php">Orders</a></li>
<li class="active"><a href="additem.php">Room Orders</a></li>
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
 <h3>Room Number: <?php if(isset($room)) { echo $room; } ?></h3>
 <div id="menu" class="menu">
<div class="menu-item">
<h4>Leisure Park Menu</h4>
<div class="leisure-park">
<?php 
$query="select * from item_category";
$data=getData($query); 
foreach($data as $val) :
	$items=getItems($val['cat_id'], 1); ?>
    <h5><?php echo ucfirst($val['category_name']); ?></h5>    
<?php foreach($items as $i=>$item): ?>
    <div id="item" class="item">
    <form name="addToBill">
    <input type="hidden"  class="section" value="1"/>
    <input type="hidden"  class="item_id" value="<?php  echo $item['item_id']; ?>"/>
	<input type="hidden"  class="price" value="<?php  echo $item['price']; ?>"/>
    <input type="hidden"  class="item_name" value="<?php  echo $item['item_name']; ?>"/>
	<?php echo $item['item_name']; ?> Kshs <?php echo $item['price']; ?> x <input type="number" name="<?php echo $item['item_name']; ?>" value="1" class="quantity"><input type="submit" value="order" class="button">
    </form>
    </div>
<?php endforeach;
endforeach;
?>
</div>
</div>
<div class="menu-item">
<h4>Accommodation Park Menu</h4>
<div class="accommodation-park">

<?php 
$query="select * from item_category";
$data=getData($query); 
foreach($data as $val) :
	$items=getItems($val['cat_id'], 1); ?>
    <h5><?php echo ucfirst($val['category_name']); ?></h5>    
<?php foreach($items as $i=>$item): ?>
    <div id="item" class="item">
    <form name="addToBill">
    <input type="hidden"  class="section" value="1"/>
    <input type="hidden"  class="item_id" value="<?php  echo $item['item_id']; ?>"/>
	<input type="hidden"  class="price" value="<?php  echo $item['price']; ?>"/>
    <input type="hidden"  class="item_name" value="<?php  echo $item['item_name']; ?>"/>
	<?php echo $item['item_name']; ?> Kshs <?php echo $item['price']; ?> x <input type="number" name="<?php echo $item['item_name']; ?>" value="1" class="quantity"><input type="submit" value="order" class="button">
    </form>
    </div>
<?php endforeach;
endforeach;
?>
</div>
</div>
</div>
<div id="bill" class="bill">
<h5>Your Bill</h5>
<form action="orders.php" method="post" id="formbill">
<input type="hidden" name="room" value="<?php echo $room; ?>">
<input type="hidden" name="order_date" value="<?php echo $order_date; ?>">
<table border="1" cellspacing="0" id="tablebill">
<tr><td>Item</td><td>Unit Price</td><td>Quantity</td><td>Amount</td></tr>
</table>
Total Amount: <input type="text" name="amount" readonly value="0" size="4" id="amount" class="amount">
<input type="submit" name="bill" value="Save" class="print"><input type="button" value="Print" id="print" class="print">
</form>
</div>
</body>
</html>
<?php } ?>