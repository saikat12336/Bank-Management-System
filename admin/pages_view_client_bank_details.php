<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

if (!isset($_GET['account_id'])) {
    header("Location: pages_manage_acc_openings.php");
    exit();
}

$account_id = intval($_GET['account_id']);

// Fetch client bank account details
$query = "SELECT * FROM iB_bankAccounts WHERE account_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $account_id);
$stmt->execute();
$res = $stmt->get_result();
$client = $res->fetch_object();

// Save client_id and name for notification
$client_id = $client->client_id;
$client_name = $client->acc_name; 

// Handle Approve/Reject
if (isset($_POST['approve'])) {
    $updateQuery = "UPDATE iB_bankAccounts SET acc_status = 'Approved' WHERE account_id = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->close();

    // Notify client
    $notif_client = "Your iBank account request has been <strong>Approved</strong>.";
    $stmtNotifClient = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('client', ?, ?)");
    $stmtNotifClient->bind_param("is", $client_id, $notif_client);
    $stmtNotifClient->execute();
    $stmtNotifClient->close();

    // Notify staff
    $notif_staff = "$client_name iBank account request has been Approved.";
    $stmtNotifStaff = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('staff', ?, ?)");
    $stmtNotifStaff->bind_param("is", $client_id, $notif_staff);
    $stmtNotifStaff->execute();
    $stmtNotifStaff->close();

    echo "<script>
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('flash-msg');
            alert.style.display = 'block';
            setTimeout(() => {
                window.location.href = window.location.href;
            }, 5000);
        });
    </script>";
    $msg = "Account Approved Successfully!";
} elseif (isset($_POST['reject'])) {
    $updateQuery = "UPDATE iB_bankAccounts SET acc_status = 'Rejected' WHERE account_id = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->close();

    // Notify client
    $notif_client = "Your iBank account request has been <strong>Rejected</strong>.";
    $stmtNotifClient = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('client', ?, ?)");
    $stmtNotifClient->bind_param("is", $client_id, $notif_client);
    $stmtNotifClient->execute();
    $stmtNotifClient->close();

    // Notify staff
    $notif_staff = "$client_name iBank account request has been Rejected.";
    $stmtNotifStaff = $mysqli->prepare("INSERT INTO ib_notifications (notification_for, client_id, notification_details) VALUES ('staff', ?, ?)");
    $stmtNotifStaff->bind_param("is", $client_id, $notif_staff);
    $stmtNotifStaff->execute();
    $stmtNotifStaff->close();

    echo "<script>
        document.addEventListener('DOMContentLoaded', () => {
            const alert = document.getElementById('flash-msg');
            alert.style.display = 'block';
            setTimeout(() => {
                window.location.href = window.location.href;
            }, 5000);
        });
    </script>";
    $msg = "Account Rejected!";
}
?>

<!DOCTYPE html>
<html>
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <div class="wrapper">
    <?php include("dist/_partials/nav.php"); ?>
    <?php include("dist/_partials/sidebar.php"); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 style="color: #007bff;">View Client Bank Details</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="pages_manage_acc_openings.php">iBank Accounts</a></li>
                <li class="breadcrumb-item active"><?php echo $client->client_name ?>'s Details</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                  <h3 class="card-title">Client Bank Account Details</h3>
                </div>

                <div class="card-body">
                  <?php if (isset($msg)) { ?>
                    <div id="flash-msg" class="alert alert-success" style="display: none; transition: all 0.5s ease;">
                      <?php echo $msg; ?>
                    </div>
                  <?php } ?>

                  <table class="table table-bordered table-hover">
                    <?php
                    $fields = [
                      'Client Name' => $client->client_name,
                      'Client Email' => $client->client_email,
                      "Gender" => $client->client_gender,
                      "Marital Status" => $client->client_marital_status,
                      "DOB" => $client->client_dob,
                      "Father's Name" => $client->client_father_name,
                      "Client PAN" => $client->client_pan,
                      "Client Aadhaar" => $client->client_aadhaar,
                      "Account Name" => $client->acc_name,
                      "Account Number" => $client->account_number,
                      "Account Type" => $client->acc_type,
                      "Interest Rate" => $client->acc_rates . '%',
                      "Date Opened" => date("d-M-Y", strtotime($client->created_at))
                    ];
                    foreach ($fields as $key => $value) {
                      echo "<tr><th style='background-color: #f8f9fa;'>$key</th><td>$value</td></tr>";
                    }
                    ?>
                    <tr>
                      <th style="background-color: #f8f9fa;">Account Status</th>
                      <td>
                        <span class="badge badge-<?php echo ($client->acc_status == 'Approved') ? 'success' : (($client->acc_status == 'Rejected') ? 'danger' : 'warning'); ?>">
                          <?php echo $client->acc_status; ?>
                        </span>
                      </td>
                    </tr>
                  </table>

                  <form method="POST" class="mt-3">
                    <?php if ($client->acc_status == "Pending") { ?>
                      <button type="submit" name="approve" class="btn btn-success mr-2" style="transition: 0.3s;">
                        <i class="fas fa-check"></i> Approve
                      </button>
                      <button type="submit" name="reject" class="btn btn-danger" style="transition: 0.3s;">
                        <i class="fas fa-times"></i> Reject
                      </button>
                    <?php } else { ?>
                      <button class="btn btn-secondary" disabled><?php echo $client->acc_status; ?></button>
                    <?php } ?>
                    <a href="pages_manage_acc_openings.php" class="btn btn-primary ml-2" style="transition: 0.3s;">
                      <i class="fas fa-arrow-left"></i> Back
                    </a>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include("dist/_partials/footer.php"); ?>
  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>

</body>
</html>