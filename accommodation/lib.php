<?php
session_start();
include_once("models/invoice/invoice.php");
include_once("models/room/room.php");
include_once("models/customer/customer.php");
include_once("models/rate/rate.php");
include_once("models/reservation/reservation.php");
include_once("models/users/users.php");
include_once("models/payment/payment.php");
include_once("models/order/order.php");
include_once("models/section/section.php");
$conn=db_connect($_SESSION["role"]);
$rowsperpage=10;
function db_connect($user) {
	if($user==1) {
	$username='chakaran_usr';
	//$username='root';
	$pwd=';G0(hH(M,xq&';
	//$pwd='';
	} else {
		$username='chakaran_usr1';
		$pwd='px(A@B6r7O&W';
	}

	$db1='chakaran_accomm';
	//$db1='chaka_accom';
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

function convertarray($data){
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function email() {
	$headers  = "From: order@mathsbooksforchildren.co.uk\r\n"; 
     $headers .= "Content-type: text/html charset=iso-8859-1 \r\n";
	 $query="select order_id, room_no, CONCAT(fname,' ', mname ,' ', lname) as cust_name ,phone_no,Adults, Child_A from reservation inner join customer on reservation.cust_id=customer.cust_id where arrival_date='".date('Y-m-d 11:00:00')."' and reservation.status='active'";
$guest=getData($query);
if(!empty($guest)){
     $message = '
    <html>
    <head>
      <title>Some title</title>
    </head>
    <body>
      <table>
		<caption>Guests Arriving Today</caption>
		<thead>
		<tr><th>Room_no</th>
		<th>Customer Details</th>
		<th>Contacts</th>
		<th>Adults</th>
		<th>Children</th></tr>
		</thead>';
		$table='';
		foreach($guest as $value) : 
		$table.='<tbody><tr><td>'. $value['room_no'].'</td><td>'. $value['cust_name'].'</td><td>'. $value['phone_no'].'</td><td>'. $value['Adults'].'</td><td>'. $value['Child_A'].'</td></tr></tbody>';
		 endforeach; 
		$table.='</table></html>';
	$msg=$message.$table;
// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("benard@starletcentre.co.ke","Guests Arriving today",$msg,$headers);
 } 
}




