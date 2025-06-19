<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];
//register new account
if (isset($_POST['open_account'])) {
    //Client open account
    $acc_name = $_POST['acc_name'];
    $account_number = $_POST['account_number'];
    $acc_type = $_POST['acc_type'];
    $acc_rates = $_POST['acc_rates'];
    $acc_status = $_POST['acc_status'];
    $acc_amount = $_POST['acc_amount'];
    $client_id  = $_GET['client_id'];
    $client_national_id = $_POST['client_national_id'];
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $client_number = $_POST['client_number'];
    $client_email  = $_POST['client_email'];
    $client_adr  = $_POST['client_adr'];
$client_father_name = $_POST['client_father_name'];
$client_dob = $_POST['client_dob'];
$client_gender = $_POST['client_gender'];
$client_marital_status = $_POST['client_marital_status'];
$client_pan = $_POST['client_pan'];
$client_aadhaar = $_POST['client_aadhaar'];



    //Insert Captured information to a database table
$query = "INSERT INTO iB_bankAccounts (
    acc_name, account_number, acc_type, acc_rates, acc_status, acc_amount,
    client_id, client_name, client_national_id, client_phone, client_number,
    client_email, client_adr, client_father_name, client_dob, client_gender,
    client_marital_status, client_pan, client_aadhaar
) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $mysqli->prepare($query);
$stmt->bind_param(
    'sssssssssssssssssss',
    $acc_name, $account_number, $acc_type, $acc_rates, $acc_status, $acc_amount,
    $client_id, $client_name, $client_national_id, $client_phone, $client_number,
    $client_email, $client_adr, $client_father_name, $client_dob, $client_gender,
    $client_marital_status, $client_pan, $client_aadhaar
);

    $stmt->execute();

    //declare a varible which will be passed to alert function
    if ($stmt) {
        $success = "iBank Account Opened";
    } else {
        $err = "Please Try Again Or Try Later";
    }
}

?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("dist/_partials/nav.php"); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include("dist/_partials/sidebar.php"); ?>

        <!-- Content Wrapper. Contains page content -->
        <?php
        $client_id = $_GET['client_id'];
        $ret = "SELECT * FROM  iB_clients WHERE client_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $client_id);
        $stmt->execute(); //ok
        $res = $stmt->get_result();
        $cnt = 1;
        while ($row = $res->fetch_object()) {

        ?>
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Open <?php echo $row->name; ?> iBanking Account</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">iBanking Accounts</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">Open </a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="card card-purple">
                                    <div class="card-header">
                                        <h3 class="card-title">Fill All Fields</h3>
                                    </div>
                                    <!-- form start -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="Name">Client Name</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->name; ?>" required class="form-control" id="Name">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="Number">Client Number</label>
                                                    <input type="text" readonly name="client_number" value="<?php echo $row->client_number; ?>" class="form-control" id="Number">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="Phone">Client Phone Number</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->phone; ?>" required class="form-control" id="Phone">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="Nid">Client National ID No.</label>
                                                    <input type="text" readonly value="<?php echo $row->national_id; ?>" name="client_national_id" required class="form-control" id="Nid">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="Email">Client Email</label>
                                                    <input type="email" readonly name="client_email" value="<?php echo $row->email; ?>" required class="form-control" id="Email">
                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="Address">Client Address</label>
                                                    <input type="text" name="client_adr" readonly value="<?php echo $row->address; ?>" required class="form-control" id="Address">
                                                </div>


    <div class="col-md-6 form-group">
        <label for="client_father_name">Father's Name</label>
        <input type="text" name="client_father_name" class="form-control" required>
    </div>
    <div class="col-md-6 form-group">
        <label for="client_dob">Date of Birth</label>
        <input type="date" name="client_dob" class="form-control" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 form-group">
        <label for="client_gender">Gender</label><br>
        <input type="radio" name="client_gender" value="Male" required> Male
        <input type="radio" name="client_gender" value="Female"> Female
        <input type="radio" name="client_gender" value="Other"> Other
    </div>

    <div class="col-md-6 form-group">
        <label for="client_marital_status">Marital Status</label><br>
        <input type="radio" name="client_marital_status" value="Single" required> Single
        <input type="radio" name="client_marital_status" value="Married"> Married
    </div>
</div>

<div class="row">
    <div class="col-md-6 form-group">
        <label for="client_pan">PAN Number</label>
        <input type="text" name="client_pan" maxlength="10" class="form-control" required>
    </div>
    <div class="col-md-6 form-group">
        <label for="client_aadhaar">Aadhaar Number</label>
        <input type="text" name="client_aadhaar" maxlength="12" class="form-control" required>
    </div>


                                            </div>
                                            <!-- ./End Personal Details -->

                                            <!--Bank Account Details-->
                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="AccType">Account Type</label>
                                                    <select class="form-control" onChange="getiBankAccs(this.value);" name="acc_type">
                                                        <option>Select Any Account types</option>
                                                        <?php
                                                        //fetch all iB_Acc_types
                                                        $ret = "SELECT * FROM  iB_Acc_types ORDER BY RAND() ";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute(); //ok
                                                        $res = $stmt->get_result();
                                                        $cnt = 1;
                                                        while ($row = $res->fetch_object()) {

                                                        ?>
                                                            <option value="<?php echo $row->name; ?> "> <?php echo $row->name; ?> </option>
                                                        <?php } ?>
                                                    </select>

                                                </div>
                                                <div class=" col-md-6 form-group">
                                                    <label for="AccTypeRate">Account Type Rates (%)</label>
                                                    <input type="text" name="acc_rates" readonly required class="form-control" id="AccountRates">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="AccStatus">Account Status</label>
                                                    <input type="text" name="acc_status" value="Active" readonly required class="form-control">
                                                </div>

                                                <div class=" col-md-6 form-group" style="display:none">
                                                    <label for="AccAmount">Account Amount</label>
                                                    <input type="text" name="acc_amount" value="0" readonly required class="form-control">
                                                </div>

                                            </div><!-- Log on to codeastro.com for more projects! -->
                                            <div class="row">
                                                <div class=" col-md-6 form-group">
                                                    <label for="AccName">Account Name</label>
                                                    <input type="text" name="acc_name" required class="form-control" id="AccName">
                                                </div>

                                                <div class=" col-md-6 form-group">
                                                    <label for="AccNo">Account Number</label>
                                                    <?php
                                                    //PHP function to generate random account number
                                                    $length = 12;
                                                    $_accnumber =  substr(str_shuffle('0123456789'), 1, $length);
                                                    ?>
                                                    <input type="text" name="account_number" value="<?php echo $_accnumber; ?>" required class="form-control" id="AccNo">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" name="open_account" class="btn btn-success">Open iBanking Account</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div>
        <?php } ?>
        <!-- /.content-wrapper -->
        <?php include("dist/_partials/footer.php"); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>

</html>