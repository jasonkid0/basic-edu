<?php
require '../../../includes/conn.php';
session_start();

if (isset($_POST['submit'])) {

    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $grade_level = array();

    if (isset($_POST['grade_level'])) {
        $temp_array = $_POST['grade_level'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($grade_level, $index);
            } else {
                array_push($grade_level, 0);
            }
        }
    }

    // $target_new = array();

    // if (isset($_POST['target_new'])) {
    //     $temp_array = $_POST['target_new'];

    //     foreach ($temp_array as $index) {
    //         if ($index != null) {
    //             array_push($target_new, $index);
    //         } else {
    //             array_push($target_new, 0);
    //         }
    //     }
    // }

    $daily_new = array();

    if (isset($_POST['daily_new'])) {
        $temp_array = $_POST['daily_new'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($daily_new, $index);
            } else {
                array_push($daily_new, 0);
            }
        }
    }

    $daily_old = array();

    if (isset($_POST['daily_old'])) {
        $temp_array = $_POST['daily_old'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($daily_old, $index);
            } else {
                array_push($daily_old, 0);
            }
        }
    }

    $reservations_new = array();

    if (isset($_POST['reservations_new'])) {
        $temp_array = $_POST['reservations_new'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($reservations_new, $index);
            } else {
                array_push($reservations_new, 0);
            }
        }
    }

    $reservations_old = array();

    if (isset($_POST['reservations_old'])) {
        $temp_array = $_POST['reservations_old'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($reservations_old, $index);
            } else {
                array_push($reservations_old, 0);
            }
        }
    }

    $strand = array();

    if (isset($_POST['strand'])) {
        $temp_array = $_POST['strand'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($strand, $index);
            } else {
                array_push($strand, 0);
            }
        }
    }

    $i = 0;
    $j = 0;
    $h = 0;


    $date_select = mysqli_query($conn, "SELECT COUNT(bd_id) as id FROM tbl_breakdown WHERE date = '$date'");
    $row = mysqli_fetch_array($date_select);

    if ($row['id'] > 0) {

        $_SESSION['date_exists'] = true;
        header("location: ../add.enrollment.breakdown.php");

    } else {

        foreach ($grade_level as $index) {

            if ($index == '14' ) {

                $bd = mysqli_query($conn, "INSERT INTO tbl_breakdown (grade_level, strand_id, daily_new, daily_old, reservations_new, reservations_old, date) VALUE ('$index', '$strand[$j]', '$daily_new[$i]', '$daily_old[$i]', '$reservations_new[$i]', '$reservations_old[$i]', '$date')");

                $j++;

            } elseif($index == '15') {

                $bd = mysqli_query($conn, "INSERT INTO tbl_breakdown (grade_level, strand_id, daily_new, daily_old, reservations_new, reservations_old, date) VALUE ('$index', '$strand[$h]', '$daily_new[$i]', '$daily_old[$i]', '$reservations_new[$i]', '$reservations_old[$i]', '$date')");

                $h++;

            } else {

                $bd = mysqli_query($conn, "INSERT INTO tbl_breakdown (grade_level, daily_new, daily_old, reservations_new, reservations_old, date) VALUE ('$index', '$daily_new[$i]', '$daily_old[$i]', '$reservations_new[$i]', '$reservations_old[$i]', '$date')");
            }

            $i++;
        }

        $_SESSION['success'] = true;
        header("location: ../add.enrollment.breakdown.php");

    }



}


?>