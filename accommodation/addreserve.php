<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_POST['delete'])) {
	$data=$_POST['order'];
	
	for($i=0; $i<count($data); $i++) {
		$query="delete from reservation where order_id='".$data[$i]."'";
		update($query);
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
</head>

<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li><a href="reservation.php">Reservations</a></li>
<li class="active"><a href="addreserve.php">Add Reservation</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1>Book Room</h1>
<div class="main-content">
<div class="data-form">
<form action="reserve.php" method="post" >
<label>Arrival date </label> <div class="cal"><input type="date" id="from" name="arrival_date" value="<?php echo date('d-m-Y'); ?>"></div>
<label>Departure date </label><div class="cal"><input type"date" id="to" name="departure_date" value="<?php echo date('d-m-Y'); ?>"></div>
<label>Room Type </label><select name="room_type"><?php
 $type=getRoomType();
 foreach($type as $value) : ?><option value="<?php echo $value['cat_id']; ?>"><?php echo $value['category']; ?></option><?php endforeach; ?></select>
 <label>Room Description </label><select name="description"><?php
 $type=getRoomSize();
 foreach($type as $value) : ?><option value="<?php echo $value['room_id']; ?>"><?php echo $value['room_description']; ?></option><?php endforeach; ?></select>
 <div class="controls">
 <input type="reset" name="clear" value="Reset" class="form-reset"  >
 <input type="submit" name="reserve" value="Proceed" class="form-submit"  >
 </div>
</form>
</div>
</div><!--end of main-content--->
</div><!--end of content-->

</body>
</html>
<?php }?>