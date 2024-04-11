<?php
require '../bed-fpdf/fpdf.php';
require '../../includes/conn.php';




class PDF extends FPDF
{

    // Page header

    function Header()
    {
        $date = date("Y-m-d");

        // Logo(x axis, y axis, height, width)
        // $this->Image('../../assets/images/logo.png', 25, 13, 19, 15);
        // font(font type,style,font size)
        $this->SetFont('Times', 'B', 28);
        $this->SetTextColor(240, 0, 0);
        // Dummy cell
        $this->Cell(50);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(110, 15, 'Saint Francis of Assisi College', 0, 1, 'C');
        $this->Ln(1);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(210, 2, 'BASIC EDUCATION', 0, 1, 'C');
        // Line break
        $this->Ln(1);
        $this->SetFont('Arial', 'B', 9);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(210, 4, 'Daily Enrollment Update', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(210, 5, 'School Year 2023-2024', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(40, 5, 'Campus: Bacoor Campus', 0, 0, 'C');
        $this->Cell(200, 5, 'Date: '. $date, 0, 1, 'C');
        $this->Cell(200, 5, 'Bacoor Campus', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 10);
        $this->Ln(2);
        $this->Rect(5,11,205,340); // box
    }
}

if (isset($_GET['date'])) {
    $date = $_GET['date'];
} else {
    $date = date("Y-m-d");
}

$lessdate = date('Y-m-d', strtotime($date. ' - 1 year'));



$get_ay = mysqli_query($conn, "SELECT * FROM tbl_active_acadyears AS aay
LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = aay.ay_id");
while ($row = mysqli_fetch_array($get_ay)) {
    $ay_id = $row['ay_id'];
    $acad = $row['academic_year'];
}

$get_sem = mysqli_query($conn, "SELECT * FROM tbl_active_semesters AS asem
LEFT JOIN tbl_semesters AS sem ON sem.semester_id = asem.semester_id");
while ($row = mysqli_fetch_array($get_sem)) {
    $sem_id = $row['semester_id'];
    $sem = $row['semester'];
}

$get_ay = mysqli_query($conn, "SELECT * FROM tbl_acadyears WHERE ay_id < '$ay_id' ORDER BY academic_year DESC LIMIT 1");
while ($row = mysqli_fetch_array($get_ay)) {
    if ($ay_id > $row['ay_id']) {
    $ay_id_past = $row['ay_id'];
    $acad_past = $row['academic_year'];
}
}

$object_new = [];
$object_old = [];
$object_total = [];

$breakdowninfo = mysqli_query($conn, "SELECT * FROM tbl_breakdown
LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_breakdown.grade_level WHERE date = '$date'");
while ($row = mysqli_fetch_array($breakdowninfo)) {

        $object_new[$row['grade_level']] = ['daily_new' => $row['daily_new'], 'reservations_new' => $row['reservations_new']];
        $object_old[$row['grade_level']] = ['daily_old' => $row['daily_old'], 'reservations_old' => $row['reservations_old']];

        $temp_total = $row['daily_new'] + $row['daily_old'];
        $temp1_total = $row['reservations_new'] + $row['reservations_old'];

        $object_total[$row['grade_level']] = ['daily_total' => $temp_total, 'reservations_total' => $temp1_total];

}


$target_new = [];
$target_old = [];
$target_total = [];

$target_total_new = 0;
$target_total_old = 0;

$targetinfo = mysqli_query($conn, "SELECT * FROM tbl_target
LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_target.grade_level");
while ($row = mysqli_fetch_array($targetinfo)) {

    $target_new[$row['grade_level']] = ['target_new' => $row['target_new']];
    $target_old[$row['grade_level']] = ['target_old' => $row['target_old']];

    $temp_total = $row['target_new'] + $row['target_old'];

    $target_total[$row['grade_level']] = ['target_total' => $temp_total];

    $target_total_new = $target_total_new + $row['target_new'];
    $target_total_old = $target_total_old + $row['target_old'];

}

// $breakdowninfo = mysqli_query($conn, "SELECT * FROM tbl_breakdown
// LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_breakdown.grade_level WHERE date = '$date'");
// while ($row = mysqli_fetch_array($breakdowninfo)) {

//     $object_old[$row['grade_level']] = ['daily_old' => $row['daily_old'], 'reservations_old' => $row['reservations_old']];

// }



$students = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id' AND semester_id IN ('0', '$sem_id') AND remark = 'Approved' AND stud_type = 'New'");
$new_students = mysqli_num_rows($students);

$students = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id' AND semester_id IN ('0', '$sem_id') AND remark = 'Approved' AND stud_type = 'Old'");
$old_students = mysqli_num_rows($students);



$pdf = new PDF('P', 'mm', 'Legal');
$pdf->SetAutoPageBreak(true, 4);
//left top right
$pdf->SetMargins(6, 10, 7);
$pdf->AddPage();
// $pdf ->Rect(7,76,150,64); // box
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);
$pdf->SetFont('Arial', '', 7);

$pdf->SetFillColor(255, 0,0); // COLOR PER BOX RED

$pdf ->Rect(5,49,38,7,true);//box
$pdf ->Rect(5,49,38,7);//box
$pdf->Cell(5,2,'',0,0);
$pdf->Cell(23, 0, 'PARTICULARS', 0, 0,'C');

$pdf ->Rect(43,49,23,7,true);//box
$pdf ->Rect(43,49,23,7);//box
$pdf->Cell(5,2,'',0,0);
$pdf->Cell(32, 0, 'S.Y.2022-2023', 0,0,'C'); //galeng

$pdf ->Rect(66,49,23,7,true);//box
$pdf ->Rect(66,49,23,7);//box
$pdf->Cell(2,2,'',0,0);
$pdf->Cell(10, 0, 'S.Y.2023-2024', 0,0,'C'); //galeng

$pdf ->Rect(89,49,23,7,true);//box
$pdf ->Rect(89,49,23,7);//box
$pdf->Cell(2,2,'',0,0);
$pdf->Cell(30, 0, 'VARIANCE', 0,0,'C'); //galeng

$pdf ->Rect(112,49,25,11);//box
$pdf->Cell(3,2,'',0,0);
$pdf->Cell(12, 0, 'TARGET', 0, 0,'C'); 

$pdf ->Rect(137,49,25,11);//box
$pdf->Cell(8,2,'',0,0);
$pdf->Cell(22, 0, 'ENROLLEES', 0, 0,'C'); 

$pdf ->Rect(162,49,25,11);//box
$pdf->Cell(5,2,'',0,0);
$pdf->Cell(20, 0, 'VARIANCE', 0, 0,'C'); 

$pdf ->Rect(187,49,23,11);//box
$pdf->Cell(4,2,'',0,0);
$pdf->Cell(20, 0, 'PERCENTAGES', 0, 1,'C');

$pdf->Ln(5);
$pdf->SetFillColor(255, 255, 0 ); // yellow
$pdf ->Rect(5,55,38,5,true);//box
$pdf ->Rect(5,55,38,5);//box
$pdf->Cell(6,4,'',0,0);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(24.5, 0, 'NURSERY TO GRADE 10', 0, 1,'C');

$pdf ->Rect(43,55,69,5,true);//box lang
$pdf ->Rect(43,55,69,5);//box lang
$pdf->Cell(5,2,'',0,0);
$pdf->Cell(20, 1, '', 0, 1,'C');

$pdf->Ln(5);
$pdf ->Rect(5,60,25,10);//box
$pdf->Cell(5,1,'',0,0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(12, 5, 'INQUIRIES', 0, 0,'C'); 

$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,60,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, 'Walk-in', 0, 0,'C'); 

$pdf ->Rect(43,60,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(13, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(66,60,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(20, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(89,60,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, '', 0, 0,'C'); // for data ata ikaw bahala san ka masaya

$pdf ->Rect(112,60,12,5);//box
$pdf->Cell(7,0,'',0,0);
$pdf->Cell(10, 0, 'NEW', 0, 0,'C');
$pdf ->Rect(124,60,13,5);//box
$pdf->Cell(3,0,'',0,0);
$pdf->Cell(10, 0, $target_total_new, 0, 0,'C'); // data  oo ata

$pdf ->Rect(137,60,12,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(5, 0, 'NEW', 0, 0,'C');
$pdf ->Rect(149,60,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(8, 0, $new_students, 0, 0,'C'); // data  oo ata

$pdf ->Rect(162,60,12,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(10, 0, 'NEW', 0, 0,'C');
$pdf ->Rect(174,60,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(4, 0, $target_total_new - $new_students, 0, 0,'C'); // data  oo ata

$pdf ->Rect(187,60,23,5);//box
$pdf->Cell(10,0,'',0,0);
$pdf->Cell(10, 0, '', 0, 1,'C');
/////////////////////////////////////////////// end
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,65,13,5);//box
$pdf->Cell(18,6,'',0,0);
$pdf->Cell(22, 0, 'Online', 0, 0,'C'); 

$pdf ->Rect(43,65,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(7, 0, '', 0, 0,'C'); // for data ata

$pdf ->Rect(66,65,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(26, 0, '', 0, 0,'C'); // for data ata

$pdf ->Rect(89,65,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(9, 0, '', 0, 0,'C'); // for data ata ikaw bahala san ka masaya

$pdf ->Rect(112,65,12,5);//box
$pdf->Cell(10,6,'',0,0);
$pdf->Cell(10, 0, 'OLD', 0, 0,'C');
$pdf ->Rect(124,65,13,5);//box
$pdf->Cell(3,6,'',0,0);
$pdf->Cell(10, 0, $target_total_old, 0, 0,'C'); // data  oo ata

$pdf ->Rect(137,65,12,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(5, 0, 'OLD', 0, 0,'C');
$pdf ->Rect(149,65,13,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(8, 0, $old_students, 0, 0,'C'); // data  oo ata

$pdf ->Rect(162,65,12,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(10, 0, 'OLD', 0, 0,'C');
$pdf ->Rect(174,65,13,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(4, 0, $target_total_old - $old_students, 0, 0,'C'); // data  oo ata

$pdf ->Rect(187,65,23,5);//box
$pdf->Cell(10,6,'',0,0);
$pdf->Cell(10, 0, '', 0, 1,'C');
//////////////////////////////////// end inquries

$breakdown = mysqli_query($conn, "SELECT COALESCE(SUM(daily_new), 0) AS daily_new, COALESCE(SUM(daily_old), 0) AS daily_old FROM tbl_breakdown WHERE grade_level IN ('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13') AND date = '$date'");
$row = mysqli_fetch_array($breakdown);

$breakdown_past = mysqli_query($conn, "SELECT COALESCE(SUM(daily_new), 0) AS daily_new, COALESCE(SUM(daily_old), 0) AS daily_old FROM tbl_breakdown WHERE grade_level IN ('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13') AND date <= '$lessdate' ORDER BY date DESC LIMIT 1");
$row_past = mysqli_fetch_array($breakdown_past);

$pdf->Ln(5);
$pdf ->Rect(5,70,25,10);//box
$pdf->Cell(5,1,'',0,0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(12, 5, 'ENROLLEES', 0, 0,'C'); 

$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,70,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, 'New', 0, 0,'C'); 

$pdf ->Rect(43,70,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(13, 0, $row_past['daily_new'], 0, 0,'C'); // for data ata
$pdf ->Rect(66,70,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(20, 0, $row['daily_new'], 0, 0,'C'); // for data ata
$pdf ->Rect(89,70,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, $row['daily_new'] - $row_past['daily_new'], 0, 0,'C'); // for data ata ikaw bahala san ka masaya

$pdf ->Rect(112,70,12,5);//box
$pdf->Cell(7,0,'',0,0);
$pdf->Cell(10, 0, 'TOTAL', 0, 0,'C');
$pdf ->Rect(124,70,13,5);//box
$pdf->Cell(3,0,'',0,0);
$pdf->Cell(10, 0, $target_total_new + $target_total_old, 0, 0,'C'); // data  oo ata

$pdf ->Rect(137,70,12,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(5, 0, 'TOTAL', 0, 0,'C');
$pdf ->Rect(149,70,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(8, 0, $old_students + $new_students, 0, 0,'C'); // data  oo ata

$pdf ->Rect(162,70,12,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(10, 0, 'TOTAL', 0, 0,'C');
$pdf ->Rect(174,70,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(4, 0, ($target_total_new + $target_total_old) - ($old_students + $new_students), 0, 0,'C'); // data  oo ata

$pdf ->Rect(187,70,23,5);//box
$pdf->Cell(10,0,'',0,0);
$pdf->Cell(10, 0, '', 0, 1,'C');
/////////////////////////////////////////////// end
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,75,13,5);//box
$pdf->Cell(18,6,'',0,0);
$pdf->Cell(22, 0, 'Old', 0, 0,'C'); 

$pdf ->Rect(43,75,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(7, 0, $row_past['daily_old'], 0, 0,'C'); // for data ata

$pdf ->Rect(66,75,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(26, 0, $row['daily_old'], 0, 0,'C'); // for data ata

$pdf ->Rect(89,75,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(9, 0, $row['daily_old'] - $row_past['daily_old'], 0, 1,'C'); // for data ata ikaw bahala san ka masaya
/////////////////////////////////// end enroll

$breakdown2 = mysqli_query($conn, "SELECT COALESCE(SUM(daily_new), 0) AS daily_new, COALESCE(SUM(daily_old), 0) AS daily_old FROM tbl_breakdown WHERE grade_level IN ('14') AND date = '$date'");
$row2 = mysqli_fetch_array($breakdown2);

$breakdown_past2 = mysqli_query($conn, "SELECT COALESCE(SUM(daily_new), 0) AS daily_new, COALESCE(SUM(daily_old), 0) AS daily_old FROM tbl_breakdown WHERE grade_level IN ('14') AND date <= '$lessdate' ORDER BY date DESC LIMIT 1");
$row_past2 = mysqli_fetch_array($breakdown_past2);

$pdf->Ln(5);
$pdf ->Rect(5,80,38,5,true);//box
$pdf ->Rect(5,80,38,5);//box
$pdf->Cell(6,0,'',0,0);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(5, 0, 'Grade 11', 0, 1,'C');
$pdf ->Rect(43,80,69,5,true);//box lang
$pdf ->Rect(43,80,69,5);//box lang
$pdf->Cell(5,2,'',0,0);
$pdf->Cell(20, 0, '', 0, 1,'C');

$pdf->Ln(5);
$pdf ->Rect(5,85,25,10);//box
$pdf->Cell(5,3,'',0,0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(12, 5, 'INQUIRIES', 0, 0,'C'); 

$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,85,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, 'Walk-in', 0, 0,'C'); 
$pdf ->Rect(43,85,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(13, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(66,85,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(20, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(89,85,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, '', 0, 1,'C'); // for data ata ikaw bahala san ka masaya

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,90,13,5);//box
$pdf->Cell(19,6,'',0,0);
$pdf->Cell(20, 0, 'Online', 0, 0,'C'); 
$pdf ->Rect(43,90,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(9, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(66,90,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(24, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(89,90,23,5);//box
$pdf->Cell(6,6,'',0,0);
$pdf->Cell(9, 0, '', 0, 1,'C'); // for data ata ikaw bahala san ka masaya

$pdf->Ln(5);
$pdf ->Rect(5,95,25,10);//box
$pdf->Cell(5,3,'',0,0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(12, 5, 'ENROLLEES', 0, 0,'C'); 

$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,95,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, 'New', 0, 0,'C'); 
$pdf ->Rect(43,95,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(13, 0, $row_past2['daily_new'], 0, 0,'C'); // for data ata
$pdf ->Rect(66,95,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(20, 0, $row2['daily_new'], 0, 0,'C'); // for data ata
$pdf ->Rect(89,95,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, $row2['daily_new'] - $row_past2['daily_new'], 0, 1,'C'); // for data ata ikaw bahala san ka masaya

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,100,13,5);//box
$pdf->Cell(19,6,'',0,0);
$pdf->Cell(20, 0, 'Old', 0, 0,'C'); 
$pdf ->Rect(43,100,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(9, 0, $row_past2['daily_old'], 0, 0,'C'); // for data ata
$pdf ->Rect(66,100,23,5);//box
$pdf->Cell(5,6,'',0,0);
$pdf->Cell(24, 0, $row2['daily_old'], 0, 0,'C'); // for data ata
$pdf ->Rect(89,100,23,5);//box
$pdf->Cell(6,6,'',0,0);
$pdf->Cell(9, 0, $row2['daily_old'] - $row_past2['daily_old'], 0, 1,'C'); // for data ata ikaw bahala san ka masaya
/////////////////////////////////// end enroll

$breakdown3 = mysqli_query($conn, "SELECT COALESCE(SUM(daily_new), 0) AS daily_new, COALESCE(SUM(daily_old), 0) AS daily_old FROM tbl_breakdown WHERE grade_level IN ('15') AND date = '$date'");
$row3 = mysqli_fetch_array($breakdown3);

$breakdown_past3 = mysqli_query($conn, "SELECT COALESCE(SUM(daily_new), 0) AS daily_new, COALESCE(SUM(daily_old), 0) AS daily_old FROM tbl_breakdown WHERE grade_level IN ('15') AND date <= '$lessdate' ORDER BY date DESC LIMIT 1");
$row_past3 = mysqli_fetch_array($breakdown_past3);
$pdf->Ln(5);
$pdf ->Rect(5,105,38,5,true);//box
$pdf ->Rect(5,105,38,5);//box
$pdf->Cell(6,0,'',0,0);
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(5, 0, 'Grade 12', 0, 0,'C');
$pdf ->Rect(43,105,69,5,true);//box lang
$pdf ->Rect(43,105,69,5);//box lang
$pdf->Cell(5,2,'',0,0);
$pdf->Cell(20, 0, '', 0, 0,'C');


$pdf->Cell(25,3,'',0,0); // space lang
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetTextColor(200,0,0); // color text for remarks
$pdf->Cell(115, 0, ' Remarks: ', 0, 1,'C'); 
// ////// 
$pdf->SetTextColor(0,0,0); // color TEXT

$pdf->Ln(5);
$pdf ->Rect(5,110,25,10);//box
$pdf->Cell(5,3,'',0,0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(12, 5, 'INQUIRIES', 0, 0,'C'); 

$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,110,13,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, 'Walk-in', 0, 0,'C'); 
$pdf ->Rect(43,110,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(13, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(66,110,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(20, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(89,110,23,5);//box
$pdf->Cell(5,0,'',0,0);
$pdf->Cell(15, 0, '', 0, 1,'C'); // for data ata ikaw bahala san ka masaya


//////////// FOR REMARKS LINE
$pdf->Cell(114,1,'',0,0); // space lang
$pdf->Cell(5, 0, '', 0, 0, 'C'); // data for remarks ATA ATA HA
$pdf->Cell(60, 0, '', 'B', 1, 'C'); // space lang sa line
$pdf->Ln(5);
$pdf ->Rect(30,115,13,5);//box
$pdf->Cell(17,1,'',0,0);
$pdf->Cell(24, 0, 'Online', 0, 0,'C'); 
$pdf ->Rect(43,115,23,5);//box
$pdf->Cell(3,1,'',0,0);
$pdf->Cell(9, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(66,115,23,5);//box
$pdf->Cell(5,1,'',0,0);
$pdf->Cell(24, 0, '', 0, 0,'C'); // for data ata
$pdf ->Rect(89,115,23,5);//box
$pdf->Cell(5,1,'',0,0);
$pdf->Cell(11, 0, '', 0, 1,'C'); // for data ata ikaw bahala san ka masaya
//////////// FOR REMARKS LINE
$pdf->Cell(114,1,'',0,0); // space lang
$pdf->Cell(5, 0, '', 0, 0, 'C'); // data for remarks ATA ATA
$pdf->Cell(60, 0, '', 'B', 1, 'C'); // space lang sa line

$pdf->Ln(5);
$pdf ->Rect(5,120,25,10);//box
$pdf->Cell(5,3,'',0,0);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(12,5, 'ENROLLEES', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 7.5);
$pdf ->Rect(30,120,13,5);//box
$pdf->Cell(5,1,'',0,0);
$pdf->Cell(15, 0, 'New', 0, 0,'C'); 
$pdf ->Rect(43,120,23,5);//box
$pdf->Cell(5,1,'',0,0);
$pdf->Cell(13, 0, $row_past3['daily_new'], 0, 0,'C'); // for data ata
$pdf ->Rect(66,120,23,5);//box
$pdf->Cell(5,1,'',0,0);
$pdf->Cell(20, 0, $row3['daily_new'], 0, 0,'C'); // for data ata
$pdf ->Rect(89,120,23,5);//box
$pdf->Cell(5,1,'',0,0);
$pdf->Cell(15, 0, $row3['daily_new'] - $row_past3['daily_new'], 0, 1,'C'); // for data ata ikaw bahala san ka masaya
//////////// FOR REMARKS LINE
$pdf->Cell(114,1,'',0,0); // space lang
$pdf->Cell(5, 0, '', 0, 0, 'C'); // data for remarks ATA ATA HA
$pdf->Cell(60, 0, '', 'B', 1, 'C'); // space lang sa line

$pdf->Ln(5);
$pdf ->Rect(30,125,13,5);//box
$pdf->Cell(17, 0,'',0,0);
$pdf->Cell(24, 0, 'Old', 0, 0,'C'); 
$pdf ->Rect(43,125,23,5);//box
$pdf->Cell(3, 0,'',0,0);
$pdf->Cell(9, 0, $row_past3['daily_old'], 0, 0,'C'); // for data ata
$pdf ->Rect(66,125,23,5);//box
$pdf->Cell(5, 0,'',0,0);
$pdf->Cell(24, 0, $row3['daily_old'], 0, 0,'C'); // for data ata
$pdf ->Rect(89,125,23,5);//box
$pdf->Cell(5, 0,'',0,0);
$pdf->Cell(11, 0, $row3['daily_old'] - $row_past3['daily_old'], 0,  1,'C'); // for data ata ikaw bahala san ka masaya
//////////// FOR REMARKS LINE
$pdf->Cell(114,1,'',0,0); // space lang
$pdf->Cell(5, 0, '', 0, 0, 'C'); // data for remarks ATA ATA
$pdf->Cell(60, 0, '', 'B', 1, 'C'); // space lang sa line
/////////////////////////////////////////////////////////////
$pdf->Ln(5);
$pdf->SetFillColor(255, 0,0); // COLOR PER BOX red
$pdf->SetTextColor(255,255,255); // color text for Grade level
$pdf ->Rect(5,135,25,10, true);//box
$pdf ->Rect(5,135,25,10);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(24, 15, 'GRADE LEVELS', 0, 0,'C'); 

$pdf ->Rect(30,135,36,10,true);//box
$pdf ->Rect(30,135,36,10);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 10, 'Final Enrollment', 0, 0,'C');

$pdf ->Rect(66,135,36,10,true);//box
$pdf ->Rect(66,135,36,10);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 10, 'Target/Level', 0, 0,'C');

$pdf ->Rect(102,135,36,10,true);//box
$pdf ->Rect(102,135,36,10);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 15, 'Daily Enrollees', 0, 0,'C');

$pdf ->Rect(138,135,36,10,true);//box
$pdf ->Rect(138,135,36,10);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 10, 'Total Enrollees', 0, 0,'C');

$pdf ->Rect(174,135,36,10,true);//box
$pdf ->Rect(174,135,36,10);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(36, 10, 'Total Reservations', 0, 1,'C');

$pdf->Ln(-1);
$pdf->Cell(24,1,'',0,0); // space lang
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(36, 0, 'S.Y.'. $acad_past, 0, 0,'C'); // ilalim ng final

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(36, 0, 'S.Y. 2023-2024', 0, 0,'C'); // ilalim ng target

$pdf->Cell(36,1,'',0,0); // space lang
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(36, 0, 'S.Y. 2023-2024', 0, 0,'C'); // ilalim ng enrollees


$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(36, 0, 'S.Y. 2023-2024', 0, 1,'C'); // ilalim ng total
// /////////////////////////////////////////////////////////////////////////////////////////////////

$pdf->Ln(6);


$departments = ['Pre-school', 'Grade School', 'Intermediate', 'Junior', 'Grade 11', 'Grade 12'];
$height = 145;

foreach ($departments as $index) {
$pdf->SetTextColor(0,0,0); // color text
$pdf->SetFillColor(166, 166,166); // COLOR PER BOX gray
$pdf ->Rect(5,$height,25,5,true);//box
$pdf ->Rect(5,$height,25,5);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(24, 0, $index, 0, 0,'C'); 

$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(30,$height,12,5,true);//box
$pdf ->Rect(30,$height,12,5);//box
$pdf->Cell(12, 0, 'OLD', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(42,$height,12,5,true);//box
$pdf ->Rect(42,$height,12,5);//box
$pdf->Cell(12, 0, 'NEW', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(54,$height,12,5,true);//box
$pdf ->Rect(54,$height,12,5);//box
$pdf->Cell(12, 0, 'TOTAL', 0, 0,'C'); 

$pdf ->Rect(66,$height,12,5,true);//box
$pdf ->Rect(66,$height,12,5);//box
$pdf->Cell(12, 0, 'OLD', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(78,$height,12,5,true);//box
$pdf ->Rect(78,$height,12,5);//box
$pdf->Cell(12, 0, 'NEW', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(90,$height,12,5,true);//box
$pdf ->Rect(90,$height,12,5);//box
$pdf->Cell(12, 0, 'TOTAL', 0, 0,'C'); 

$pdf ->Rect(102,$height,12,5,true);//box
$pdf ->Rect(102,$height,12,5);//box
$pdf->Cell(12, 0, 'OLD', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(114,$height,12,5,true);//box
$pdf ->Rect(114,$height,12,5);//box
$pdf->Cell(12, 0, 'NEW', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(126,$height,12,5,true);//box
$pdf ->Rect(126,$height,12,5);//box
$pdf->Cell(12, 0, 'TOTAL', 0, 0,'C'); 

$pdf ->Rect(138,$height,12,5,true);//box
$pdf ->Rect(138,$height,12,5);//box
$pdf->Cell(12, 0, 'OLD', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(150,$height,12,5,true);//box
$pdf ->Rect(150,$height,12,5);//box
$pdf->Cell(12, 0, 'NEW', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(162,$height,12,5,true);//box
$pdf ->Rect(162,$height,12,5);//box
$pdf->Cell(12, 0, 'TOTAL', 0, 0,'C'); 

$pdf ->Rect(174,$height,12,5,true);//box
$pdf ->Rect(174,$height,12,5);//box
$pdf->Cell(12, 0, 'OLD', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(186,$height,12,5,true);//box
$pdf ->Rect(186,$height,12,5);//box
$pdf->Cell(12, 0, 'NEW', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(198,$height,12,5,true);//box
$pdf ->Rect(198,$height,12,5);//box
$pdf->Cell(12, 0, 'TOTAL', 0, 1,'C'); 

$height = $height + 5;

if ($index == 'Pre-school') {
    $preschool = mysqli_query($conn, "SELECT * FROM tbl_grade_levels WHERE grade_level_id IN ('1', '2', '3')");

} elseif ($index == 'Grade School') {
    $preschool = mysqli_query($conn, "SELECT * FROM tbl_grade_levels WHERE grade_level_id IN ('4', '5', '6')");

} elseif ($index == 'Intermediate') {
    $preschool = mysqli_query($conn, "SELECT * FROM tbl_grade_levels WHERE grade_level_id IN ('7', '8', '9')");

} elseif ($index == 'Junior') {
    $preschool = mysqli_query($conn, "SELECT * FROM tbl_grade_levels WHERE grade_level_id IN ('10', '11', '12', '13')");

} elseif ($index == 'Grade 11') {
    $preschool = mysqli_query($conn, "SELECT * FROM tbl_grade_levels JOIN tbl_strands WHERE grade_level_id IN ('14')");

} elseif ($index == 'Grade 12') {
    $preschool = mysqli_query($conn, "SELECT * FROM tbl_grade_levels JOIN tbl_strands WHERE grade_level_id IN ('15')");

}

$sub_new = 0;
$sub_old = 0;
$sub_total = 0;

$sub_new_past = 0;
$sub_old_past = 0;
$sub_total_past = 0;

$sub_daily_new = 0;
$sub_daily_old = 0;
$sub_daily_total = 0;

$sub_reservations_new = 0;
$sub_reservations_old = 0;
$sub_reservations_total = 0;

$sub_target_new = 0;
$sub_target_old = 0;
$sub_target_total = 0;

while ($row = mysqli_fetch_array($preschool)) {

    if ($row['grade_level_id'] == 14 || $row['grade_level_id'] == 15) {
        $students_past = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id_past' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND strand_id = '$row[strand_id]' AND remark = 'Approved' AND stud_type = 'New'");
        $new_students_past = mysqli_num_rows($students_past);

        $students_past = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id_past' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND strand_id = '$row[strand_id]' AND remark = 'Approved' AND stud_type = 'Old'");
        $old_students_past = mysqli_num_rows($students_past);

        $students = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND strand_id = '$row[strand_id]' AND remark = 'Approved' AND stud_type = 'New'");
        $new_students = mysqli_num_rows($students);

        $students = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND strand_id = '$row[strand_id]' AND remark = 'Approved' AND stud_type = 'Old'");
        $old_students = mysqli_num_rows($students);

        $breakdown = mysqli_query($conn, "SELECT *, SUM(daily_old + daily_new) AS daily_total, SUM(reservations_new + reservations_old) AS reservations_total FROM tbl_breakdown  WHERE grade_level = '$row[grade_level_id]' AND strand_id = '$row[strand_id]' AND date = '$date'");
        $row2 = mysqli_fetch_array($breakdown);
        
    } else {
        $students_past = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id_past' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND remark = 'Approved' AND stud_type = 'New'");
        $new_students_past = mysqli_num_rows($students_past);

        $students_past = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id_past' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND remark = 'Approved' AND stud_type = 'Old'");
        $old_students_past = mysqli_num_rows($students_past);

        $students = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND remark = 'Approved' AND stud_type = 'New'");
        $new_students = mysqli_num_rows($students);

        $students = mysqli_query($conn, "SELECT * FROM tbl_schoolyears WHERE ay_id = '$ay_id' AND semester_id IN ('0', '$sem_id') AND grade_level_id = '$row[grade_level_id]' AND remark = 'Approved' AND stud_type = 'Old'");
        $old_students = mysqli_num_rows($students);

        $breakdown = mysqli_query($conn, "SELECT *, SUM(daily_old + daily_new) AS daily_total, SUM(reservations_new + reservations_old) AS reservations_total FROM tbl_breakdown  WHERE grade_level = '$row[grade_level_id]' AND date = '$date'");
        $row2 = mysqli_fetch_array($breakdown);

    }
    $target = mysqli_query($conn, "SELECT *, SUM(target_old + target_new) AS target_total FROM tbl_target  WHERE grade_level = '$row[grade_level_id]'");
    $row3 = mysqli_fetch_array($target);

    $total_students_past = $old_students_past + $new_students_past;

    $sub_new_past = $sub_new_past + $new_students_past;
    $sub_old_past = $sub_old_past + $old_students_past;
    $sub_total_past = $sub_total_past + $total_students_past;

    $total_students = $old_students + $new_students;

    $sub_new = $sub_new + $new_students;
    $sub_old = $sub_old + $old_students;
    $sub_total = $sub_total + $total_students;

    $sub_daily_new = $sub_daily_new + $row2['daily_new'];
    $sub_daily_old = $sub_daily_old + $row2['daily_old'];
    $sub_daily_total = $sub_daily_total + $row2['daily_total'];

    $sub_reservations_new = $sub_reservations_new + $row2['reservations_new'];
    $sub_reservations_old = $sub_reservations_old + $row2['reservations_old'];
    $sub_reservations_total = $sub_reservations_total + $row2['reservations_total'];

$pdf->Ln(5);
$pdf ->Rect(5,$height,25,5);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(24, 0, ($row['grade_level_id'] == 14 || $row['grade_level_id'] == 15) ? $row['strand_name'] : $row['grade_level'], 0, 0,'C'); 

$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(30,$height,12,5);//box
$pdf->Cell(12, 0, $old_students_past, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(42,$height,12,5);//box
$pdf->Cell(12, 0,  $new_students_past, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(54,$height,12,5,true);//box
$pdf ->Rect(54,$height,12,5);//box
$pdf->Cell(12, 0, $total_students_past, 0, 0,'C'); 

if ($row['grade_level_id'] <> 14 && $row['grade_level_id'] <> 15) {

    $sub_target_new = $sub_target_new + $row3['target_new'];
    $sub_target_old = $sub_target_old + $row3['target_old'];
    $sub_target_total = $sub_target_total + $row3['target_total'];

    $pdf ->Rect(66,$height,12,5);//box
    $pdf->Cell(12, 0, $row3['target_old'], 0, 0,'C'); 
    $pdf->SetFont('Arial', '', 9);
    $pdf ->Rect(78,$height,12,5);//box
    $pdf->Cell(12, 0, $row3['target_new'], 0, 0,'C'); 
    $pdf->SetFont('Arial', '', 9);
    $pdf ->Rect(90,$height,12,5,true);//box
    $pdf ->Rect(90,$height,12,5);//box
    $pdf->Cell(12, 0, $row3['target_total'], 0, 0,'C');
    

} else {
    
    $sub_target_new = $row3['target_new'];
    $sub_target_old = $row3['target_old'];
    $sub_target_total = $row3['target_total'];

    $pdf ->Rect(66,$height,12,5);//box
    $pdf->Cell(12, 0, '', 0, 0,'C'); 
    $pdf->SetFont('Arial', '', 9);
    $pdf ->Rect(78,$height,12,5);//box
    $pdf->Cell(12, 0, '', 0, 0,'C'); 
    $pdf->SetFont('Arial', '', 9);
    $pdf ->Rect(90,$height,12,5,true);//box
    $pdf ->Rect(90,$height,12,5);//box
    $pdf->Cell(12, 0, '', 0, 0,'C');

}



$pdf ->Rect(102,$height,12,5);//box
$pdf->Cell(12, 0, $row2['daily_old'], 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(114,$height,12,5);//box
$pdf->Cell(12, 0, $row2['daily_new'], 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(126,$height,12,5,true);//box
$pdf ->Rect(126,$height,12,5);//box
$pdf->Cell(12, 0, $row2['daily_total'], 0, 0,'C'); 

$pdf ->Rect(138,$height,12,5);//box
$pdf->Cell(12, 0, $old_students, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(150,$height,12,5);//box
$pdf->Cell(12, 0, $new_students, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(162,$height,12,5,true);//box
$pdf ->Rect(162,$height,12,5);//box
$pdf->Cell(12, 0,$total_students, 0, 0,'C'); 

$pdf ->Rect(174,$height,12,5);//box
$pdf->Cell(12, 0, $row2['reservations_old'], 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(186,$height,12,5);//box
$pdf->Cell(12, 0, $row2['reservations_new'], 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(198,$height,12,5,true);//box
$pdf ->Rect(198,$height,12,5);//box
$pdf->Cell(12, 0, $row2['reservations_total'], 0, 1,'C'); 

$height = $height + 5;

}

$pdf->Ln(5);
$pdf->SetTextColor(0,0,0); // color text
$pdf->SetFillColor(255, 255, 0); // COLOR PER BOX gray
$pdf ->Rect(5,$height,25,5,true);//box
$pdf ->Rect(5,$height,25,5);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(24, 0, 'Sub-total', 0, 0,'C'); 

$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(30,$height,12,5,true);//box
$pdf ->Rect(30,$height,12,5);//box
$pdf->Cell(12, 0, $sub_old_past, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(42,$height,12,5,true);//box
$pdf ->Rect(42,$height,12,5);//box
$pdf->Cell(12, 0, $sub_new_past, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(54,$height,12,5,true);//box
$pdf ->Rect(54,$height,12,5);//box
$pdf->Cell(12, 0, $sub_total_past, 0, 0,'C'); 

if ($index == 'Grade 11' || $index == 'Grade 12') {

    $pdf ->Rect(66,$height,12,5,true);//box
    $pdf ->Rect(66,$height,12,5);//box
    $pdf->Cell(12, 0, $sub_target_old, 0, 0,'C'); 
    $pdf->SetFont('Arial', '', 9);
    $pdf ->Rect(78,$height,12,5,true);//box
    $pdf ->Rect(78,$height,12,5);//box
    $pdf->Cell(12, 0, $sub_target_new, 0, 0,'C'); 
    $pdf->SetFont('Arial', '', 9);
    $pdf ->Rect(90,$height,12,5,true);//box
    $pdf ->Rect(90,$height,12,5);//box
    $pdf->Cell(12, 0, $sub_target_total, 0, 0,'C');

} else {

$pdf ->Rect(66,$height,12,5,true);//box
$pdf ->Rect(66,$height,12,5);//box
$pdf->Cell(12, 0, $sub_target_old, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(78,$height,12,5,true);//box
$pdf ->Rect(78,$height,12,5);//box
$pdf->Cell(12, 0, $sub_target_new, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(90,$height,12,5,true);//box
$pdf ->Rect(90,$height,12,5);//box
$pdf->Cell(12, 0, $sub_target_total, 0, 0,'C');

}

 

$pdf ->Rect(102,$height,12,5,true);//box
$pdf ->Rect(102,$height,12,5);//box
$pdf->Cell(12, 0, $sub_daily_old, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(114,$height,12,5,true);//box
$pdf ->Rect(114,$height,12,5);//box
$pdf->Cell(12, 0, $sub_daily_new, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(126,$height,12,5,true);//box
$pdf ->Rect(126,$height,12,5);//box
$pdf->Cell(12, 0, $sub_daily_total, 0, 0,'C'); 

$pdf ->Rect(138,$height,12,5,true);//box
$pdf ->Rect(138,$height,12,5);//box
$pdf->Cell(12, 0, $sub_old, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(150,$height,12,5,true);//box
$pdf ->Rect(150,$height,12,5);//box
$pdf->Cell(12, 0, $sub_new, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(162,$height,12,5,true);//box
$pdf ->Rect(162,$height,12,5);//box
$pdf->Cell(12, 0, $sub_total, 0, 0,'C'); 

$pdf ->Rect(174,$height,12,5,true);//box
$pdf ->Rect(174,$height,12,5);//box
$pdf->Cell(12, 0, $sub_reservations_old, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(186,$height,12,5,true);//box
$pdf ->Rect(186,$height,12,5);//box
$pdf->Cell(12, 0, $sub_reservations_new, 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(198,$height,12,5,true);//box
$pdf ->Rect(198,$height,12,5);//box
$pdf->Cell(12, 0, $sub_reservations_total, 0, 1,'C'); 

$height = $height + 5;
$pdf->Ln(5);

}

$pdf->SetTextColor(255, 255, 255); // color text
$pdf->SetFillColor(255, 0, 0); // COLOR PER BOX gray
$pdf ->Rect(5,$height,25,5,true);//box
$pdf ->Rect(5,$height,25,5);//box
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(24, 0, 'TOTAL', 0, 0,'C'); 

$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(30,$height,12,5,true);//box
$pdf ->Rect(30,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(42,$height,12,5,true);//box
$pdf ->Rect(42,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(54,$height,12,5,true);//box
$pdf ->Rect(54,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 

$pdf ->Rect(66,$height,12,5,true);//box
$pdf ->Rect(66,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(78,$height,12,5,true);//box
$pdf ->Rect(78,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(90,$height,12,5,true);//box
$pdf ->Rect(90,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 

$pdf ->Rect(102,$height,12,5,true);//box
$pdf ->Rect(102,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(114,$height,12,5,true);//box
$pdf ->Rect(114,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(126,$height,12,5,true);//box
$pdf ->Rect(126,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 

$pdf ->Rect(138,$height,12,5,true);//box
$pdf ->Rect(138,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(150,$height,12,5,true);//box
$pdf ->Rect(150,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(162,$height,12,5,true);//box
$pdf ->Rect(162,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 

$pdf ->Rect(174,$height,12,5,true);//box
$pdf ->Rect(174,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(186,$height,12,5,true);//box
$pdf ->Rect(186,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 0,'C'); 
$pdf->SetFont('Arial', '', 9);
$pdf ->Rect(198,$height,12,5,true);//box
$pdf ->Rect(198,$height,12,5);//box
$pdf->Cell(12, 0, '', 0, 1,'C');


$pdf->Ln(8);
$pdf->SetTextColor(0,0,0); // color text for Grade level

$pdf->Cell(6,10,'',0,0); // space lang
$pdf->Cell(6, 10, 'Prepared by:', 0, 0,'C'); 

$pdf->Cell(25,10,'',0,0); // space lang
$pdf->Cell(25, 10, 'dasdjanidauihdauydababdjab', 0, 0,'C'); // data for name

$pdf->Cell(55,10,'',0,0); // space lang
$pdf->Cell(55, 10, 'Noted by:', 0, 0,'C'); // data for name

$pdf->Cell(1,10,'',0,0); // space lang
$pdf->Cell(1, 10, 'awdajwdaiwdhawdawd', 0, 1,'C'); // data for name

$pdf->Ln(1);
$pdf->Cell(25,0,'',0,0); // space lang
$pdf->Cell(40, 0, 'AH-Bacoor Campus', 0, 0,'C');

$pdf->Cell(70,0,'',0,0); // space lang
$pdf->Cell(70, 0, 'OIC - School Principal', 0, 0,'C');



$pdf->Output();
