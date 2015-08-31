<?php
require_once('lib.php');
if(( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15)) || !isset($_SESSION['user_name']) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
if(isset($_GET['id'])){
	$id=$_GET['id'];
	$query=" select * from season where season_id='".$id."'";
	$res=getData($query);
	foreach($res as $value) :
		$season_id=$value['season_id'];
		$season=$value['season_name'];
		$start_date=$value['start_date'];
		$end_date=$value['end_date'];		
	endforeach;	
}
if(isset($_POST['submit'])) {
	$id=$_POST['id'];
	$season=$_POST['season'];
	$start=$_POST['start_date'];
	$end=$_POST['end_date'];
	$date=date_create($start);
	$start=date_format($date,"Y-m-d");
	$date=date_create($end);
	$end=date_format($date,"Y-m-d");
	$query="update season set start_date='".$start."', end_date='".$end."', season_name='".$season."' where season_id='".$id."'";
	update($query);
	header('Location: season.php');
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
		var $from = $("#start"),
        $to = $("#end");
		$( "#datepicker" ).datepicker();
		$( "#start" ).datepicker({
			showOn: "button",
			minDate: 0,
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			onSelect: function (date) {
                var date2 = $('#start').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#end').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#end').datepicker('option', 'minDate', date2);
            }
		});
		
		$( "#end" ).datepicker({
			showOn: "button",
			buttonImage: "images/calendar.gif",
			buttonImageOnly: true,
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
				$( "#start" ).datepicker( "option", "maxDate", selectedDate );
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
	});
	</script>
</head>

<body>
<?php include_once('header.php'); ?>
<div class="menu-actions">
<ul>
<li><a href="roomprices.php">Rates</a></li>
<li><a href="addroomprices.php">Add Rate</a></li>
<li><a href="season.php">Seasons</a></li>
<li><a href="addseason.php">Add Season</a></li>
<li class="active"><a href="updateseason.php">Update Season</a></li>
</ul>
</div>
</div>
</div><!--end of dashboard-->
</div><!--end of header--->
<div style="clear: both;"></div>
<div id="content" class="content">
<h1> Update Season</h1>
<div class="main-content">
<div class="error-bar"><?php if(isset($error)) {	echo $error; }?> </div>
<div class="data-form">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<label>Start Date:</label><div class="cal"><input type="date" name="start_date" id="start" value="<?php echo $start_date; ?>"><div class="cal">
<label>End Date :</label><div class="cal"><input type="date" name="end_date" id="end" value="<?php echo $end_date; ?>"><div class="cal">
<label>Season Name :</label><input type="text" name="season" value="<?php echo $season; ?>">
<div class="controls">
<input type="hidden" name="id" value="<?php echo $season_id; ?>">
<input type="reset" value="Reset" class="form-reset">
<input type="submit" value="Save" name="submit" class="form-submit"> 
</div>
</form>
</div>
</div><!--end of main-content-->
</div><!--end of content-->
</body>
</html>
<?php } ?>