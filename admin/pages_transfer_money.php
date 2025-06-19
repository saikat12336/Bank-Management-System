<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

if (isset($_POST['deposit'])) {
    $tr_code = $_POST['tr_code'];
    $account_id = $_GET['account_id'];
    $acc_name = $_POST['acc_name'];
    $account_number = $_GET['account_number'];
    $acc_type = $_POST['acc_type'];
    $tr_type  = $_POST['tr_type'];
    $tr_status = $_POST['tr_status'];
    $client_id  = $_GET['client_id'];
    $client_name  = $_POST['client_name'];
    $client_national_id  = $_POST['client_national_id'];
    $transaction_amt = $_POST['transaction_amt'];
    $client_phone = $_POST['client_phone'];
    $receiving_acc_no = $_POST['receiving_acc_no'];
    $receiving_acc_name = $_POST['receiving_acc_name'];
    $receiving_acc_holder = $_POST['receiving_acc_holder'];
    $notification_details = "$client_name Has Transferred Rs. $transaction_amt From Bank Account $account_number To Bank Account $receiving_acc_no";

    // ✅ Get Available Balance
    $stmt = $mysqli->prepare("SELECT SUM(CASE WHEN tr_type IN ('Deposit') THEN transaction_amt ELSE -transaction_amt END) AS balance FROM iB_Transactions WHERE account_id = ?");
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($balance);
    $stmt->fetch();
    $stmt->close();

    if ($balance === null) $balance = 0;

       if ($transaction_amt > $balance) {
        $transaction_error = "You Do Not Have Sufficient Funds In Your Account For Transfer. Your Current Account Balance Is Rs. $balance";
    } else {
        $query = "INSERT INTO iB_Transactions (tr_code, account_id, acc_name, account_number, acc_type, tr_type, tr_status, client_id, client_name, client_national_id, transaction_amt, client_phone, receiving_acc_no, receiving_acc_name, receiving_acc_holder, admin_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssssssssssss', $tr_code, $account_id, $acc_name, $account_number, $acc_type, $tr_type, $tr_status, $client_id, $client_name, $client_national_id, $transaction_amt, $client_phone, $receiving_acc_no, $receiving_acc_name, $receiving_acc_holder, $admin_id);
        $stmt->execute();

        // ✅ Credit Amount to Receiver's Account
$credit_query = "INSERT INTO iB_Transactions (
    tr_code, account_id, acc_name, account_number, acc_type, tr_type, tr_status, client_id, client_name, client_national_id, transaction_amt, client_phone, receiving_acc_no, receiving_acc_name, receiving_acc_holder, admin_id
) VALUES (?, ?, ?, ?, ?, 'Deposit', 'Success', ?, ?, ?, ?, ?, '', '', '',?)";

// Get receiver account_id
$receiver_stmt = $mysqli->prepare("SELECT account_id, client_id, client_name, client_national_id, client_phone, acc_name, acc_type FROM iB_bankAccounts WHERE account_number = ?");
$receiver_stmt->bind_param('s', $receiving_acc_no);
$receiver_stmt->execute();
$receiver_stmt->bind_result($receiver_account_id, $receiver_client_id, $receiver_client_name, $receiver_national_id, $receiver_phone, $receiver_acc_name, $receiver_acc_type);
$receiver_stmt->fetch();
$receiver_stmt->close();

if ($receiver_account_id) {
    $stmt_credit = $mysqli->prepare($credit_query);
    $stmt_credit->bind_param(
        'sisssssssss',
        $tr_code,                     // Same transaction code
        $receiver_account_id,        // Receiver's account_id
        $receiver_acc_name,          // Receiver account name
        $receiving_acc_no,           // Receiver account number
        $receiver_acc_type,          // Account type
        $receiver_client_id,         // Receiver client_id
        $receiver_client_name,       // Receiver name
        $receiver_national_id,       // Receiver NID
        $transaction_amt,            // Amount credited
        $receiver_phone,            // Receiver phone
        $admin_id              
    );
    $stmt_credit->execute();

    // 📢 Notify Receiver
    $receiver_notification = "An amount of Rs. $transaction_amt has just been credited to your iBank account by $client_name, A/C: $account_number Handled by Admin.";
    $stmt_notify_receiver = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('client', ?, ?)");
    $stmt_notify_receiver->bind_param('is', $receiver_client_id, $receiver_notification);
    $stmt_notify_receiver->execute();
}


        // 🕒 Format Timestamp
        $current_datetime = date("Y-m-d H:i");

        // 📢 Notification Messages
        $admin_msg = "$client_name has transferred Rs. $transaction_amt from Account No. $account_number to Account No. $receiving_acc_no ( $receiving_acc_holder ) Handled by Admin.";
        $client_msg = "Rs. $transaction_amt has been transferred from your iBank Account $account_number to Account $receiving_acc_no - $receiving_acc_holder.";

        // 🔔 Insert Admin/Staff Notification
        $stmt1 = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('admin', ?, ?)");
        $stmt1->bind_param('is', $client_id, $admin_msg);
        $stmt1->execute();

// 🔔 Insert Staff Notification
$stmt_staff = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('staff', ?, ?)");
$stmt_staff->bind_param('is', $client_id, $admin_msg);
$stmt_staff->execute();

        // 🔔 Insert Client Notification
        $stmt2 = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('client', ?, ?)");
        $stmt2->bind_param('is', $client_id, $client_msg);
        $stmt2->execute();

        if ($stmt && $stmt1 && $stmt_staff && $stmt2) {
            $success = "Money Transferred Successfully.";
        } else {
            $err = "Please Try Again Later.";
        }
    }
}
// Handle AJAX request to fetch account details
if (isset($_POST['action']) && $_POST['action'] == 'getAccountDetails') {
    $account_number = $_POST['account_number'];
    $stmt = $mysqli->prepare("SELECT acc_name, client_name FROM iB_bankAccounts WHERE account_number = ?");
    $stmt->bind_param('s', $account_number);
    $stmt->execute();
    $stmt->bind_result($acc_name, $client_name);
    if ($stmt->fetch()) {
        echo json_encode(['success' => true, 'acc_name' => $acc_name, 'client_name' => $client_name]);
    } else {
        echo json_encode(['success' => false]);
    }
    $stmt->close();
    exit; // Stop further processing
}

?>
<!-- HTML Part -->
<!DOCTYPE html>
<html>
<?php include("dist/_partials/head.php"); ?>
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
        // ✅ Calculate Updated Balance
        $stmt = $mysqli->prepare("SELECT SUM(CASE WHEN tr_type IN ('Deposit') THEN transaction_amt ELSE -transaction_amt END) AS balance FROM iB_Transactions WHERE account_id = ?");
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close();
        if ($balance === null) $balance = 0;
    ?>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1>Transfer Money</h1></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?php echo $row->acc_name; ?></li>
                        </ol>
                    </div>
                </div>
                <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                <?php if (isset($transaction_error)) echo "<div class='alert alert-danger'>$transaction_error</div>"; ?>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <form method="post">
                    <div class="card card-purple">
                        <div class="card-header"><h3 class="card-title">Fill All Fields</h3></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Client Name</label>
                                    <input type="text" readonly name="client_name" value="<?php echo $row->client_name; ?>" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Client National ID No.</label>
                                    <input type="text" readonly name="client_national_id" value="<?php echo $row->client_national_id; ?>" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Client Phone Number</label>
                                    <input type="text" readonly name="client_phone" value="<?php echo $row->client_phone; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Account Name</label>
                                    <input type="text" readonly name="acc_name" value="<?php echo $row->acc_name; ?>" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Account Number</label>
                                    <input type="text" readonly name="account_number" value="<?php echo $row->account_number; ?>" class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Account Type</label>
                                    <input type="text" readonly name="acc_type" value="<?php echo $row->acc_type; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                
                                    <input type="hidden" name="tr_code" readonly value="<?php echo substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 12); ?>" class="form-control">
                               
                                <div class="col-md-4 form-group">
                                    <label>Current Account Balance</label>
                                    <input type="text" readonly value="<?php echo $balance; ?>" class="form-control">
                                </div>

                                <div class="col-md-4 form-group">
                                    <label>Receiving Account Number</label>
                                    <select name="receiving_acc_no" required class="form-control">
                                        <option value="">Select Receiving Account</option>
                                       <?php
  $all = $mysqli->query("SELECT * FROM iB_bankAccounts WHERE account_id != $account_id AND acc_status = 'Approved'");
  while ($recv = $all->fetch_object()) {
      echo "<option value='$recv->account_number'>$recv->account_number</option>";
  }
  ?>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label>Amount To Transfer (Rs.)</label>
                                    <input type="number" name="transaction_amt" required class="form-control" min="1">
                                </div>
                            
                                
                                <div class="col-md-4 form-group">
                                    <label>Receiving Account Name</label>
                                    <input type="text" name="receiving_acc_name" required class="form-control">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Receiving Account Holder</label>
                                    <input type="text" name="receiving_acc_holder" required class="form-control">
                                </div>
                                <input type="hidden" name="tr_type" value="Transfer">
                                <input type="hidden" name="tr_status" value="Success">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" name="deposit" class="btn btn-success">Transfer Funds</button>
                        </div>
                    </div>
                </form>
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
    <script>
$(document).ready(function() {
    // Event handler for receiving account number change
    $('select[name="receiving_acc_no"]').change(function() {
        var receiving_acc_no = $(this).val();

        // If an account is selected
        if (receiving_acc_no != "") {
            // AJAX request to fetch account details
            $.ajax({
                url: './pages_transfer_money.php', // same page
                type: 'POST',
                data: {
                    action: 'getAccountDetails', // custom action
                    account_number: receiving_acc_no
                },
                success: function(response) {
                    // Parse the JSON response
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Fill in the fields with the received data
                        $('input[name="receiving_acc_name"]').val(data.acc_name);
                        $('input[name="receiving_acc_holder"]').val(data.client_name);
                    } else {
                        alert('Account details not found.');
                    }
                }
            });
        }
    });
});
</script>

</body>
</html>
