<?php 
function availablerooms($from,$to){
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
 function check_availablestandard($from,$to){
	$date=date_create($from);
	$from=date_format($date,"Y-m-d 11:00:00");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d 14:00:00");
	$query="select * from room where room_no not in (select room_no from reservation,room_reservation
where (arrival_date >='".$from."' and departure_date <= '".$to."') and reservation.order_id=room_reservation.order_id )";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function check_availabledeluxe($from,$to){
	$query="select  room_no, name, DISTINCT room_id from room where room_no NOT IN (select room_no from reservation, room_reservation where (departure_date >= ".$from." AND arrival_date <=".$to.") OR (departure_date <=".$from." AND arrival_date >=".$to.")and reservation.order_id=room_reservation.order_id) and cat_id=2";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function getAvailable($from,$to,$cat,$type){
	$date=date_create($from);
	$from=date_format($date,"Y-m-d");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d");
	
	$query=" select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation, room_reservation where (arrival_date >='".$from."' and departure_date <= '".$to."')and reservation.order_id=room_reservation.order_id ) ";
		  
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
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
function getRoomData($offset, $rowsperpage) {
	$query="select room_no,name,room_description,category from room inner join room_size on room.room_id=room_size.room_id inner join room_type on room.cat_id=room_type.cat_id order by room_no asc LIMIT $offset, $rowsperpage";
	$data=mysql_query($query);
	return convertarray($data);
}

function deleteRoom($no){
	
	$query="delete from room where room_no='".$no."'";
	mysql_query($query);
}
function getRoom($number) {
	$query="select room_no,name,room_description,category,room.cat_id as cat_id, room.room_id as room_id from room inner join room_size on room.room_id=room_size.room_id inner join room_type on room.cat_id=room_type.cat_id and room_no='".$number."'";
	$data=mysql_query($query);
	return convertarray($data);
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

function Rooms() {
	$query="select * from room";
	$data=mysql_query($query);
	return convertarray($data);
}
function check_availablerooms($from,$to){
	$date=date_create($from);
	$from=date_format($date,"Y-m-d");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d");
	$query="select * from room where room_no not in (select room_no from reservation, room_reservation
where (arrival_date >='".$from."' and departure_date <= '".$to."')and reservation.order_id=room_reservation.order_id )";
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
	
	//$query=" select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation where (arrival_date >='".$from."' and arrival_date<='".$to."') OR ( departure_date>='".$from."' and departure_date <= '".$to."') and status='active') and status='available' ";
	//$query="select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation where (arrival_date >='".$from."' and arrival_date<='".$to."') OR ( departure_date>='".$from."' and departure_date <= '".$to."') OR ('".$to."'>=arrival_date and departure_date>='".$from."')and status='active') and status='available' ";	

//$query="select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation where ((arrival_date < '".$to."' and departure_date >'".$to."') OR ( arrival_date <='".$from."' and departure_date > '".$from."') OR (arrival_date < '".$to."' and arrival_date > '".$from."'))and status='active') and status='available' ";

//$query="select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation, room_reservation where status='active' and ((arrival_date >= '".$from."' and arrival_date <= '".$to."') OR (arrival_date <= '".$from."' and departure_date >= '".$to."')) and reservation.order_id=room_reservation.order_id  ) and status='available' ";
$query="select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation, room_reservation where status='active' and ((arrival_date BETWEEN '".$from."' and '".$to."' ) OR (departure_date BETWEEN '".$from."' and '".$to."') OR (arrival_date <= '".$from."' and departure_date >= '".$to."')) and reservation.order_id=room_reservation.order_id  ) and status='available' ";
	$data=mysql_query($query);	
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}

function getAvailableroomId($from,$to,$cat,$type,$no){
	$date=date_create($from);
	$from=date_format($date,"Y-m-d");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d");	
	$query=" select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation, room_reservation where (arrival_date >= '".$from."' and departure_date <= '".$to."')and reservation.order_id=room_reservation.order_id)  and status='available' and room_no='".$no."'";	

//$query=" select * from room where (room_id='".$type."' and cat_id='".$cat."') and room_no not in (select room_no from reservation where (arrival_date >= STR_TO_DATE('".$from."', '%Y-%m-%d %H:%i:%s') and arrival_date <= STR_TO_DATE('".$to."', '%Y-%m-%d %H:%i:%s') )  and status='active') and status='available' ";
//select * from rooms where roomid not in(select roomid from reservation where (begin_date>=given_begin_date and begin_date<=given_end_date ) or(end_date>=given_begin_date and end_date<=given_end_date))
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}