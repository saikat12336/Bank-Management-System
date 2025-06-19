<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();

// Check if the required GET parameters exist
if (isset($_GET['account_id'])) {
    $account_id = $_GET['account_id'];
} else {
    $_SESSION['error_msg'] = "Account ID is missing!";
    header("Location: pages_transfer_money.php");
    exit();
}

// Check if the required POST parameters exist
if (isset($_POST['deposit'])) {
    $tr_code = $_POST['tr_code'];
    $account_id = $_GET['account_id'];
    $acc_name = $_POST['acc_name'];
    $account_number = isset($_GET['account_number']) ? $_GET['account_number'] : '';
    $acc_type = $_POST['acc_type'];
    $tr_type  = $_POST['tr_type'];
    $tr_status = $_POST['tr_status'];
    $client_id  = isset($_GET['client_id']) ? $_GET['client_id'] : '';
    $client_name  = $_POST['client_name'];
    $client_national_id  = $_POST['client_national_id'];
    $transaction_amt = $_POST['transaction_amt'];
    $client_phone = $_POST['client_phone'];

    // Fund transfer fields
    $receiving_acc_no = $_POST['receiving_acc_no'];
    $receiving_acc_name = $_POST['receiving_acc_name'];
    $receiving_acc_holder = $_POST['receiver_name'];

    // Calculate actual balance (credits - debits)
    $credits_query = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE account_id=? AND (tr_type='Deposit')";
    $debits_query  = "SELECT SUM(transaction_amt) FROM iB_Transactions WHERE account_id=? AND (tr_type='Withdrawal' OR tr_type='Transfer')";

    $stmt_credit = $mysqli->prepare($credits_query);
    $stmt_credit->bind_param('i', $account_id);
    $stmt_credit->execute();
    $stmt_credit->bind_result($total_credits);
    $stmt_credit->fetch();
    $stmt_credit->close();

    $stmt_debit = $mysqli->prepare($debits_query);
    $stmt_debit->bind_param('i', $account_id);
    $stmt_debit->execute();
    $stmt_debit->bind_result($total_debits);
    $stmt_debit->fetch();
    $stmt_debit->close();

    $available_balance = $total_credits - $total_debits;

    if ($transaction_amt > $available_balance) {
        // Store error message in session to display after page reload
        $_SESSION['error_msg'] = "You Do Not Have Sufficient Funds In Your Account For Transfer. Your Current Account Balance Is Rs. $available_balance";
        header("Location: pages_transfer_money.php?account_id=$account_id"); // Refresh the page
        exit();
    } else {
        $notification_details = "$client_name has transferred Rs. $transaction_amt from Account No. $account_number to Account No. $receiving_acc_no ( $receiving_acc_holder )";

        $query = "INSERT INTO iB_Transactions (tr_code, account_id, acc_name, account_number, acc_type, tr_type, tr_status, client_id, client_name, client_national_id, transaction_amt, client_phone, receiving_acc_no, receiving_acc_name, receiving_acc_holder) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('sssssssssssssss', $tr_code, $account_id, $acc_name, $account_number, $acc_type, $tr_type, $tr_status, $client_id, $client_name, $client_national_id, $transaction_amt, $client_phone, $receiving_acc_no, $receiving_acc_name, $receiving_acc_holder);
        $stmt->execute();

        // Step 1: Get receiving account ID from account number
$get_receiver = "SELECT account_id, acc_name, acc_type, client_id, client_name, client_national_id, client_phone FROM iB_bankAccounts WHERE account_number = ?";
$stmt_receiver = $mysqli->prepare($get_receiver);
$stmt_receiver->bind_param('s', $receiving_acc_no);
$stmt_receiver->execute();
$stmt_receiver->store_result();

if ($stmt_receiver->num_rows > 0) {
    $stmt_receiver->bind_result($receiver_acc_id, $receiver_acc_name, $receiver_acc_type, $receiver_client_id, $receiver_client_name, $receiver_national_id, $receiver_phone);
    $stmt_receiver->fetch();
    $stmt_receiver->close();

    // Step 2: Insert transaction for receiver (credit)
    $tr_type_receiver = 'Deposit'; // Or 'Transfer Received'
    $stmt_receiver_insert = $mysqli->prepare($query);
    $stmt_receiver_insert->bind_param(
        'sssssssssssssss',
        $tr_code,                 // Same transaction code
        $receiver_acc_id,
        $receiver_acc_name,
        $receiving_acc_no,
        $receiver_acc_type,
        $tr_type_receiver,
        $tr_status,
        $receiver_client_id,
        $receiver_client_name,
        $receiver_national_id,
        $transaction_amt,
        $receiver_phone,
        $account_number,         // Original sender's account number
        $acc_name,
        $client_name
    );
    $stmt_receiver_insert->execute();
}


        // Send notifications
        $notification_admin = "INSERT INTO iB_notifications (notification_for, client_id, notification_details) VALUES (?, ?, ?)";
        $admin = 'admin';
        $staff = 'staff';

        $notification_stmt_admin = $mysqli->prepare($notification_admin);
        $notification_stmt_admin->bind_param('sis', $admin, $client_id, $notification_details);
        $notification_stmt_admin->execute();

        $notification_stmt_staff = $mysqli->prepare($notification_admin);
        $notification_stmt_staff->bind_param('sis', $staff, $client_id, $notification_details);
        $notification_stmt_staff->execute();



// ✅ Notification for Sender (client1)
$sender_msg = "Rs. $transaction_amt has been successfully transferred from your iBank Account to account no. $receiving_acc_no ( $receiving_acc_holder ).";
$insert_sender = $mysqli->prepare("INSERT INTO iB_notifications (notification_for, client_id, notification_details, created_at) VALUES ('Client', ?, ?, ?)");
$insert_sender->bind_param('iss', $client_id, $sender_msg, $created_at);
$insert_sender->execute();

// ✅ Notification for Receiver (client2)
$receiver_msg = "An amount of Rs. $transaction_amt has just been credited to your iBank account by $client_name, A/C: $account_number.";
$insert_receiver = $mysqli->prepare("INSERT INTO iB_notifications (notification_for, client_id, notification_details, created_at) VALUES ('Client', ?, ?, ?)");
$insert_receiver->bind_param('iss', $receiver_client_id, $receiver_msg, $created_at);
$insert_receiver->execute();


        if ($stmt && $notification_stmt_admin && $notification_stmt_staff) {
            $_SESSION['success_msg'] = "Money Transferred Successfully!";
            header("Location: pages_transfer_money.php?account_id=$account_id");
            exit();
        } else {
            $_SESSION['error_msg'] = "Something went wrong. Please try again.";
            header("Location: pages_transfer_money.php?account_id=$account_id");
            exit();
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
        $account_id = $_GET['account_id'];
        $ret = "SELECT * FROM iB_bankAccounts WHERE account_id = ? ";
        $stmt = $mysqli->prepare($ret);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
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
                                <h1>Transfer Money</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Finances</a></li>
                                    <li class="breadcrumb-item"><a href="pages_transfer_money.php">Transfer</a></li>
                                    <li class="breadcrumb-item active"><?php echo $row->acc_name; ?></li>
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

                                    <!-- Display success or error messages if set in the session -->
                                    <?php
                                    if (isset($_SESSION['error_msg'])) {
                                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                ' . $_SESSION['error_msg'] . '
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>';
                                        unset($_SESSION['error_msg']);
                                    }

                                    if (isset($_SESSION['success_msg'])) {
                                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                ' . $_SESSION['success_msg'] . '
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>';
                                        unset($_SESSION['success_msg']);
                                    }
                                    ?>
<style>
        .form-control:hover {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            background-color: #f1f9ff;
        }
</style>

                                    <!-- form start -->
                                    <form method="post" enctype="multipart/form-data" role="form">
                                        <div class="card-body" style="background: linear-gradient(135deg, #e6e6fa, #d8bfd8, #dda0dd);">

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Name</label>
                                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Client National ID No.</label>
                                                    <input type="text" readonly value="<?php echo $row->client_national_id; ?>" name="client_national_id" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Client Phone Number</label>
                                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Name</label>
                                                    <input type="text" readonly name="acc_name" value="<?php echo $row->acc_name; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputPassword1">Account Number</label>
                                                    <input type="text" readonly value="<?php echo $row->account_number; ?>" name="account_number" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group">
                                                    <label for="exampleInputEmail1">Account Type | Category</label>
                                                    <input type="text" readonly name="acc_type" value="<?php echo $row->acc_type; ?>" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                            </div>

<div class="row">
    <!-- Hidden Transaction Code -->
    <?php
    $length = 20;
    $_transcode = substr(str_shuffle('0123456789QWERgfdsazxcvbnTYUIOqwertyuioplkjhmPASDFGHJKLMNBVCXZ'), 1, $length);
    ?>
    <input type="hidden" name="tr_code" value="<?php echo $_transcode; ?>">

    <!-- Receiving Account Number -->
    <div class="col-md-6 mb-3">
        <label for="receiving_acc_no" class="form-label">Receiving Account Number</label>
        <input type="text" name="receiving_acc_no" id="receiving_acc_no" class="form-control" required>
        <div id="account-validation-result" class="form-text text-danger mt-1"></div>
    </div>

    <!-- Amount Transferred -->
    <div class="col-md-6 mb-3">
        <label for="transaction_amt" class="form-label">Amount Transferred (Rs.)</label>
        <input type="text" name="transaction_amt" class="form-control" id="transaction_amt" required>
    </div>

</div>

<div class="row">
    <!-- Receiving Account Name -->
    <div class="col-md-4 mb-3">
        <label for="ReceivingAccName" class="form-label">Receiving Account Name</label>
        <input type="text" name="receiving_acc_name" class="form-control" id="ReceivingAccName" required>
    </div>

    <!-- Receiving Account Holder -->
    <div class="col-md-4 mb-3">
        <label for="receiver_name" class="form-label">Receiving Account Holder</label>
        <input type="text" class="form-control" id="receiver_name" name="receiver_name" required>
    </div>
</div>


                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Type</label>
                                                    <input type="text" name="tr_type" value="Transfer" required class="form-control" id="exampleInputEmail1">
                                                </div>
                                                <div class=" col-md-4 form-group" style="display:none">
                                                    <label for="exampleInputPassword1">Transaction Status</label>
                                                    <input type="text" name="tr_status" value="Success " required class="form-control" id="exampleInputEmail1">
                                                </div>

                                            </div>

                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button id="transfer_button" type="submit" name="deposit" class="btn btn-primary" disabled>Transfer</button>
                                        </div>
                                        <!-- <button type="submit" name="deposit" class="btn btn-success">Transfer Funds</button> -->

                                    </form>
                                </div>
                                <!-- /.card -->
                            </div><!-- /.container-fluid -->
                </section>
                <!-- /.content -->
            </div><!-- Log on to codeastro.com for more projects! -->
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
<script>
$(document).ready(function() {
    $('#transfer_button').prop('disabled', true);

    $('#receiving_acc_no').on('blur', function() {
        var accountNumber = $(this).val().trim();

        if (accountNumber !== '') {
            $.ajax({
                url: 'ajax/check_account.php',
                method: 'GET',
                dataType: 'json',
                data: { acc_no: accountNumber },
                success: function(response) {
                    console.log(response);

                    if (response.status === 'valid') {
                        // ✅ Set both values from DB
                        $('#ReceivingAccName').val(response.account_name).prop('readonly', true);
                        $('#receiver_name').val(response.client_name).prop('readonly', true);

                        $('#account-validation-result').html('<span class="text-success">Account no. is valid and approved.</span>');
                        $('#transfer_button').prop('disabled', false);
                    } else {
                        $('#ReceivingAccName').val('').prop('readonly', false);
                        $('#receiver_name').val('').prop('readonly', false);

                        $('#account-validation-result').html('<span class="text-danger">' + response.message + '</span>');
                        $('#transfer_button').prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                    $('#account-validation-result').html('<span class="text-danger">Server error while validating account.</span>');
                    $('#transfer_button').prop('disabled', true);
                }
            });
        } else {
            $('#ReceivingAccName').val('').prop('readonly', false);
            $('#receiver_name').val('').prop('readonly', false);

            $('#account-validation-result').empty();
            $('#transfer_button').prop('disabled', true);
        }
    });
});
</script>




</body>

</html>