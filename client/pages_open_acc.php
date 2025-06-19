<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = $_SESSION['client_id'];

if (isset($_POST['open_account'])) {
    $acc_name = $_POST['acc_name'];
    $account_number = $_POST['account_number'];
    $acc_type = $_POST['acc_type'];
    $acc_rates = $_POST['acc_rates'];
    $acc_status = "Pending";
    $acc_amount = $_POST['acc_amount'];
    $client_national_id = $_POST['client_national_id'];
    $client_pan = $_POST['client_pan'];
    $client_aadhaar = $_POST['client_aadhaar'];
    $client_name = $_POST['client_name'];
    $client_phone = $_POST['client_phone'];
    $client_number = $_POST['client_number'];
    $client_father_name = $_POST['client_father_name'];
    $client_dob = $_POST['client_dob'];
    $client_gender = $_POST['client_gender'];
    $client_marital_status = $_POST['client_marital_status'];

    $client_email = isset($_POST['client_email']) ? $_POST['client_email'] : '';
    $client_adr = isset($_POST['client_adr']) ? $_POST['client_adr'] : '';

    $check_query = "SELECT * FROM ib_bankaccounts WHERE client_pan = ? OR client_aadhaar = ?";
    $stmt_check = $mysqli->prepare($check_query);
    $stmt_check->bind_param('ss', $client_pan, $client_aadhaar);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();

    if ($res_check->num_rows > 0) {
        $_SESSION['form_error'] = "PAN or Aadhaar already exists in another account. Please check and try again.";
    } else {
        $query = "INSERT INTO ib_bankaccounts (
            acc_name, account_number, acc_type, acc_rates, acc_status, acc_amount, 
            client_id, client_name, client_national_id, client_pan, client_aadhaar, 
            client_phone, client_number, client_email, client_adr,
            client_father_name, client_dob, client_gender, client_marital_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssssssssssssssss',
            $acc_name, $account_number, $acc_type, $acc_rates, $acc_status, $acc_amount,
            $client_id, $client_name, $client_national_id, $client_pan, $client_aadhaar,
            $client_phone, $client_number, $client_email, $client_adr,
            $client_father_name, $client_dob, $client_gender, $client_marital_status
        );

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $_SESSION['form_success'] = "iBank Account Request Submitted Successfully!";
            header("Location: pages_open_acc.php");
            exit();
        } else {
            $_SESSION['form_error'] = "Error! Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<?php include("dist/_partials/head.php"); ?>

<style>
    body {
        background: linear-gradient(to right, #e0f7fa, #fffde7);
    }

    .card-purple {
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(100, 0, 200, 0.2);
    }

    .card-header {
        background-color: #6a1b9a;
        color: #fff;
        border-radius: 15px 15px 0 0;
    }

    .form-control:hover,
    .form-control:focus {
        border-color: #6a1b9a;
        box-shadow: 0 0 10px #ce93d8;
        transition: all 0.3s ease;
    }

    .btn-success {
        background: linear-gradient(145deg, #43a047, #2e7d32);
        border: none;
        padding: 10px 20px;
        font-weight: bold;
        color: white;
        box-shadow: 0 6px 10px rgba(67, 160, 71, 0.5);
        transition: 0.3s;
    }

    .btn-success:hover {
        background: linear-gradient(145deg, #66bb6a, #388e3c);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(67, 160, 71, 0.7);
    }

    .alert {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        font-weight: bold;
    }
</style>

<body>
<div class="wrapper">
    <?php include("dist/_partials/nav.php"); ?>
    <?php include("dist/_partials/sidebar.php"); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Open iBanking Account</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <?php if (isset($_SESSION['form_error'])) { ?>
                    <div class="alert alert-danger" id="errorAlert"><?php echo $_SESSION['form_error']; unset($_SESSION['form_error']); ?></div>
                <?php } ?>
                <?php if (isset($_SESSION['form_success'])) { ?>
                    <div class="alert alert-success" id="successAlert"><?php echo $_SESSION['form_success']; unset($_SESSION['form_success']); ?></div>
                <?php } ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-purple">
                            <div class="card-header">
                                <h3 class="card-title">Fill All Fields</h3>
                            </div>
                            <form method="post" onsubmit="return validateForm()">
                                <div class="card-body">
                                    <?php
                                    $ret = "SELECT * FROM ib_clients WHERE client_id = ?";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->bind_param('i', $client_id);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $row = $res->fetch_object();
                                    ?>

                                    <input type="hidden" name="client_email" value="<?php echo $row->email; ?>">
                                    <input type="hidden" name="client_adr" value="<?php echo $row->address; ?>">

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Client Name</label>
                                            <input type="text" readonly name="client_name" value="<?php echo $row->name; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Client Number</label>
                                            <input type="text" readonly name="client_number" value="<?php echo $row->client_number; ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Client Phone</label>
                                            <input type="text" readonly name="client_phone" value="<?php echo $row->phone; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Client National ID</label>
                                            <input type="text" readonly name="client_national_id" value="<?php echo $row->national_id; ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Client Email</label>
                                            <input type="text" readonly value="<?php echo $row->email; ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Client Address</label>
                                            <input type="text" readonly value="<?php echo $row->address; ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Father's Name</label>
                                            <input type="text" name="client_father_name" required class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Date of Birth</label>
                                            <input type="date" name="client_dob" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Gender</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="client_gender" value="Male" required>
                                                <label class="form-check-label">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="client_gender" value="Female">
                                                <label class="form-check-label">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="client_gender" value="Other">
                                                <label class="form-check-label">Other</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Marital Status</label><br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="client_marital_status" value="Single" required>
                                                <label class="form-check-label">Single</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="client_marital_status" value="Married">
                                                <label class="form-check-label">Married</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>PAN Number</label>
                                            <input type="text" name="client_pan" required class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Aadhaar Number</label>
                                            <input type="text" name="client_aadhaar" required class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>iBank Account Type</label>
                                            <select class="form-control" id="acc_type" name="acc_type" onchange="fetchRate(this.value)">
                                                <option>Select Account Type</option>
                                                <?php
                                                $ret = "SELECT * FROM ib_acc_types ORDER BY name";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                while ($acc = $res->fetch_object()) {
                                                    echo "<option value='$acc->name' data-rate='$acc->rate'>$acc->name</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Account Type Rates (%)</label>
                                            <input type="text" id="acc_rates" name="acc_rates" readonly class="form-control">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Account Name</label>
                                            <input type="text" name="acc_name" required class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Account Number</label>
                                            <?php $acc_number = substr(str_shuffle('0123456789'), 0, 12); ?>
                                            <input type="text" name="account_number" value="<?php echo $acc_number; ?>" readonly class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group" style="display:none;">
                                        <input type="text" name="acc_status" value="Pending" readonly>
                                        <input type="text" name="acc_amount" value="0" readonly>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" name="open_account" class="btn btn-success">Open iBanking Account</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include("dist/_partials/footer.php"); ?>
</div>

<script>
    function fetchRate(accountType) {
        let accDropdown = document.getElementById("acc_type");
        let selectedOption = accDropdown.options[accDropdown.selectedIndex];
        let rate = selectedOption.getAttribute("data-rate");
        document.getElementById("acc_rates").value = rate ? rate : 'N/A';
    }

    function validateForm() {
        let pan = document.querySelector("input[name='client_pan']").value.trim();
        let aadhaar = document.querySelector("input[name='client_aadhaar']").value.trim();
        let panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        let aadhaarPattern = /^\d{12}$/;

        if (!panPattern.test(pan)) {
            alert("Invalid PAN number! It should be 10 characters (ABCDE1234F).");
            return false;
        }
        if (!aadhaarPattern.test(aadhaar)) {
            alert("Invalid Aadhaar number! Must be exactly 12 digits.");
            return false;
        }
        return true;
    }

    // Auto-hide alert after 7 seconds
    setTimeout(() => {
        const alertSuccess = document.getElementById('successAlert');
        const alertError = document.getElementById('errorAlert');
        if (alertSuccess) alertSuccess.style.display = 'none';
        if (alertError) alertError.style.display = 'none';
    }, 7000);
</script>
</body>
</html>
