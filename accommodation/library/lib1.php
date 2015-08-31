<?php
$conn=db_connect();
function db_connect() {
	$username='root';
	$pwd='';
	$db1='chaka_accomodation';
	$conn = mysql_connect('localhost', $username, $pwd);
	if (!$conn) {
			die('Could not connect: ' . mysql_error());
		}
		$db = mysql_select_db($db1, $conn);
		if (!$db) {
			die('Could not select the database: ' . mysql_error());
		}
		
	return $conn;
}
function getData($query){
	$data=mysql_query($query);
	return convertarray($data);
}
function update($query) {
	mysql_query($query);
}
function getRoomId() {
	$query="select room_no from room order by room_no asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['room_no'];
	}
	return $id+1;
}
function get_Rooms() {
	$query="select room_no,name,room_description,category from room inner join room_size on room.room_id=room_size.room_id inner join room_type on room.cat_id=room_type.cat_id order by room_no asc";
	$data=mysql_query($query);
	return convertarray($data);
}
function deleteRoom($no){
	
	$query="delete from room where room_no='".$no."'";
	mysql_query($query);
}
function getRoom($number) {
	$query="select room_no,name,room_description,category from room inner join room_size on room.room_id=room_size.room_id inner join room_type on room.cat_id=room_type.cat_id and room_no='".$number."'";
	$data=mysql_query($query);
	return convertarray($data);
}
function convertarray($data){
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function getRoomSize($id) {
	if(isset($id)){
		$query="select * from room_size where room_id='".$id."'";
	} else {
		$query="select * from room_size ";
	}
	$data=mysql_query($query);
	return convertarray($data);
}
function getRoomType($cat) {
	if(isset($cat)){
		$query="select * from room_type where cat_id='".$cat."'";
	} else {
		$query="select * from room_type ";
	}
	$data=mysql_query($query);
	return convertarray($data);
}
function updateRoom($query) {
	mysql_query($query);
}

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
	
	$query="select order_id, room_no,arrival_date, departure_date, status, CONCAT(fname,' ', mname ,' ', lname) as cust_name from reservation inner join customer on reservation.cust_id=customer.cust_id";
	
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
function check_availablerooms($from,$to){
	$date=date_create($from);
	$from=date_format($date,"Y-m-d");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d");
	$query="select * from room where room_no not in (select room_no from reservation
where (arrival_date >='".$from."' and departure_date <= '".$to."') )";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function getAvailablerooms($from,$to,$cat,$type){
	$date=date_create($from);
	$from=date_format($date,"Y-m-d");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d");
	
	$query=" select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation
where (arrival_date >='".$from."' and departure_date <= '".$to."') ) ";
	
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}


function getCustomerId() {
	$query="select cust_id from customer order by cust_id asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['cust_id'];
	}
	return $id+1;
}

function getRates() {
	$query="select rate, category, room_description,room_rates.room_id, room_rates.cat_id  from room_rates, room_type, room_size where room_rates.room_id=room_size.room_id and room_type.cat_id=room_rates.cat_id";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}