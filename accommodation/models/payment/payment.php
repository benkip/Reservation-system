<?php
function getPayment() {
	$query="select pay_id from payment order by pay_id asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['pay_id'];
	}
	return $id+1;
}
function getPay($id) {
	$query="select * from payment where invoice_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);
}
function getPayData($id, $offset,$rowsperpage) {
	$query="select * from payment where invoice_id='".$id."' limit $offset, $rowsperpage";
	$data=mysql_query($query);
	return convertarray($data);
}
function getAmountPaid($id) {
	$query="select sum(amount) as paid from payment where invoice_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);
}
function updatePayment($id) {
	$query="select * from payment where pay_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);
}
?>