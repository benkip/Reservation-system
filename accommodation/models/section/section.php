<?php
function getSectionName($id) {
	$query="select section_name from section where section_id='".$id."'";
	$data=mysql_query($query);
	return convertarray($data);
}

?>