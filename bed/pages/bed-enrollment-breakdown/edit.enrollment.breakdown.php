<?php
require '../../includes/conn.php';
session_start();
ob_start();


require '../../includes/bed-session.php';

$date = $_GET['date'];
?>


<!DOCTYPE html>
<html lang="en">

<!-- Head and links -->

<head>
    <title>Enrollement Breakdown | SFAC Bacoor</title>
    <?php include '../../includes/bed-head.php'; ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link disabled text-light">Enrollement Breakdown</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link disabled text-light">Basic Education</a>
                </li>
            </ul>
            <?php include '../../includes/bed-navbar.php'; ?>

            <!-- sidebar menu -->
            <?php include '../../includes/bed-sidebar.php'; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper pt-4">


                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid pl-5 pr-5 pb-3">
                        <div class="card card-info shadow-lg">
                            <div class="card-header">
                                <h3 class="card-title">Enrollement Breakdown Settings</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="userData/ctrl.edit.enrollment.breakdown.php" enctype="multipart/form-data" method="POST">
                                <div class="card-body">
                                    <div class="row mb-4 mt-3">
                                        <div class="input-group col-sm-6 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                            </div>
                                            <input type="date" class="form-control" value="<?php echo $date?>"
                                                placeholder="Date" disabled>
                                            <input type="date" class="form-control" name="date" value="<?php echo $date?>"
                                                placeholder="Date" hidden>
                                        </div>
                                    </div>

                                    <?php
                                     $grade_level = mysqli_query($conn, "SELECT * FROM tbl_breakdown LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_breakdown.grade_level WHERE date = '$date' GROUP BY grade_level_id");
                                     while ($row = mysqli_fetch_array($grade_level)) {
                                        if ($row['grade_level_id'] <> 14 && $row['grade_level_id'] <> 15) {
                                    ?>
                                    <div class="row mb-4">
                                        <div class="input-group col-sm-4 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="text" class="form-control" value="<?php echo $row['grade_level']?>"
                                                placeholder="Target" disabled>
                                            <input type="text" class="form-control" name="grade_level[]" value="<?php echo $row['grade_level_id']?>"
                                                placeholder="Target" hidden>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="input-group col-sm-6 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Daily Enrollees (New)</span>
                                            </div>
                                            <input type="text" class="form-control" name="daily_new[]" value="<?php echo $row['daily_new']?>">
                                        </div>
                                        <div class="input-group col-sm-6 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Daily Enrollees (Old)</span>
                                            </div>
                                            <input type="text" class="form-control" name="daily_old[]" value="<?php echo $row['daily_old']?>">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="input-group col-sm-6 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Reservations (New)</span>
                                            </div>
                                            <input type="text" class="form-control" name="reservations_new[]" value="<?php echo $row['reservations_new']?>"
                                                placeholder="Reservations">
                                        </div>
                                        <div class="input-group col-sm-6 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Reservations (Old)</span>
                                            </div>
                                            <input type="text" class="form-control" name="reservations_old[]" value="<?php echo $row['reservations_old']?>"
                                                placeholder="Reservations">
                                        </div>
                                    </div>
                                    <hr>
                                    <?php
                                        } else {
                                            $strand_info =  mysqli_query($conn, "SELECT * FROM tbl_strands LEFT JOIN tbl_breakdown ON tbl_breakdown.strand_id = tbl_strands.strand_id WHERE date = '$date' AND grade_level = '$row[grade_level_id]'");
                                            ?>
                                            <div class="row mb-4">
                                                <div class="input-group col-sm-4 mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" value="<?php echo $row['grade_level']?>"
                                                        placeholder="Target" disabled>
                                                    
                                                </div>
                                            </div>
                                            <?php
                                            $i = 0;
                                            while ($row2 = mysqli_fetch_array($strand_info)) {
                                        
                                            ?>
                                            <div class="row mb-4">
                                                <div class="input-group col-sm-4 mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" value="<?php echo $row2['strand_name'];?>"
                                                        placeholder="Target" disabled>
                                                        <input type="text" class="form-control" value="<?php echo $row2['strand_id'];?>"
                                                        placeholder="Target" hidden>
                                                        <input type="text" class="form-control" name="grade_level[]" value="<?php echo $row['grade_level_id']?>"
                                                        placeholder="Target" hidden>
                                                </div>
                                                <div class="input-group col-sm-4 mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Daily Enrollees (Old)</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="daily_old[]" value="<?php echo $row2['daily_old']?>"
                                                        placeholder="Daily">
                                                </div>
                                                <div class="input-group col-sm-4 mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Daily Enrollees (New)</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="daily_new[]" value="<?php echo $row2['daily_new']?>"
                                                        placeholder="Daily">
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="input-group col-sm-4 mb-2">
                                                </div>
                                                <div class="input-group col-sm-4 mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Reservations (Old)</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="reservations_old[]" value="<?php echo $row2['reservations_old']?>"
                                                        placeholder="Reservations">
                                                </div>
                                                <div class="input-group col-sm-4 mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Reservations (New)</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="reservations_new[]" value="<?php echo $row2['reservations_new']?>"
                                                        placeholder="Reservations">
                                                </div>
                                            </div>
                                            <?php
                                                
                                        }
                                        }
                                     }
                                    ?>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" name="submit" class="btn btn-info"><i
                                            class="fa fa-user-plus"></i> Update</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->

                    </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->


            <!-- Footer and script -->
            <?php include '../../includes/bed-footer.php';  ?>



</body>

</html>