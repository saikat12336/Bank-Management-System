<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();
$client_id = intval($_SESSION['client_id']);
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
              <h1 style="color:black; text-shadow:2px 2px 5px rgba(0,0,0,2.9);">💳 My Bank Accounts</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="pages_dashboard.php" style="color:#007bff;">Dashboard</a></li>
                <li class="breadcrumb-item active">iBank Accounts</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-12">
            <div class="card" style="border-radius:15px; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
              <div class="card-header" style="background: linear-gradient(45deg, #007bff, #0056b3); color:white; border-radius:15px 15px 0px 0px;">
                <h3 class="card-title">📜 Account Details</h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-hover table-striped" style="border-radius:10px; overflow:hidden;">
                  <thead style="background:linear-gradient(45deg, #007bff, #0056b3); color:white;">
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Account No.</th>
                      <th>Rate</th>
                      <th>Acc. Type</th>
                      <th>Acc. Owner</th>
			<th>Gender</th>
			<th>DOB</th>
			<th>Maritial Status</th>
			<th>Father's Name</th>
                      <th>Aadhaar No.</th>
                      <th>PAN No.</th>
                      <th>Status</th>
                      <th>Date Opened</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $ret = "SELECT acc_name, account_number, acc_rates, acc_type, client_name, client_gender, client_dob, client_marital_status, client_father_name, client_aadhaar, client_pan, acc_status, created_at 
                            FROM iB_bankAccounts WHERE client_id = ? LIMIT 100";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('i', $client_id);
                    if ($stmt->execute()) {
                      $res = $stmt->get_result();
                      $cnt = 1;
                      while ($row = $res->fetch_object()) {
                        $dateOpened = $row->created_at;
                        // Status Badge Color
                        $statusClass = ($row->acc_status == 'Approved') ? 'background:#28a745; color:white;' : 
                                      (($row->acc_status == 'Rejected') ? 'background:#dc3545; color:white;' : 
                                      'background:#ffc107; color:black;');
                    ?>
                      <tr style="transition:0.3s; border-radius:10px;">
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row->acc_name; ?></td>
                        <td><?php echo $row->account_number; ?></td>
                        <td><?php echo $row->acc_rates; ?>%</td>
                        <td><?php echo $row->acc_type; ?></td>
                        <td><?php echo $row->client_name; ?></td>
                        <td><?php echo $row->client_gender; ?></td>
                        <td><?php echo $row->client_dob; ?></td>
                        <td><?php echo $row->client_marital_status; ?></td>
                        <td><?php echo $row->client_father_name; ?></td>

                        <td><?php echo $row->client_aadhaar; ?></td>
                        <td><?php echo $row->client_pan; ?></td>
                        <td>
                          <span style="padding:5px 10px; border-radius:20px; <?php echo $statusClass; ?>" title="Current Status: <?php echo $row->acc_status; ?>">
                            <?php echo $row->acc_status; ?>
                          </span>
                        </td>
                        <td><?php echo date("d-M-Y", strtotime($dateOpened)); ?></td>
                      </tr>
                    <?php 
                        $cnt++; 
                      }
                    } else {
                      echo "<tr><td colspan='10' style='text-align:center; color:red;'>Error fetching data</td></tr>";
                    }
                    ?>
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

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="plugins/datatables/jquery.dataTables.js"></script>
  <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script>
    $(function() {
      $("#example1").DataTable();
    });
  </script>

  <style>
    body {
      background: #f4f6f9;
    }
    table tr:hover {
      background: rgba(0, 123, 255, 0.1);
      transition: 0.3s;
    }
    .card {
      transition: 0.3s;
    }
    .card:hover {
      box-shadow: 0px 6px 12px rgba(0,0,0,0.3);
    }
  </style>

<script>
  setTimeout(function() {
    location.reload();
  }, 10000); // 10 sec me refresh
</script>


</body>
</html>
