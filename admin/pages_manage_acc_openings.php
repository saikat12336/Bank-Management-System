<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$admin_id = $_SESSION['admin_id'];

// Fire staff Delete Bank Account
if (isset($_GET['deleteBankAcc'])) {
    $id = intval($_GET['deleteBankAcc']);
    $adn = "DELETE FROM iB_bankAccounts WHERE account_id = ?";
    $stmt = $mysqli->prepare($adn);
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        $info = "iBanking Account Closed";
    } else {
        $err = "Try Again Later";
    }
    $stmt->close();
}

// Approve Client Bank Account
if (isset($_GET['approveAcc'])) {
    $account_id = intval($_GET['approveAcc']);

    // Fetch client details
    $query = "SELECT ib_clients.client_id, ib_clients.client_email 
              FROM ib_clients 
              INNER JOIN iB_bankAccounts ON ib_clients.client_id = iB_bankAccounts.client_id 
              WHERE iB_bankAccounts.account_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $account_id);
    $stmt->execute();
    $stmt->bind_result($client_id, $client_email);
    $stmt->fetch();
    $stmt->close();

    if (!empty($client_id) && !empty($client_email)) {
        // Update account status to 'Active'
        $updateQuery = "UPDATE iB_bankAccounts SET acc_status = 'Approved' WHERE account_id = ?";
        $stmt = $mysqli->prepare($updateQuery);
        $stmt->bind_param('i', $account_id);
        $stmt->execute();
        $stmt->close();


    }
}
?>


<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
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
              <h1>Manage iBanking Accounts</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="pages_manage_acc_openings.php">iBank Accounts</a></li>
                <li class="breadcrumb-item active">Manage Accounts</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Select on any action options to manage clients accounts</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-hover table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Acc Number</th>
                      <th>Rate</th>
                      <th>Acc Type</th>
                      <th>Acc Owner</th>
                      <th>Date Opened</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ret = "SELECT * FROM iB_bankAccounts ORDER BY RAND()";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $res->fetch_object()) {
                        $dateOpened = $row->created_at;
                        // Status Badge Color
                        $statusClass = ($row->acc_status == 'Approved') ? 'background:#28a745; color:white;' : 
                                      (($row->acc_status == 'Rejected') ? 'background:#dc3545; color:white;' : 
                                      'background:#ffc107; color:black;');
                    ?>
                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->acc_name; ?></td>
                        <td><?php echo $row->account_number; ?></td>
                        <td><?php echo $row->acc_rates; ?>%</td>
                        <td><?php echo $row->acc_type; ?></td>
                        <td><?php echo $row->client_name; ?></td>
                        <td><?php echo date("d-M-Y", strtotime($dateOpened)); ?></td>
                        <td>
                          <span style="padding:5px 10px; border-radius:20px; <?php echo $statusClass; ?>">
                            <?php echo $row->acc_status; ?>
                          </span>
                        </td>
                        <td>
                          <a class="btn btn-info btn-sm" href="pages_view_client_bank_details.php?account_id=<?php echo $row->account_id; ?>">
                            <i class="fas fa-eye"></i> View Details
                          </a>
                          <a class="btn btn-sm text-white" style="background: linear-gradient(45deg, #6a11cb, #2575fc); border: none;" href="pages_update_client_accounts.php?account_id=<?php echo $row->account_id; ?>">
  <i class="fas fa-tools"></i> Manage
</a>
                          <a class="btn btn-danger btn-sm" href="pages_manage_acc_openings.php?deleteBankAcc=<?php echo $row->account_id; ?>">
                            <i class="fas fa-times"></i> Close Account
                          </a>
                        </td>
                      </tr>
                    <?php $cnt++; } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <?php include("dist/_partials/footer.php"); ?>
  </div>
</body>
</html>
