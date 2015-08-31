<?php
require_once('lib.php');
require_once('library/pdf/fpdf.php');
if(isset($_GET['id'])) {
	$cust=$_GET['id'];	
}
$html="<div class='voucher'>
<div class='logo-img'><img src='images/logo-voucher.png' width='144'  /></div>
<div class='top-invoice'>
<h4>Guest Details</h4>
<div class='top-item'><label>Guest Name:</label><label> ";
 $name=getCustName($cust);
 $html.=$name[0]['cust_name']."</label></div>
<div class='top-item'><label>Arrival Date:</label><label> ";
$qry="select * from reservation where cust_id='$cust'";
$result=getData($qry);
$date=date_create($result[0]['arrival_date']);
$html.=date_format($date,"l, d F Y")."</label></div>
<div class='top-item'><label>Room No:</label><label>1,5</label></div>
<div class='top-item'><label>Departure Date:</label><label> ";
$date=date_create($result[0]['departure_date']);
$html.=date_format($date,"l, d F Y")."</label></div>
<div class='top-item'><label>Mobile No:</label><label> ";
$phone=getCust($cust);
$html.=$phone[0]['phone_no']."</label></div>

<div class='top-item'><label>Email: </label><label> ";
$html.=$phone[0]['email_address']."</label></div>
</div>
<div class='invoice-table'>
<table cellspacing='0' border='0'>
<tr><td>Item Description</td><td>Charges</td><td>Credit</td><td>Balance</td></tr>";
$qry="select * from invoice where cust_id=$cust";
$data=getData($qry);
foreach($data as $val):
$sect=getSectionName($val['section_id']);
$id=$val['section_id'];
$html.="<tr><td>".$sect[0]['section_name']."</td><td>";
$qy="select sum(amount) as amount from invoice where cust_id=$cust and section_id=$id";
$charge=getData($qy);
$ch=$charge[0]['amount'];
$html.=number_format($charge[0]['amount'], 2, '.', ',')."</td><td>";
$qy="select sum(amount) as amount from payment where cust_id=$cust and section_id=$id";
$credit=getData($qy);
$cr=$credit[0]['amount'];
$html.=number_format($credit[0]['amount'], 2, '.', ',')."</td><td>";
$bal=$ch-$cr;
$html.=number_format($bal, 2, '.', ',')."</td></tr>";
endforeach;
$html.="</table>
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