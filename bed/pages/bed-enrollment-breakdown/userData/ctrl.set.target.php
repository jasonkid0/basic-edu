<?php
require '../../../includes/conn.php';
session_start();

if (isset($_POST['submit'])) {


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

    $target_new = array();

    if (isset($_POST['target_new'])) {
        $temp_array = $_POST['target_new'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($target_new, $index);
            } else {
                array_push($target_new, 0);
            }
        }
    }

    $target_old = array();

    if (isset($_POST['target_old'])) {
        $temp_array = $_POST['target_old'];

        foreach ($temp_array as $index) {
            if ($index != null) {
                array_push($target_old, $index);
            } else {
                array_push($target_old, 0);
            }
        }
    }

    $i = 0;



        foreach ($grade_level as $index) {

                $bd = mysqli_query($conn, "UPDATE tbl_target SET grade_level = '$index', target_old = '$target_old[$i]', target_new = '$target_new[$i]' WHERE grade_level = '$index'");
            

            $i++;
        }

        $_SESSION['success'] = true;
        header("location: ../set.target.php");

    



}


?>