<?php
require_once('lib.php');
require_once('library/pdf/fpdf.php');
if(isset($_GET['number'])) {
	$cust=$_GET['number'];	
}
$query="select * from invoice where cust_id='$cust' and section_id=5";
$data=getData($query);
$amount=number_format($data[0]['amount'], 2, '.', ','); 
$html="<div class='voucher'>
<div class='logo-img'><img src='images/logo-voucher.png' width='144'  /></div>
<h4>Dear ";
 $name=getCustName($cust);
 $html.=$name[0]['cust_name'].",	</h4>
<p>
Thank you for choosing Chaka Ranch. While you are here, we hope you will be able to experience all that Chaka has to offer. Below, please find your reservation confirmation number and additional details.
</p>
<div class='voucher-table'>
<table cellspacing='0' border='0'>
<tr><td>Confirmation Number:</td><td>";
$qry="select code from payment where cust_id=$cust and section_id=5";
$code=getData($qry);
$html.=$code[0]['code']."</td></tr>
<tr><td>Guest's Name: </td><td>";
$html.=$name[0]['cust_name']."</td></tr>
<tr><td>Arrival date: </td><td>";
$qry="select * from reservation where cust_id='$cust'";
$result=getData($qry);
$date=date_create($result[0]['arrival_date']);
$html.=date_format($date,"l, d F Y")."</td></tr>
<tr><td>Departure date: </td><td>";
$date=date_create($result[0]['departure_date']);
$html.=date_format($date,"l, d F Y")."</td></tr>
<tr><td>Check-in Time: </td><td>2.00 P.M</td></tr>
<tr><td>Check-out Time: </td><td>11.00 A.M</td></tr>
<tr><td>Number of Nights: </td><td>";
$datetime1 = new DateTime($result[0]['arrival_date']);
$datetime2 = new DateTime($result[0]['departure_date']);
$interval = $datetime1->diff($datetime2);
$html.=$interval->format('%R%a')."</td></tr>
<tr><td>Number of Adults: </td><td>";
$qry="select  sum(adults) as adults, sum(child_a) as child_a, sum(child_b) as child_b from room_reservation where order_id='".$result[0]['order_id']."'";
$room=getData($qry);
$html.=$room[0]['adults']."</td></tr>
<tr><td>Number of Children (5-12 years): </td><td>";
$html.=$room[0]['child_a']."</td></tr>
<tr><td>Number of Children below 5 years: </td><td>";
$html.=$room[0]['child_b']."</td></tr>
<tr><td>Room Type: </td><td>".$result[0]['description']."</td></tr>
<tr><td>Meal Plan: </td><td>".$result[0]['meal']."</td></tr>
<tr><td>Total Amount: </td><td>Ksh.";
$html.=$amount."</td></tr>
<tr><td>Deposit Amount: </td><td>Ksh.";
$qry="select sum(amount) as amount from payment where cust_id=$cust and section_id=5";
$dep=getData($qry);
$deposit=number_format($dep[0]['amount'], 2, '.', ',');
$html.=$deposit."</td></tr>
<tr><td>Amount Due: </td><td>Ksh.";
$number=$data[0]['amount']-$dep[0]['amount'];
$due=number_format($number, 2, '.', ',');
$html.=$due."</td></tr>
</table></div>
<p class='extras'>Extras: This voucher is for services as stated above only. All other expenses to be paid direct by clients.</p>

<div class='voucher-footer'><p>
Chaka Ranch Limited <br>
Nairobi: Green House, 3rd Floor Suite 9<br>
P.O. Box 21212 - 00505 Nairobi<br></p>
<p>
Nyeri:                     Kiganjo, Nyeri County <br>
Tel:                         0738600046, 0719242897, 0719242885. <br>
Email:                    info@chakaranch.com <br>
Website:                www.chakaranch.com
</p>
</div>
</div><!--end of voucher-->";
include("library/mpdf/mpdf.php");
$mpdf=new mPDF('c'); 
$stylesheet = file_get_contents('css/style.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
 ?>