<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
} else {
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();

require_once('library/admin-dashboard.php');
if(isset($_POST['delete'])) {
	$data=$_POST['room'];
	
	for($i=0; $i<count($data); $i++) {
		deleteRoom($data[$i]);
	}
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Accommodation</title>
<link  href="css/style.css" type="text/css" rel="stylesheet">
 <link rel="stylesheet" href="javascript/themes/base/all.css">
    <script src="javascript/jquery.js"></script>
	<script src="javascript/ui/core.js"></script>
    <script src="javascript/ui/widget.js"></script>
    <script src="javascript/ui/datepicker.js"></script>
	<script>
	$(function() {
		var $from = $("#from"),
        $to = $("#to");
		$( "#datepicker" ).datepicker();
		$( "#from" ).datepicker({
			showOn: "button",
			minDate: 0,
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			onSelect: function (date) {
                var date2 = $('#from').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#to').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#to').datepicker('option', 'minDate', date2);
            }
		});
		
		$( "#to" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
				$( "#from" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
		$from.add($to).change(function () {
        var dayFrom = $from.datepicker('getDate');
        var dayTo = $to.datepicker('getDate');
        if (dayFrom && dayTo) {
            var days = calcDaysBetween(dayFrom, dayTo);

            $('#nights').val(days);
        }
    });

    function calcDaysBetween(startDate, endDate) {
        return (endDate - startDate) / (1000 * 60 * 60 * 24);
    }
	$( "#calender" ).datepicker({
		minDate:0,
		numberOfMonths: 2,
		/*onSelect: function (date) {
                var date2 = $('#from').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#to').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#to').datepicker('option', 'minDate', date2);
       },*/
		showButtonPanel: false
	});
		
});
</script>
<script>
$(function() {
	$(window).load(function() {
        
	$('#main-content').css('height', window.innerHeight);
	});
});
</script>
</head>

<body>
<?php include_once('header.php'); ?>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">

<div class="main-content">
<div class="dash-menu">
<?php
$from=date('Y-m-d');
$to=$from;
?>
<ul><li><abbr><?php $book=getBooking();  echo count($book); ?></abbr> Client Booking today.
</li>
<li><abbr>&nbsp;</abbr>Room Availability.</li>
<li class="last-child"><abbr><?php echo count(RoomBooking(1,1))+count(RoomBooking(1,2))+count(RoomBooking(1,3))+count(RoomBooking(2,1))+count(RoomBooking(2,2)); ?> </abbr> Booked rooms today.</li>
</ul>
</div>
<div class="dash-item">
<div class="column-top">Latest Booking Today</div>
<blockquote>
<?php  
	foreach($book as $i=>$value): ?>
	Customer Name: <?php echo $value['cust_name'].'<br>'; ?>	

	Arrival Date: <?php echo $value['arrival_date'].'<br/>'; ?>
	Duration: <?php $datetime1 = new DateTime($value['arrival_date']);
	$datetime2 = new DateTime($value['departure_date']);
	$interval = $datetime1->diff($datetime2);
	echo $interval->format('%R%a days').'<br/><br/>';
	//echo date_diff($value['arrival_date'],$value['departure_date']);
	endforeach;
 ?>
</blockquote>
</div>
<div class="dash-item">
<div class="column-top">Check Room Availability</div>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<p><label>Start Date :</label><input type="date" name="start" value="<?php if(isset($_POST['submit'])){ echo $_POST['start']; } else { echo date('Y-m-d'); } ?>" id="from"></p>
<p><label>End Date :</label><input type="date" name="end" value="<?php if(isset($_POST['submit'])){ echo $_POST['end']; } else { echo date('Y-m-d'); } ?>" id="to"></p>
<p><label>Rooom type:</label><select name="room_type"><?php
 $type=getRoomType();
 foreach($type as $value) : ?><option value="<?php echo $value['cat_id']; ?>" <?php if(isset($_POST['submit']) && ($value['cat_id']==$_POST['room_type'])){ echo "selected"; } ?>><?php echo $value['category']; ?></option><?php endforeach; ?></select></p>
<p><label>Room Description: </label><select name="description"><?php
 $type=getRoomSize();
 foreach($type as $value) : ?><option value="<?php echo $value['room_id']; ?>" <?php if(isset($_POST['submit']) && ($value['room_id']==$_POST['description'])){ echo "selected"; } ?>><?php echo $value['room_description']; ?></option><?php endforeach; ?></select></p>
<p><input type="reset" value="Reset" class="dash-controls">
<input type="submit" value="Check Availability" name="submit" class="dash-controls"></p>
</form>
<?php
if(isset($_POST['submit'])) {
	$from=$_POST['start'];
	$to=$_POST['end'];
	$room_type=$_POST['room_type'];
	$room_descrip=$_POST['description'];
	$available=getAvailablerooms($from, $to, $room_type, $room_descrip); 
	?>
	<table>
    <caption>Start Date: <?php echo $from; ?> End Date: <?php echo $to; ?></caption><tr><td>Room_no</td>
    <td>Name</td>
    <td>Description</td>
    <td> Room type</td>
    </tr>
<?php   if($available) {
		foreach($available as $value) :
				$data=getRoom($value['room_no']);
				foreach($data as $val) :
					echo "<tr><td>".$val['room_no']."</td>";
					echo "<td>".$val['name']."</td>";
					echo "<td>".$val['room_description']."</td>";
					echo "<td>".$val['category']."</td></tr>";
				endforeach;
			endforeach;
		} else {
		echo "No Available Rooms of that type";	
		}
}

?>
</table>

</div>
<div class="dash-item-last">
<div class="column-top">Booked Rooms Today</div>
<pre>Standard Rooms:<br>
Single Rooms:<?php echo count(RoomBooking(1,1)); ?> <br>
Double Rooms:<?php echo count(RoomBooking(1,2)); ?> <br>
Triple Rooms:<?php echo count(RoomBooking(1,3)); ?> <br>
Deluxe Rooms:<br>
Single Rooms: <?php echo count(RoomBooking(2,1)); ?><br>
Double Rooms: <?php echo count(RoomBooking(2,2)); ?><br>
</pre>
<?php 
$query="select order_id, CONCAT(fname,' ', mname ,' ', lname) as cust_name ,phone_no from reservation inner join customer on reservation.cust_id=customer.cust_id where arrival_date='".date('Y-m-d 11:00:00')."' and reservation.status='active'";
$guest=getData($query);
if(!empty($guest)){
?>
<table>
<caption>Guests Arriving Today</caption>
<thead>
<tr>
<th>Customer Details</th>
<th>Contacts</th></tr>
</thead>
<?php foreach($guest as $value) : ?>
<tbody><tr>
<td><?php echo $value['cust_name']; ?></td>
<td><?php echo $value['phone_no']; ?></td>
</tr></tbody>
<?php endforeach; ?>
</table>
<?php }  else {
echo "<h4> No Guests Arriving today.</h4>";
}?>
</div>
<?php
function checkroom($from, $to, $no) {
//$query="select * from reservation where (arrival_date >='".$from."' and arrival_date<='".$to."') OR ( departure_date>='".$from."' and departure_date <= '".$to."') OR ('".$to."'>=arrival_date and departure_date>='".$from."')and status='active' and room_no='".$no."'";

//$query="select arrival_date, departure_date, CONCAT(fname,' ', mname ,' ', lname) as cust_name from reservation, customer where  status='active' and   room_no='".$no."' and ((arrival_date <= '$from' AND departure_date >= '$to') OR (arrival_date < '$to' AND departure_date >= '$to') OR (arrival_date >= '$from' AND departure_date < '$to')) and customer.cust_id=reservation.cust_id";
// $query="select * from reservation where status='active' and room_no='".$no."' and ('".$to."'> arrival_date and '".$to."'< departure_date)";
  $query="select arrival_date, departure_date, cust_id from reservation,room_reservation where room_reservation.order_id=reservation.order_id and status='active' and room_no='".$no."' and /*('".$to."'> arrival_date and '".$to."'< departure_date*/((arrival_date BETWEEN '".$from."' and '".$to."' ) OR (departure_date BETWEEN '".$from."' and '".$to."') OR (arrival_date <= '".$from."' and departure_date >= '".$to."') )";
$data=getData($query);
return $data;
}
?>
<?php
$week = date("W");
$year = date("Y");
$week = (isset($_GET['week']))?$_GET['week']:Date('W');
?>
<table cellspacing="0">
<tr class="book-head"><td colspan="8"> 
<a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week-1); ?>" class="previous">Previous Week</a> <!--Previous week-->
<span>WEEKLY BOOKING SUMMARY</span>
<a href="<?php echo $_SERVER['PHP_SELF'].'?week='.($week+1);?>" class="next">Next Week</a> <!--Next week-->
</td></tr>
<tr class="table-home">
<td>Room Number</td>
<?php

if(isset($_GET['week'])){
   $week = $_GET['week'];
} else {
   $week = Date('W');
}


for($day=1; $day<=7; $day++)
{
	$d = strtotime($year."W".$week.$day);
	
	echo "<td >".date('D',$d)."&nbsp;".date('d-m-Y',$d )."</td>";
}
?>
</tr>
<tr><td colspan="8" style="background:#333; color:#FFF; padding:3px;">STANDARD ROOMS</td></tr>
<?php 
$query="select * from room where Status='available' and cat_id=1 order by room_no";
$data=getData($query);
foreach($data as $x=>$value) { ?>
	<tr class="table-content"><td>Room <?php echo $value['room_no']; ?></td>
    <?php for($de=1; $de<=7; $de++) { 
    	$d = strtotime($year."W".$week.$de);
    	?>
	
     <td><?php $from = date('Y-m-d 11:00:00', $d);
     		 $to = date('Y-m-d 14:00:00', $d);
		$res=checkroom($from, $to, $value['room_no']);
		//echo count( $res);		
		if(count($res)== 2 && $from < $res[0]['departure_date']) {  $customer= getCustName($res[1]['cust_id']); echo $customer[0]['cust_name']; } else if(count($res)==1  && $from < $res[0]['departure_date']) { $customer= getCustName($res[0]['cust_id']); echo $customer[0]['cust_name'];  }?>
 </td>
        <?php  
		}?>
    
	</tr>
<?php } ?>
<tr><td colspan="8" style="background:#333; color:#FFF; padding:3px;">DELUXE ROOMS</td></tr>
<?php 
$query="select * from room where Status='available' and cat_id=2 order by room_no";
$data=getData($query);
foreach($data as $x=>$value) {  ?>
	<tr class="table-content"><td>Room <?php echo $value['room_no']; ?></td>
    <?php for($de=1; $de<=7; $de++) { 
    	$d = strtotime($year."W".$week.$de);
    	?>
	
     <td><?php $from = date('Y-m-d 11:00:00', $d);
     		 $to = date('Y-m-d 14:00:00', $d);
		$res=checkroom($from, $to, $value['room_no']);
				
		if(count($res)== 2 ) {  $customer= getCustName($res[1]['cust_id']); echo $customer[0]['cust_name']; } else if(count($res)==1) { $customer= getCustName($res[0]['cust_id']); echo $customer[0]['cust_name']; }?> </td>
        <?php  
		}?>
    
	</tr>
<?php } ?>
<tr><td colspan="8" style="background:#333; color:#FFF; padding:3px;">SUPERIOR ROOMS</td></tr>
<?php 
$query="select * from room where Status='available' and cat_id=3 order by room_no";
$data=getData($query);
foreach($data as $x=>$value) {  ?>
	<tr class="table-content"><td>Room <?php echo $value['room_no']; ?></td>
    <?php for($de=1; $de<=7; $de++) { 
    	$d = strtotime($year."W".$week.$de);
    	?>
	
     <td><?php $from = date('Y-m-d 11:00:00', $d);
     		 $to = date('Y-m-d 14:00:00', $d);
		$res=checkroom($from, $to, $value['room_no']);
				
		if(count($res)== 2 ) {  $customer= getCustName($res[1]['cust_id']); echo $customer[0]['cust_name']; } else if(count($res)==1) { $customer= getCustName($res[0]['cust_id']); echo $customer[0]['cust_name']; }?> </td>
        <?php  
		}?>
    
	</tr>
<?php } ?>
</table>
</div><!--end of main-content-->
</div><!--end of content-->
<!--<div id="footer" class="footer">
</div>-->
</body>
</html>
<?php } ?>