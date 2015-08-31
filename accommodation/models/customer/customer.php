<?php
function getCustomerId() {
	$query="select cust_id from customer order by cust_id asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['cust_id'];
	}
	return $id+1;
}
function getCustomers(){
	$query="select distinct CONCAT(fname,' ', mname ,' ', lname) as cust_name, cust_id from customer";
	$data=mysql_query($query);
	return convertarray($data);	
}
function getCust($id){
	$query="select * from customer where cust_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);	
}
function getCustName($id){
	$query="select CONCAT(fname,' ', mname ,' ', lname) as cust_name from customer where cust_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);	
}
function getCustomer($invoice) {
	$query="select CONCAT(fname,' ', mname ,' ', lname) as cust_name from customer, reservation where customer.cust_id=reservation.cust_id and invoice_number='".$invoice."'";
	$data=mysql_query($query);
	return convertarray($data);	
}
?>