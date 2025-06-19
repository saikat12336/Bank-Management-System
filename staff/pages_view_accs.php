<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$staff_id = $_SESSION['staff_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View iBank Account Types</title>
    <?php include("dist/_partials/head.php"); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <?php include("dist/_partials/nav.php"); ?>

    <!-- Main Sidebar Container -->
    <?php include("dist/_partials/sidebar.php"); ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>iBank Account Types</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">View Account Types</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="card card-purple shadow">
                    <div class="card-header">
                        <h3 class="card-title">All Account Type Details</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                       <table class="table table-hover table-bordered">
    <thead class="bg-primary text-white">
        <tr>
            <th>#</th>
            <th>Account Name</th>
            <th style="width: 35%;">Description</th>
            <th>Rate (%/Year)</th>
            <th>Account Code</th>
        </tr>
    </thead>
    <tbody class="bg-light">
        <?php
        $ret = "SELECT * FROM iB_Acc_types";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute();
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {
        ?>
        <tr>
            <td><?php echo $cnt++; ?></td>
            <td><?php echo htmlspecialchars($row->name); ?></td>
            <td style="white-space: normal;"><?php echo html_entity_decode($row->description); ?></td>
            <td><?php echo htmlspecialchars($row->rate); ?>%</td>
            <td><?php echo htmlspecialchars($row->code); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include("dist/_partials/footer.php"); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark"></aside>

</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
