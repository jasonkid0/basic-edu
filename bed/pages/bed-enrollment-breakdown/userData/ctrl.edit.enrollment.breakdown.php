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

    print_r($grade_level);

    $i = 0;
    $j = 0;

        foreach ($grade_level as $grade_id) {

            if ($grade_id <> 14 && $grade_id <> 15) {

                $bd = mysqli_query($conn, "UPDATE tbl_breakdown SET grade_level = '$grade_id', daily_new = '$daily_new[$i]', daily_old = '$daily_old[$i]', reservations_new = '$reservations_new[$i]', reservations_old = '$reservations_old[$i]' WHERE date = '$date' AND grade_level = '$grade_id'");

            } else {

                $bd = mysqli_query($conn, "UPDATE tbl_breakdown SET grade_level = '$grade_id', daily_new = '$daily_new[$i]', daily_old = '$daily_old[$i]', reservations_new = '$reservations_new[$i]', reservations_old = '$reservations_old[$i]' WHERE date = '$date' AND grade_level = '$grade_id' AND strand_id = '$strand[$j]'");
                $j ++;

            }

            $i++;
        }

        $_SESSION['success'] = true;
        header("location: ../edit.enrollment.breakdown.php?date=". $date);

    



}


?>