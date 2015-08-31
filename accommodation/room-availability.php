<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_GET['number'])) {
$query="select arrival_date, departure_date from reservation where room_no='".$_GET['number']."'";
//$query="select arrival_date, departure_date from reservation ";
$data=getData($query);
} 
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
<link href='calendar/fullcalendar-2.3.1/fullcalendar.css' rel='stylesheet' />
<link href='calendar/fullcalendar-2.3.1/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='calendar/fullcalendar-2.3.1/lib/moment.min.js'></script>
<script src='calendar/fullcalendar-2.3.1/lib/jquery.min.js'></script>
<script src='calendar/fullcalendar-2.3.1/fullcalendar.min.js'></script>
<script>
	var data=<?php echo json_encode($data);?>;
	var evt = []; //The array
	for(var i =0; i < data.length; i++) 
	{			
		evt.push( { title: 'BOOKED', start: data[i]['arrival_date'], end :data[i]['departure_date'], overlap: false,	rendering: 'background',color: '#ff9f89'})
		}

	$(document).ready(function() {

		$('#calendar').fullCalendar({
			header: {
				left: '',
				center: 'title',
				right: 'prev,next '
			},
			defaultDate: '2015-03-12',
			defaultView: 'agendaWeek',
			businessHours: true, // display business hours
			editable: true,
			eventTextColor: 'black',
			allDayDefault: true,
			events: evt,
			height:120			
		});
		
	});

</script>
<style>

	body {
		margin: 40px 10px;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#calendar {
		
		margin: 0 auto;
	}

</style>
</head>

<body>


<div id="content" class="content">
<h1>Reservation for room &nbsp;<?php echo $_GET['number']; ?></h1>
<div class="main-content">

<div id='calendar'></div>
</div><!--end of main-content-->
</div><!--end of content-->

</body>
</html>
<?php } ?>