<?php
require('../bed-fpdf/fpdf.php');
require('../../includes/conn.php');

if(isset($_GET['semester'])) {
    $sem = $_GET['semester'];
    $acad = $_GET['acadyear'];
    $grade_level = $_GET['grade_level'];
    $student_type = $_GET['student_type'];
    
} else {
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
$grade_level = "All";
    $student_type = "All";
}


class PDF extends FPDF
{
    
    
    function Header()
    {   
        require('../../includes/conn.php');
        if(isset($_GET['semester'])) {
    $sem = $_GET['semester'];
    $acad = $_GET['acadyear'];
    $grade_level = $_GET['grade_level'];
    $student_type = $_GET['student_type'];
    
} else {
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
$grade_level = "All";
    $student_type = "All";
}

        // Logo(x axis, y axis, height, width)
        $this->Image('../../../assets/img/logo.png',27,3,19,19);
        // font(font type,style,font size)
        $this->SetFont('Times','B',28);
        $this->SetTextColor(255,0,0);
        // Dummy cell
        $this->Cell(50);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(110,5,'Saint Francis of Assisi College',0,0,'C');
        // Line break
        $this->Ln(9);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',10);
        // dummy cell
        
        // //cell(width,height,text,border,end line,[align])
        $test = utf8_decode("Piñas");
        $this->Cell(0,3,'96 Bayanan, City of Bacoor',0,0,'C');
        // Line break
        $this->Ln(4);
        $this->SetFont('Arial','B',12);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(0,4,'BASIC EDUCATION DEPARTMENT',0,0,'C');
        // Line break
        $this->Ln(8);
        $this->SetFont('Arial','B',14);
        // //cell(width,height,text,border,end line,[align])
        $this->Cell(0,6,'Master List',0,1,'C');
        $this->SetFont('Arial','B',10);
        $this->Cell(0,4,$sem.' '.$acad,0,1,'C');
        $this->Ln(5);
    
    }


    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-25);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(255,0,0);
        // Page number
        $this->Cell(0,5,'',0,1,'C');
        $this->SetFont('Arial','',8);
        $this->SetTextColor(0,0,0);
        $this->Cell(0,5,'',0,0,'R');
    }


}

$pdf = new PDF('P','mm','Legal');
//left top right
$pdf->SetMargins(10,10,10);

if ($grade_level == "All") {
    $dept = mysqli_query($conn, "SELECT * FROM tbl_departments") or die (mysqli_error($conn));
} else {
    $dept = mysqli_query($conn, "SELECT * FROM tbl_departments WHERE department_id = '$grade_level'") or die (mysqli_error($conn));
}
while ($row = mysqli_fetch_array($dept)) {
    
    if ($row['department_id'] != "4") {
    
        if ($student_type == "All") {
            $liststudents = mysqli_query($conn, "SELECT *, CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS fullname 
            FROM tbl_schoolyears AS sy
            LEFT JOIN tbl_students AS stud ON stud.student_id = sy.student_id
            LEFT JOIN tbl_strands AS stds ON stds.strand_id = sy.strand_id
            LEFT JOIN tbl_semesters AS sem ON sem.semester_id = sy.semester_id
            LEFT JOIN tbl_grade_levels AS gl ON gl.grade_level_id =sy.grade_level_id
            LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = sy.ay_id  
            LEFT JOIN tbl_departments AS dep ON dep.department_id = gl.department_id
            WHERE remark = 'Approved'
            AND ay.academic_year = '$acad' 
            AND (sem.semester = '$sem' OR sy.semester_id = '0')
            AND dep.department_id = '$row[department_id]'  ORDER BY gl.grade_level_id, student_lname") or die(mysqli_error($conn));
        } else {
            $liststudents = mysqli_query($conn, "SELECT *, CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS fullname 
            FROM tbl_schoolyears AS sy
            LEFT JOIN tbl_students AS stud ON stud.student_id = sy.student_id
            LEFT JOIN tbl_strands AS stds ON stds.strand_id = sy.strand_id
            LEFT JOIN tbl_semesters AS sem ON sem.semester_id = sy.semester_id
            LEFT JOIN tbl_grade_levels AS gl ON gl.grade_level_id =sy.grade_level_id
            LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = sy.ay_id  
            LEFT JOIN tbl_departments AS dep ON dep.department_id = gl.department_id
            WHERE remark = 'Approved'
            AND ay.academic_year = '$acad' 
            AND (sem.semester = '$sem' OR sy.semester_id = '0')
            AND dep.department_id = '$row[department_id]'
            AND sy.stud_type = '$student_type' ORDER BY gl.grade_level_id, student_lname") or die(mysqli_error($conn));
        }
    
    
                                                
    if (empty(mysqli_num_rows($liststudents))) {

    continue;

    } else {
    
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,$row['department_name'],0,1);
    $pdf->Cell(0,3,'',0,1);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(85,5,'Name',1,0);
    $pdf->Cell(61,5,'LSA',1,0);
    $pdf->Cell(50,5,'Grade Level',1,1);
    $pdf->SetFont('Arial','',9);
    $x = 1;
    
    while ($row3= mysqli_fetch_array ($liststudents)) {   
    $pdf->Cell(6,5,$x,0,0);

    $fontsize = 9;
    $tempFontSize = $fontsize;
    $cellwidth = 74;
    $fullname = utf8_decode($row3['fullname']);

    while ($pdf->GetStringWidth($fullname) > $cellwidth){
        $pdf->SetFontSize($tempFontSize -= 0.1);
    }
    $pdf->Cell(79,5,strtoupper($fullname),0,0);
    $pdf->SetFont('Arial','',9);

    $fontsize = 9;
    $tempFontSize = $fontsize;
    $cellwidth = 56;
    $lastschool = utf8_decode($row3['last_sch']);

    while ($pdf->GetStringWidth($lastschool) > $cellwidth){
        $pdf->SetFontSize($tempFontSize -= 0.1);
    }
    $pdf->Cell(61,5,utf8_decode($lastschool),0,0);
    $pdf->SetFont('Arial','',9);
    
    $pdf->Cell(50,5,$row3['grade_level'],0,1);
    
    $x++;

    }
    
    }
    
    } else {
    
        if ($student_type == "All") {
            $liststudents = mysqli_query($conn, "SELECT *, CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS fullname 
            FROM tbl_schoolyears AS sy
            LEFT JOIN tbl_students AS stud ON stud.student_id = sy.student_id
            LEFT JOIN tbl_strands AS stds ON stds.strand_id = sy.strand_id
            LEFT JOIN tbl_semesters AS sem ON sem.semester_id = sy.semester_id
            LEFT JOIN tbl_grade_levels AS gl ON gl.grade_level_id =sy.grade_level_id
            LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = sy.ay_id  
            LEFT JOIN tbl_departments AS dep ON dep.department_id = gl.department_id
            WHERE remark = 'Approved'
            AND ay.academic_year = '$acad' 
            AND (sem.semester = '$sem' OR sy.semester_id = '0')
            AND dep.department_id = '$row[department_id]'
            AND gl.grade_level_id = '14' ORDER BY gl.grade_level_id, stds.strand_name, student_lname") or die(mysqli_error($conn));
        } else {
            $liststudents = mysqli_query($conn, "SELECT *, CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS fullname 
            FROM tbl_schoolyears AS sy
            LEFT JOIN tbl_students AS stud ON stud.student_id = sy.student_id
            LEFT JOIN tbl_strands AS stds ON stds.strand_id = sy.strand_id
            LEFT JOIN tbl_semesters AS sem ON sem.semester_id = sy.semester_id
            LEFT JOIN tbl_grade_levels AS gl ON gl.grade_level_id =sy.grade_level_id
            LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = sy.ay_id  
            LEFT JOIN tbl_departments AS dep ON dep.department_id = gl.department_id
            WHERE remark = 'Approved'
            AND ay.academic_year = '$acad' 
            AND (sem.semester = '$sem' OR sy.semester_id = '0')
            AND dep.department_id = '$row[department_id]'
            AND gl.grade_level_id = '14'
            AND sy.stud_type = '$student_type' ORDER BY gl.grade_level_id, stds.strand_name, student_lname") or die(mysqli_error($conn));
        }
    
    
                                                
    if (empty(mysqli_num_rows($liststudents))) {

    continue;

    } else {
    
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,$row['department_name'].' (Grade 11)',0,1);
    $pdf->Cell(0,3,'',0,1);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(85,5,'Name',1,0);
    $pdf->Cell(61,5,'LSA',1,0);
    $pdf->Cell(50,5,'Grade Level',1,1);
    $pdf->SetFont('Arial','',9);
    $x = 1;
    
    while ($row3= mysqli_fetch_array ($liststudents)) {   
    $pdf->Cell(6,5,$x,0,0);

    $fontsize = 9;
    $tempFontSize = $fontsize;
    $cellwidth = 74;
    $fullname = utf8_decode($row3['fullname']);

    while ($pdf->GetStringWidth($fullname) > $cellwidth){
        $pdf->SetFontSize($tempFontSize -= 0.1);
    }
    $pdf->Cell(79,5,strtoupper($fullname),0,0);
    $pdf->SetFont('Arial','',9);

    $fontsize = 9;
    $tempFontSize = $fontsize;
    $cellwidth = 56;
    $lastschool = utf8_decode($row3['last_sch']);

    while ($pdf->GetStringWidth($lastschool) > $cellwidth){
        $pdf->SetFontSize($tempFontSize -= 0.1);
    }
    $pdf->Cell(61,5,utf8_decode($lastschool),0,0);
    $pdf->SetFont('Arial','',9);
    
    $pdf->Cell(50,5,$row3['grade_level'].' - '.$row3['strand_name'],0,1);
     
    $x++;

    }
    
    }
    
    if ($student_type == "All") {
        $liststudents = mysqli_query($conn, "SELECT *, CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS fullname 
        FROM tbl_schoolyears AS sy
        LEFT JOIN tbl_students AS stud ON stud.student_id = sy.student_id
        LEFT JOIN tbl_strands AS stds ON stds.strand_id = sy.strand_id
        LEFT JOIN tbl_semesters AS sem ON sem.semester_id = sy.semester_id
        LEFT JOIN tbl_grade_levels AS gl ON gl.grade_level_id =sy.grade_level_id
        LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = sy.ay_id  
        LEFT JOIN tbl_departments AS dep ON dep.department_id = gl.department_id
        WHERE remark = 'Approved'
        AND ay.academic_year = '$acad' 
        AND (sem.semester = '$sem' OR sy.semester_id = '0')
        AND dep.department_id = '$row[department_id]'
        AND gl.grade_level_id = '15' ORDER BY gl.grade_level_id, stds.strand_name, student_lname") or die(mysqli_error($conn));
    } else {
        $liststudents = mysqli_query($conn, "SELECT *, CONCAT(stud.student_lname, ', ', stud.student_fname, ' ', stud.student_mname) AS fullname 
        FROM tbl_schoolyears AS sy
        LEFT JOIN tbl_students AS stud ON stud.student_id = sy.student_id
        LEFT JOIN tbl_strands AS stds ON stds.strand_id = sy.strand_id
        LEFT JOIN tbl_semesters AS sem ON sem.semester_id = sy.semester_id
        LEFT JOIN tbl_grade_levels AS gl ON gl.grade_level_id =sy.grade_level_id
        LEFT JOIN tbl_acadyears AS ay ON ay.ay_id = sy.ay_id  
        LEFT JOIN tbl_departments AS dep ON dep.department_id = gl.department_id
        WHERE remark = 'Approved'
        AND ay.academic_year = '$acad' 
        AND (sem.semester = '$sem' OR sy.semester_id = '0')
        AND dep.department_id = '$row[department_id]'
        AND gl.grade_level_id = '15'
        AND sy.stud_type = '$student_type' ORDER BY gl.grade_level_id, stds.strand_name, student_lname") or die(mysqli_error($conn));
    }
    
    
                                                
    if (empty(mysqli_num_rows($liststudents))) {

    continue;

    } else {
    
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,5,$row['department_name'] .' (Grade 12)',0,1);
    $pdf->Cell(0,3,'',0,1);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(85,5,'Name',1,0);
    $pdf->Cell(61,5,'LSA',1,0);
    $pdf->Cell(50,5,'Grade Level',1,1);
    $pdf->SetFont('Arial','',9);
    $x = 1;
    
    while ($row3= mysqli_fetch_array ($liststudents)) {   
    $pdf->Cell(6,5,$x,0,0);

    $fontsize = 9;
    $tempFontSize = $fontsize;
    $cellwidth = 74;
    $fullname = utf8_decode($row3['fullname']);

    while ($pdf->GetStringWidth($fullname) > $cellwidth){
        $pdf->SetFontSize($tempFontSize -= 0.1);
    }
    $pdf->Cell(79,5,strtoupper($fullname),0,0);
    $pdf->SetFont('Arial','',9);

    $fontsize = 9;
    $tempFontSize = $fontsize;
    $cellwidth = 56;
    $lastschool = utf8_decode($row3['last_sch']);

    while ($pdf->GetStringWidth($lastschool) > $cellwidth){
        $pdf->SetFontSize($tempFontSize -= 0.1);
    }
    $pdf->Cell(61,5,utf8_decode($lastschool),0,0);
    $pdf->SetFont('Arial','',9);
    
    $pdf->Cell(50,5,$row3['grade_level'].' - '.$row3['strand_name'],0,1);
     
    $x++;

    }
    
    }
    
    
    }
    
}

$pdf->Output();