<?php
require '../../includes/conn.php';
session_start();
ob_start();


require '../../includes/bed-session.php';
?>


<!DOCTYPE html>
<html lang="en">

<!-- Head and links -->

<head>
    <title>Set Target Enrollees | SFAC Bacoor</title>
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
                    <a href="#" class="nav-link disabled text-light">Set Target Enrollees </a>
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
                                <h3 class="card-title">Set Target Enrollees </h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="userData/ctrl.set.target.php" enctype="multipart/form-data" method="POST">
                                <div class="card-body">

                                    <?php
                                     $grade_level = mysqli_query($conn, "SELECT * FROM tbl_target LEFT JOIN tbl_grade_levels ON tbl_grade_levels.grade_level_id = tbl_target.grade_level ");
                                     while ($row = mysqli_fetch_array($grade_level)) {
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
                                                <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Target Enrollees (New)</span>
                                            </div>
                                            <input type="text" class="form-control" name="target_new[]" value="<?php echo $row['target_new']?>"
                                                placeholder="Target">
                                        </div>
                                        <div class="input-group col-sm-6 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="mx-1 fas fa-envelope"></i> Target Enrollees (Old)</span>
                                            </div>
                                            <input type="text" class="form-control" name="target_old[]" value="<?php echo $row['target_old']?>"
                                                placeholder="Target">
                                        </div>
                                    </div>
                                    <hr>
                                            <?php
                                     }
                                    ?>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" name="submit" class="btn btn-info"><i
                                            class="fa fa-user-plus"></i> Set</button>
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