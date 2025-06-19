<style>
  .staff-header {
    background-color: #ff821a;
    color: white;
  }

  .staff-header .nav-link {
    color: #ffffff !important;
  }

  .staff-header .nav-link:hover {
    color: #ffe484 !important;
    transition: 0.3s;
  }

  .fa-bell {
    font-size: 1.2rem;
  }

  .staff-badge {
    background-color: #faff0c;
    color: black;
    font-size: 0.85rem;
    animation: bellPulse 1.5s infinite;
  }

  .dropdown-menu-lg {
    max-height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #aaa #f5f5f5;
  }

  .dropdown-menu-lg::-webkit-scrollbar {
    width: 6px;
  }

  .dropdown-menu-lg::-webkit-scrollbar-thumb {
    background-color: #aaa;
    border-radius: 10px;
  }

  .dropdown-item:hover {
    background-color: #f2f2f2;
    color: #6f42c1;
  }

.notif-box {
  border-radius: 10px;
  padding: 10px 15px;
  color: #fff;
  margin-bottom: 8px;
  transition: background-color 0.3s, transform 0.3s;
}

.notif-box:hover {
  transform: scale(1.02);
  opacity: 0.95;
}

/* Success and danger remain unchanged */
.notif-success {
  background-color: #8ceb22; /* teal */
}

.notif-danger {
  background-color: #fd7e14; /* orange */
}

/* New distinct colors (not red, blue, sky, orange) */
.notif-withdrawal {
  background-color: #8e44ad; /* purple (70% deep) */
}

.notif-transfer {
  background-color: #16a085; /* dark aqua green */
}

.notif-deposit {
  background-color: #b7950b; /* mustard gold */
}

/* Optional info fallback */
.notif-info {
  background-color: #6c757d; /* muted gray */
}


  @keyframes bellPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
  }
</style>


<nav class="main-header navbar navbar-expand staff-header">
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
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM iB_notifications WHERE notification_for = 'staff'");
        $stmt->execute();
        $stmt->bind_result($ntf_count);
        $stmt->fetch();
        $stmt->close();
        ?>
        <span class="badge staff-badge navbar-badge"><?php echo $ntf_count; ?></span>
      </a>

      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php
        $stmt = $mysqli->prepare("SELECT * FROM iB_notifications WHERE notification_for = 'staff' ORDER BY created_at DESC");
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
} elseif (strpos($message, 'withdraw') !== false) {
  $notif_class = 'notif-withdrawal';
} elseif (strpos($message, 'transfer') !== false) {
  $notif_class = 'notif-transfer';
} elseif (strpos($message, 'deposit') !== false) {
  $notif_class = 'notif-deposit';
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
