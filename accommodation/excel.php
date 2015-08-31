<?php
//import the phpexecel library. 
require_once('lib.php');
require_once 'library/PHPExcel/Classes/PHPExcel.php';
//create an instance of phpexcel
//$headings = array('id','email address');
$headings = array( 'Reservation_no','Room_No','Customer_details','Contacts','Arrival_date', 'Departure_date','Adults','Children','status','Username'); 
$query="select reservation.order_id, room_reservation.room_no, CONCAT(fname,' ', mname ,' ', lname) as cust_name,phone_no,arrival_date, departure_date, adults, child_A,status,username from reservation, customer, room_reservation, users where reservation.cust_id=customer.cust_id and reservation.user_id=users.user_id and reservation.order_id=room_reservation.order_id order by MONTH(arrival_date), DAYOFMONTH(arrival_date)";
//$query="select * from email"; 
$result=mysql_query($query);
if ($result) { 
    // Create a new PHPExcel object 
    $objPHPExcel = new PHPExcel(); 
    $objPHPExcel->getActiveSheet()->setTitle('Reservation Details'); 

    $rowNumber = 1; 
    $col = 'A'; 
    foreach($headings as $heading) { 
       $objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,$heading); 
       $col++; 
    } 

    // Loop through the result set 
    $rowNumber = 2; 
    while ($row = mysql_fetch_row($result)) { 
       $col = 'A'; 
       foreach($row as $cell) { 
          $objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,$cell); 
          $col++; 
       } 
       $rowNumber++; 
    } 
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('F')->setWidth(20);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('H')->setWidth(10);
	$objPHPExcel->setActiveSheetIndex(0)->getColumnDimension('I')->setWidth(20);
    // Freeze pane so that the heading line won't scroll 
    $objPHPExcel->getActiveSheet()->freezePane('A2'); 

    // Save as an Excel BIFF (xls) file 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 

   header('Content-Type: application/vnd.ms-excel'); 
   header('Content-Disposition: attachment;filename="accommodation.xls"'); 
   header('Cache-Control: max-age=0'); 

   $objWriter->save('php://output'); 
  // header('Location: reservation.php');
} 
?>
