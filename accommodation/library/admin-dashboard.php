<?php
function getBooking(){
	$query="select  arrival_date, departure_date, CONCAT(fname,' ', mname ,' ', lname) as cust_name from reservation, customer, room_reservation where reservation.cust_id=customer.cust_id and reservation.order_id=room_reservation.order_id and date(booking_date)=CURDATE()";	
	$data=mysql_query($query);
	return convertarray($data);
}
function RoomBooking($cat_id, $type){
	$query="select room_description, category from reservation, room_reservation, room, room_size, room_type where  room.room_no=room_reservation.room_no and room.room_id=room_size.room_id and room.cat_id=room_type.cat_id and reservation.order_id=room_reservation.order_id and date(booking_date)=CURDATE() and room.cat_id='".$cat_id."' and room.room_id='".$type."'";
	//$query="select room_description, category, arrival_date, departure_date, CONCAT(fname,' ', mname ,' ', lname) as cust_name from reservation, room_reservation, customer, room, room_size, room_type where reservation.cust_id=customer.cust_id and room.room_no=room_reservation.room_no and room.room_id=room_size.room_id and room.cat_id=room_type.cat_id and date(booking_date)=CURDATE() and room.cat_id='".$cat_id."' and room.room_id='".$type."'";
	$data=mysql_query($query);
	return convertarray($data);
}
