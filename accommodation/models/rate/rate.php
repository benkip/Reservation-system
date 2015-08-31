<?php
function getRates() {
	$query="select rate, category, room_description,room_rates.room_id, room_rates.cat_id  from room_rates, room_type, room_size where room_rates.room_id=room_size.room_id and room_type.cat_id=room_rates.cat_id";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}

function getRateData($offset, $rowsperpage) {
	$query="select rate, category, room_description,room_rates.room_id, room_rates.cat_id,season_name  from room_rates, room_type, room_size, season where room_rates.room_id=room_size.room_id and room_type.cat_id=room_rates.cat_id and season.season_id=room_rates.season_id  LIMIT $offset, $rowsperpage";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function getRoomRate($cat, $type,$id){	
	$query="select rate from room_rates where cat_id=$cat and room_id=$type and season_id = $id";
	echo $query;
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function getSeasons() {
	$query="select * from season";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function getSeasonData($offset, $rowsperpage) {
	$query="select *  from season LIMIT $offset, $rowsperpage";
	$data=mysql_query($query);
	while($result[] = mysql_fetch_assoc($data)); 
	array_pop($result);
	return $result;
}
function getSeasonId(){
	$query="select season_id from season order by season_id asc";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['season_id'];
	}
	return $id+1;
}
function getSeasonRate($from, $to){
	$date=date_create($from);
	$from=date_format($date,"Y-m-d");
	$date=date_create($to);
	$to=date_format($date,"Y-m-d");
	$query="select season_id from season where start_date>='".$from."' and end_date<='".$to."'";
	$data=mysql_query($query);
	$id=0;
	while ($row = mysql_fetch_array($data)) {
		   $id=$row['season_id'];
	}
	return $id+1;
}
?>