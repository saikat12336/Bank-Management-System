<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo 
    and load this 
    page with logged in user instance
    -->
  <?php
$client_id = $_SESSION['client_id'];

  // Fetch client info
  $ret = "SELECT * FROM  iB_clients  WHERE client_id = ? ";
  $stmt = $mysqli->prepare($ret);
  $stmt->bind_param('i', $client_id);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_object()) {

    // Fetch account status
    $acc_status = 'Pending'; // Default
    $acc_qry = "SELECT acc_status FROM iB_bankAccounts WHERE client_id = ? LIMIT 1";
    $acc_stmt = $mysqli->prepare($acc_qry);
    $acc_stmt->bind_param('i', $client_id);
    $acc_stmt->execute();
    $acc_stmt->bind_result($acc_status);
    $acc_stmt->fetch();
    $acc_stmt->close();

    //set automatically logged in user default image if they have not updated their pics
    if ($row->profile_pic == '') {
      $profile_picture = "<img src='../admin/dist/img/user_icon.png' class=' elevation-2' alt='User Image'>
                ";
    } else {
      $profile_picture = "<img src='../admin/dist/img/$row->profile_pic' class='elevation-2' alt='User Image'>
                ";
    }



    /* Persisit System Settings On Brand */
    $ret = "SELECT * FROM `iB_SystemSettings` ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute(); //ok
    $res = $stmt->get_result();
    while ($sys = $res->fetch_object()) {
  ?>

      <a href="pages_dashboard.php" class="brand-link">
        <img src="../admin/dist/img/<?php echo $sys->sys_logo;?>" alt="iBanking Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo $sys->sys_name;?></span>
      </a>

      <style>
  /* Apply glowing/3D color effect */
  .main-sidebar {
    background: #1e1e2f; /* Keep dark theme */
    box-shadow: inset -3px -3px 7px #2c2c3e, inset 3px 3px 7px #0e0e1a;
  }

  .main-sidebar .brand-link {
    background: linear-gradient(to right, #3a3f58, #2b2f44);
    box-shadow: 0 3px 5px rgba(0, 0, 0, 0.5);
  }

  .main-sidebar .nav-link {
    transition: all 0.3s ease;
    border-radius: 10px;
    margin: 2px 5px;
    color: #c2c6dc !important;
  }

  .main-sidebar .nav-link:hover {
    background: linear-gradient(135deg, #4c70ff, #88aaff);
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
  }

  .nav-icon {
    color: #6d8ddf !important;
    text-shadow: 1px 1px 2px #000;
  }

  .nav-sidebar > .nav-item > .nav-link.active {
    background-color: #5a77ff;
    color: #fff;
    box-shadow: inset 2px 2px 6px #3541a0, inset -2px -2px 6px #748fff;
  }

  .nav-header {
    color: #888ca9;
    font-weight: bold;
    padding-left: 15px;
    text-shadow: 1px 1px 2px #000;
  }

  .user-panel .image img {
    border: 2px solid #444;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  .user-panel .info a {
    color: #d9dcff;
    font-weight: 600;
    text-shadow: 1px 1px 2px #000;
  }
</style>


      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <?php echo $profile_picture; ?>
          </div>
          <div class="info">
            <a href="pages_dashboard.php" class="d-block"><?php echo $row->name; ?></a>
          </div>
                  <style>
          .disabled-link {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
          }
        </style>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-item has-treeview">
              <a href="pages_dashboard.php" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard

                </p>
              </a>
            </li>
            <!-- ./DAshboard -->

            <!--Account -->
            <li class="nav-item">
              <a href="pages_account.php" class="nav-link">
                <i class="nav-icon fas fa-user-tie"></i>
                <p>
                  Account
                </p>
              </a>
            </li>
            <!-- ./Account-->

            <!--iBank Accounts-->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-briefcase"></i>
                <p>
                  iBank Accounts
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="pages_open_acc.php" class="nav-link">
                    <i class="fas fa-lock-open nav-icon"></i>
                    <p>Open iBank Acc</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="pages_manage_acc_openings.php" class="nav-link">
                    <i class="fas fa-cog nav-icon"></i>
                    <p>My iBank Accounts</p>
                  </a>
                </li>
              </ul>
            </li>
            <!--./ iBank Acounts-->

 <!-- Finances -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                <i class="nav-icon fas fa-dollar-sign"></i>
                <p>Finances<i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="pages_transfers.php" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                    <i class="fas fa-random nav-icon"></i>
                    <p>Transfers</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="pages_view_client_bank_acc.php" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                    <i class="fas fa-money-bill-alt nav-icon"></i>
                    <p>Balance Enquiries</p>
                  </a>
                </li>
              </ul>
            </li>
            <!-- ./Finances -->

                       <li class="nav-header">Advanced Modules</li>

            <!-- Transactions History -->
            <li class="nav-item">
              <a href="pages_transactions_engine.php" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                <i class="nav-icon fas fa-exchange-alt"></i>
                <p>Transactions History</p>
              </a>
            </li>

            <!-- Financial Reports -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>Financial Reports<i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="pages_financial_reporting_deposits.php" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                    <i class="fas fa-file-upload nav-icon"></i>
                    <p>Deposits</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="pages_financial_reporting_withdrawals.php" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                    <i class="fas fa-cart-arrow-down nav-icon"></i>
                    <p>Withdrawals</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="pages_financial_reporting_transfers.php" class="nav-link <?php echo ($acc_status == 'Approved') ? '' : 'disabled-link'; ?>">
                    <i class="fas fa-random nav-icon"></i>
                    <p>Transfers</p>
                  </a>
                </li>
              </ul>
            </li>
            <!-- ./ End financial Reporting-->

<!-- Log Out -->
<li class="nav-item">
  <a href="pages_logout.php" class="nav-link" style="color: #e63946;"> <!-- Deep red shade -->
    <i class="nav-icon fas fa-power-off" style="color: #c8eb0c !important;"></i>
    <p style="color: #e63946; font-weight: bold;">
      Log Out
    </p>
  </a>
</li>

            <!-- ./Log Out -->
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
</aside>
<?php
    }
  } ?>