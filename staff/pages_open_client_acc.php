<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$astaff_id = $_SESSION['staff_id'];

// Register new account
if (isset($_POST['open_account'])) {
    // Sanitize and validate inputs
    function clean_input($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    $acc_name = clean_input($_POST['acc_name']);
    $account_number = clean_input($_POST['account_number']);
    $acc_type = clean_input($_POST['acc_type']);
    $acc_rates = clean_input($_POST['acc_rates']);
    $acc_status = 'Pending';
    $acc_amount = clean_input($_POST['acc_amount']);
    $client_id = intval($_GET['client_id']);
    $client_national_id = clean_input($_POST['client_national_id']);
    $client_name = clean_input($_POST['client_name']);
    $client_phone = clean_input($_POST['client_phone']);
    $client_number = clean_input($_POST['client_number']);
    $client_email = filter_var($_POST['client_email'], FILTER_SANITIZE_EMAIL);
    $client_adr = clean_input($_POST['client_adr']);
    $client_father_name = clean_input($_POST['client_father_name']);
    $client_dob = $_POST['client_dob'];
    $client_gender = clean_input($_POST['client_gender']);
    $client_marital_status = clean_input($_POST['client_marital_status']);
    $client_pan = strtoupper(clean_input($_POST['client_pan']));
    $client_aadhaar = clean_input($_POST['client_aadhaar']);

    // Additional Validation
    if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $err = "Invalid email format!";
    } elseif (!preg_match("/^[0-9]{12}$/", $client_aadhaar)) {
        $err = "Aadhaar number must be 12 digits!";
    } elseif (!preg_match("/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/", $client_pan)) {
        $err = "Invalid PAN number format!";
    } else {
        // Check for duplicate account number
        $check = $mysqli->prepare("SELECT account_number FROM iB_bankAccounts WHERE account_number = ?");
        $check->bind_param('s', $account_number);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $err = "Account Number already exists. Please try again.";
        } else {
            // Insert into database
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

            if ($stmt->execute()) {
                $success = "iBank Account Opened";
            } else {
                $err = "Please Try Again Or Try Later";
            }
        }
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