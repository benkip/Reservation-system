<?php
function getReservation($id) {
	if(isset($id)){
		$query="select order_id, room_no,arrival_date, departure_date, CONCAT(fname,' ', mname ,' ', lname) as cust_name from reservation inner join customer on reservation.cust_id=customer.cust_id and order_id='".$id."'";
	} else {
	$query="select order_id, room_no,arrival_date, departure_date, CONCAT(fname,' ', mname ,' ', lname) as cust_name from reservation inner join customer on reservation.cust_id=customer.cust_id";
	}
	$data=mysql_query($query);
	return convertarray($data);
}

function getRes() {
	
	$query="select order_id,arrival_date, departure_date, status, username, CONCAT(fname,' ', mname ,' ', lname) as cust_name,invoice_number,phone_no from reservation inner join customer on reservation.cust_id=customer.cust_id inner join users on reservation.user_id=users.user_id where arrival_date>='".date('Y-m-d')."' order by MONTH(arrival_date), DAYOFMONTH(arrival_date) ";
	
	$data=mysql_query($query);
	return convertarray($data);
}
function getResData($offset,$rowsperpage) {
	
	$query="select order_id, arrival_date, departure_date, status, username, CONCAT(fname,' ', mname ,' ', lname) as cust_name ,invoice_number,phone_no, customer.cust_id from reservation inner join customer on reservation.cust_id=customer.cust_id inner join users on reservation.user_id=users.user_id where arrival_date>='".date('Y-m-d')."' order by MONTH(arrival_date), DAYOFMONTH(arrival_date) LIMIT $offset, $rowsperpage";
	
	$data=mysql_query($query);
	return convertarray($data);
}
function getReserveId() {
	$query="select order_id from reservation order by order_id asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['order_id'];
	}
	return $id+1;
}
function getReserve($id) {
	$query="select * from reservation where order_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);
}
function getBook($id) {
	$query="select room_no, sum(adults) as adults, sum(child_a) as child_a, sum(child_b) as child_b from room_reservation where order_id=$id";
	$data=mysql_query($query);
	return convertarray($data);
}
?>