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
    <title>Enrollment History | SFAC Bacoor</title>
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
                    <a href="#" class="nav-link disabled text-light">Enrollment History</a>
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
                                <h3 class="card-title">Enrollment History</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="../bed-forms/masterlist.php" enctype="multipart/form-data" method="GET">
                                <div class="card-body">


                                    <div class="row mb-4 mt-5 justify-content-center">
                                        <div class="input-group col-sm-4 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                            </div>
                                            <select class="form-control select2 select2-info custom-select"
                                                data-dropdown-css-class="select2-info"
                                                data-placeholder="Select Semester" name="semester" required>
                                                <option value="" disabled selected>Select Semester</option>
                                                <?php
                                                $query = mysqli_query($conn, "SELECT * from tbl_semesters");
                                                while ($row2 = mysqli_fetch_array($query)) {
                                                    echo '<option value="' . $row2['semester'] . '">' . $row2['semester'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>


                                        <div class="input-group col-sm-4 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-keyboard"></i></span>
                                            </div>
                                            <select class="form-control select2 select2-info custom-select"
                                                data-dropdown-css-class="select2-info"
                                                data-placeholder="Select Academic Year" name="acadyear" required>
                                                <option value="" disabled selected>Select Academic Year</option>
                                                <?php
                                                $query = mysqli_query($conn, "SELECT * from tbl_acadyears");
                                                while ($row2 = mysqli_fetch_array($query)) {
                                                    echo '<option value="' . $row2['academic_year'] . '">' . $row2['academic_year'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row mb-4 justify-content-center">
                                        <div class="input-group col-sm-4 mb-2">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                        <select class="form-control select2 select2-info custom-select"
                                                data-dropdown-css-class="select2-info"
                                                data-placeholder="Select Level" name="grade_level" required>
                                                <option value="" disabled selected>Select Level</option>
                                                <?php
                                                $query = mysqli_query($conn, "SELECT * from tbl_departments");
                                                while ($row2 = mysqli_fetch_array($query)) {
                                                    echo '<option value="' . $row2['department_id'] . '">' . $row2['department_name'] . '</option>';
                                                }
                                                ?>
                                                <option value="All">All</option>
                                            </select>
                                            </div>

                                        <div class="input-group col-sm-4 mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <select class="form-control select2 select2-info custom-select"
                                                data-dropdown-css-class="select2-info"
                                                data-placeholder="Select Student Type" name="student_type" required>
                                                <option value="" disabled selected>Select Student Type</option>
                                                <option value="All">All</option>
                                                <option value="New">New</option>
                                                <option value="Old">Old</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" name="submit" class="btn btn-info"> Generate Masterlist</button>
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