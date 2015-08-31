<?php
require_once('lib.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<h3>Orders from Room Number : <?php echo $_GET['number']; ?></h3>
<?php
$order_date=date('Y-m-d');
echo $order_date;
$room=$_GET['number'];
$query="select item_name,quantity,section_id,price from items, item_orders, orders where room_no='".$room."' and order_date='".$order_date."' and items.item_id=item_orders.item_id and orders.order_id=item_orders.order_id";
$data=getData($query); ?>
<table border="1" cellspacing="0">
<tr><td>item_name</td><td>Section</td><td>Unit price</td><td>Quantity</td><td>Amount</td></tr>
<?php $total=0; foreach($data as $i=>$val): ?>
	<tr><td><?php echo $val['item_name']; ?></td>
    <td><?php if($val['section_id']==1){ echo 'Leisure park';} else { echo 'Accommodation'; } ?></td>
    <td><?php echo $val['price']; ?></td>
    <td><?php echo $val['quantity']; ?></td>
    <td><?php $amount=$val['price']* $val['quantity']; echo $amount; ?></td>
    </tr> 
<?php $total=$total+$amount; endforeach; ?>
<tr><td colspan="4">Total Amount</td>
<td><?php echo $total; ?></td></tr>
</table>
</body>
</html>