<style>
  /* Navbar background */
  .main-header {
    background: linear-gradient(45deg, #1e3c72, #2a5298);
    color: white;
  }

  .main-header .nav-link {
    color: #ffffff !important;
  }

  .main-header .nav-link:hover {
    color: #ffd700 !important;
    transition: 0.3s;
  }

  /* Notification Bell Badge */
  .navbar-badge {
    background-color: #dc3545;
    font-size: 0.75rem;
    animation: pulse 1.5s infinite;
  }

  /* Notification Box */
  .dropdown-menu-lg {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #888 #e0e0e0;
  }

  .dropdown-menu-lg::-webkit-scrollbar {
    width: 6px;
  }

  .dropdown-menu-lg::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 10px;
  }

  .dropdown-menu-lg::-webkit-scrollbar-track {
    background-color: #f1f1f1;
  }

  .dropdown-item:hover {
    background-color: #f8f9fa;
    color: #0056b3;
  }

  .dropdown-footer:hover {
    color: #ff4c4c !important;
  }

  .notif-box {
    border-radius: 10px;
    padding: 10px 15px;
    color: #fff;
    margin-bottom: 8px;
  }

  .notif-info {
    background-color: #17a2b8;
  }

  .notif-success {
    background-color: #28a745;
  }

  .notif-danger {
    background-color: #dc3545;
  }

  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
  }
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <!-- Notification Bell -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <?php
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM iB_notifications WHERE notification_for = 'admin'");
        $stmt->execute();
        $stmt->bind_result($ntf_count);
        $stmt->fetch();
        $stmt->close();
        ?>
        <span class="badge badge-danger navbar-badge"><?php echo $ntf_count; ?></span>
      </a>

      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php
        $stmt = $mysqli->prepare("SELECT * FROM iB_notifications WHERE notification_for = 'admin' ORDER BY created_at DESC");
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
          while ($row = $res->fetch_object()) {
            $message = strtolower($row->notification_details);
            $notif_class = 'notif-info';
            if (strpos($message, 'approved') !== false || strpos($message, 'success') !== false) {
              $notif_class = 'notif-success';
            } elseif (strpos($message, 'rejected') !== false || strpos($message, 'failed') !== false || strpos($message, 'error') !== false) {
              $notif_class = 'notif-danger';
            }
        ?>
            <div class="dropdown-item">
              <div class="notif-box <?php echo $notif_class; ?>">
                <p class="text-sm font-weight-bold mb-1"><?php echo $row->notification_details; ?></p>
                <p class="text-sm text-white-50 mb-0">
                  <i class="far fa-clock mr-1"></i>
                  <?php echo date("d-M-Y :: h:i A", strtotime($row->created_at)); ?>
                </p>
              </div>
              <a href="pages_dashboard.php?Clear_Notifications=<?php echo $row->notification_id; ?>" class="dropdown-item dropdown-footer text-danger font-weight-bold">
                <i class="fas fa-trash-alt mr-1"></i> Clear Notification
              </a>
              <div class="dropdown-divider"></div>
            </div>
        <?php
          }
        } else {
          echo '<span class="dropdown-item text-muted">No new notifications</span>';
        }
        $stmt->close();
        ?>
      </div>
    </li>
  </ul>
</nav>
