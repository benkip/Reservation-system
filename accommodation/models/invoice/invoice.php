<?php
function getInvoices(){
	$query="select * from invoice";
	$data=mysql_query($query);
	return convertarray($data);
}
function getInvoice($id) {
	$query="select * from invoice where invoice_number=$id";
	$data=mysql_query($query);
	return convertarray($data);
}
function getInvoiceData($offset, $rowsperpage){
	$query="select * from invoice LIMIT $offset, $rowsperpage";
	$data=mysql_query($query);
	return convertarray($data);
}
function getInvoiceId() {
	$query="select invoice_number from invoice order by invoice_number asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['invoice_number'];
	}
	return $id+1;
}
function getCustomerInvoice($id) {
	$query="select * from invoice where invoice_number=$id";
	$data=mysql_query($query);
	return convertarray($data);
}

?>