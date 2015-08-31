<?php
session_start();
if( isset($_SESSION["last_acted_on"]) && (time() - $_SESSION["last_acted_on"] > 60*15) ){
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
    header('Location:index.php');
}else{
    session_regenerate_id(true);
    $_SESSION["last_acted_on"] = time();
}
//SHOW A DATABASE ON A PDF FILE
//CREATED BY: Carlos Vasquez S.
//E-MAIL: cvasquez@cvs.cl
//CVS TECNOLOGIA E INNOVACION
//SANTIAGO, CHILE

require('pdf/fpdf.php');

//Connect to your database
include("lib.php");
//Select the Products you want to show in your PDF file
$result=getRes();
$num = count($result);

//Initialize the 3 columns and the total
$column_transaction = "";
$column_cash = "";
$column_float = "";
$column_total="";

//For each row, add the field to the corresponding column
foreach ($result as $i=>$row) {
    $order = $row["order_id"];
    $room =$row["room_no"];
    $arrive = $row["arrival_date"];
    //$total=$float+$cash;

    $column_transaction = $column_transaction.$order."\n";
    $column_cash = $column_cash.$room."\n";
    $column_float = $column_float.$arrive."\n";
	//$column_total=$column_total.$total."\n";

    //Sum all the Prices (TOTAL)
    //$total = $total+$real_price;
}
mysql_close();

//Convert the Total Price to a number with (.) for thousands, and (,) for decimals.
//$total = number_format($total,',','.','.');

//Create a new PDF file
$pdf=new FPDF();
$pdf->AddPage();

//Fields Name position
$Y_Fields_Name_position = 20;
//Table position, under Fields Name
$Y_Table_Position = 26;

//First create each Field Name
//Gray color filling each Field Name box
$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
$pdf->SetFont('Arial','B',12);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(25);
$pdf->Cell(40,6,'Order_id',1,0,'L',1);
$pdf->SetX(65);
$pdf->Cell(40,6,'Room_no',1,0,'L',1);
$pdf->SetX(105);
$pdf->Cell(40,6,'Arrival Date',1,0,'L',1);
$pdf->SetX(145);
$pdf->Cell(40,6,'Departure Date',1,0,'L',1);
$pdf->Ln();

//Now show the 3 columns
$pdf->SetFont('Arial','',12);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(25);
$pdf->MultiCell(40,6,$column_transaction,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(65);
$pdf->MultiCell(40,6,$column_float,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(105);
$pdf->MultiCell(40,6,$column_cash,1,'L');
$pdf->SetY($Y_Table_Position);
$pdf->SetX(145);
$pdf->MultiCell(40,6,$column_total,1,'L');


//Create lines (boxes) for each ROW (Product)
//If you don't use the following code, you don't create the lines separating each row
$i = 0;
$pdf->SetY($Y_Table_Position);
while ($i < $num)
{
    $pdf->SetX(25);
    $pdf->MultiCell(160,6,'',1);
    $i = $i +1;
}


$pdf->Output();
?>