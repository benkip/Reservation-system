<?php
function getUsers() {
	$query="select * from users";
	$data=mysql_query($query);
	return convertarray($data);
}
function getUserData($offset,$rowsperpage) {
	
	$query="select * from users order by user_id desc LIMIT $offset, $rowsperpage";	
	$data=mysql_query($query);
	return convertarray($data);
}
function getUserId() {
	$query="select user_id from users order by user_id asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['user_id'];
	}
	return $id+1;
}
function getUser($id) {
	$query="select * from users where user_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);
}
?>