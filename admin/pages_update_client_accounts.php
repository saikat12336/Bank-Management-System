<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

// Update Account
if (isset($_POST['update_account'])) {
    $acc_name = $_POST['acc_name'];
    $account_number = $_POST['account_number'];
    $acc_type = $_POST['acc_type'];
    $acc_rates = $_POST['acc_rates'];
    $acc_status = !empty($_POST['acc_status']) ? $_POST['acc_status'] : "Approved"; 
    $acc_amount = !empty($_POST['acc_amount']) ? $_POST['acc_amount'] : 0; 
    $account_id  = $_GET['account_id'];
$client_father_name = $_POST['client_father_name'];
$client_dob = $_POST['client_dob'];
$client_gender = $_POST['client_gender'];
$client_marital_status = $_POST['client_marital_status'];
    $client_aadhaar = $_POST['client_aadhaar'];
    $client_pan = $_POST['client_pan'];

$query = "UPDATE iB_bankAccounts 
          SET acc_name=?, account_number=?, acc_type=?, acc_rates=?, 
               acc_amount=?, 
              client_aadhaar=?, client_pan=?, 
              client_father_name=?, client_dob=?, client_gender=?, client_marital_status=?
          WHERE account_id =?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param(
    'sssssssssssi', // 12 strings + 1 integer
    $acc_name, $account_number, $acc_type, $acc_rates,
 $acc_amount,
    $client_aadhaar, $client_pan,
    $client_father_name, $client_dob, $client_gender, $client_marital_status,
    $account_id
);

    $stmt->execute();

    if ($stmt) {
        $success = "iBank Account Updated Successfully";
    } else {
        $err = "Error! Try Again.";
    }
}
?>

<!DOCTYPE html>
<html>
<?php include("dist/_partials/head.php"); ?>

<style>
    /* Global Styling */
    body {
        background-color: #f4f7fc;
        font-family: 'Poppins', sans-serif;
    }

    /* 3D Card Effect */
    .card-3d {
        background: linear-gradient(145deg, #ffffff, #e6ebf5);
        color: #333;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.1), -4px -4px 10px rgba(255, 255, 255, 0.8);
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card-3d:hover {
        transform: translateY(-3px);
        box-shadow: 6px 6px 14px rgba(0, 0, 0, 0.15), -6px -6px 14px rgba(255, 255, 255, 0.9);
    }

    /* 3D Buttons */
    .btn-3d {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        font-weight: bold;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2), -3px -3px 6px rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease-in-out;
    }

    .btn-3d:hover {
        background: linear-gradient(135deg, #0056b3, #007bff);
        transform: translateY(-2px);
        box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.25), -5px -5px 10px rgba(255, 255, 255, 0.3);
    }

    /* Editable Input Fields */
    .form-control {
        background: #eef2ff;
        border: none;
        color: #333;
        padding: 10px;
        border-radius: 8px;
        box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1), inset -2px -2px 5px rgba(255, 255, 255, 0.7);
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        background: #e1e8ff;
        box-shadow: 0 0 5px #007bff;
        outline: none;
    }

    /* Disabled Read-Only Fields */
    .form-control[readonly], 
    .form-control[disabled] {
        background: #dcdcdc !important;  /* Light Grey */
        color: #777 !important;  /* Dark Grey Text */
        cursor: not-allowed;
        box-shadow: none;
        border: 1px solid #bbb;
    }

    /* Labels */
    label {
        font-weight: bold;
        color: #555;
    }

    /* Sidebar */
    .sidebar {
        background: linear-gradient(180deg, #1c1c3c, #2e2e5e);
        color: white;
    }

    .sidebar a {
        color: #fff;
        text-decoration: none;
        transition: color 0.3s ease-in-out;
    }

    .sidebar a:hover {
        color: #007bff;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        border-radius: 10px;
        padding: 10px;
    }

    .breadcrumb-item a {
        color: #007bff;
        font-weight: bold;
        text-decoration: none;
    }

    .breadcrumb-item.active {
        color: #0056b3;
    }
</style>


<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <?php include("dist/_partials/nav.php"); ?>
        <?php include("dist/_partials/sidebar.php"); ?>

        <?php
        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM iB_bankAccounts WHERE account_id = ?";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_object()) {
        ?>
            <div class="content-wrapper">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update <?php echo $row->client_name; ?> iBanking Account</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_open_acc.php">iBanking Accounts</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->client_name; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-purple">
                                    <div class="card-header">
                                        <h3 class="card-title">Update Account Details</h3>
                                    </div>
                                    <form method="post">
                                        <div class="card-body">
                                            
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Client Name</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" class="form-control">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Client Number</label>
                                                    <input type="text" readonly name="client_number" value="<?php echo $row->client_number; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Client Phone</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" class="form-control">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Client National ID</label>
                                                    <input type="text" readonly name="client_national_id" value="<?php echo $row->client_national_id; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Client Email</label>
                                                    <input type="email" readonly name="client_email" value="<?php echo $row->client_email; ?>" class="form-control">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Client Address</label>
                                                    <input type="text" readonly name="client_adr" value="<?php echo $row->client_adr; ?>" class="form-control">
                                                </div>
                                            </div>

<!-- Add inside your existing form, just after Client Address -->
<div class="row">
    <div class="col-md-6 form-group">
        <label>Father's Name</label>
        <input type="text" name="client_father_name" value="<?php echo $row->client_father_name; ?>" class="form-control">
    </div>
    <div class="col-md-6 form-group">
        <label>Date of Birth</label>
        <input type="date" name="client_dob" value="<?php echo $row->client_dob; ?>" class="form-control">
    </div>
</div>

<div class="row">
    <div class="col-md-6 form-group">
        <label>Gender</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_gender" value="Male" <?php if ($row->client_gender == 'Male') echo 'checked'; ?>>
            <label class="form-check-label">Male</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_gender" value="Female" <?php if ($row->client_gender == 'Female') echo 'checked'; ?>>
            <label class="form-check-label">Female</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_gender" value="Other" <?php if ($row->client_gender == 'Other') echo 'checked'; ?>>
            <label class="form-check-label">Other</label>
        </div>
    </div>

    <div class="col-md-6 form-group">
        <label>Marital Status</label><br>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_marital_status" value="Single" <?php if ($row->client_marital_status == 'Single') echo 'checked'; ?>>
            <label class="form-check-label">Single</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_marital_status" value="Married" <?php if ($row->client_marital_status == 'Married') echo 'checked'; ?>>
            <label class="form-check-label">Married</label>
        </div>
    </div>
</div>


                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Aadhaar Number</label>
                                                    <input type="text" name="client_aadhaar" value="<?php echo $row->client_aadhaar; ?>" class="form-control">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>PAN Number</label>
                                                    <input type="text" name="client_pan" value="<?php echo $row->client_pan; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Account Name</label>
                                                    <input type="text" name="acc_name" value="<?php echo $row->acc_name; ?>" class="form-control">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Account Number</label>
                                                    <input type="text" name="account_number" value="<?php echo $row->account_number; ?>" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label>Account Type</label>
                                                    <select class="form-control" name="acc_type" onchange="getiBankAccs(this.value);">
                                                        <option value="<?php echo $row->acc_type; ?>"><?php echo $row->acc_type; ?></option>
                                                        <?php
                                                        $ret = "SELECT * FROM iB_Acc_types ORDER BY name ASC";
                                                        $stmt = $mysqli->prepare($ret);
                                                        $stmt->execute();
                                                        $res = $stmt->get_result();
                                                        while ($type = $res->fetch_object()) {
                                                        ?>
                                                            <option value="<?php echo $type->name; ?>"><?php echo $type->name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label>Account Type Rates (%)</label>
                                                    <input type="text" name="acc_rates" id="AccountRates" value="<?php echo $row->acc_rates; ?>" readonly class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <button type="submit" name="update_account" class="btn btn-success">Update Account</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        <?php } ?>
        <?php include("dist/_partials/footer.php"); ?>
    </div>

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
