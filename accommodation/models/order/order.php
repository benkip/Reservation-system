<?php
function getOrders(){
	$query="select * from orders, room where room.room_no=orders.room_no and room.status='available' ";
	$data=mysql_query($query);
	return convertarray($data);
}
function getOrdersData($offset,$rowsperpage,$dt) {
	$date=date_create($dt);
	$order_date=date_format($date,"Y-m-d");
	$query="select * from orders, room where room.room_no=orders.room_no and room.status='available' limit $offset, $rowsperpage";
	$data=mysql_query($query);
	return convertarray($data);
}
function getItems($id,$section){
	$data=mysql_query("select items.item_id, item_name, price from items, item_section where items.item_id=item_section.item_id and cat_id='".$id."' and section_id='".$section."'");
	return convertarray($data);
}
?>