<style>
/* Navbar 3D Style */
.navbar {
    background: linear-gradient(135deg, #007bff, #6610f2);
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    padding: 12px 20px;
    border-radius: 0 0 20px 20px;
}

/* Navbar Icon Hover */
.navbar-nav .nav-item .nav-link {
    color: white;
    font-size: 18px;
    transition: 0.3s ease-in-out;
}

.navbar-nav .nav-item .nav-link:hover {
    transform: scale(1.1);
    text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.8);
}

/* Right Align Notification Icon */
.navbar-nav.ml-auto {
    margin-left: auto !important;
}

/* Notification Badge */
#notificationCount {
    position: absolute;
    top: 5px;
    right: 5px;
    background: red;
    color: white;
    font-size: 12px;
    padding: 3px 7px;
    border-radius: 50%;
    box-shadow: 0px 0px 10px rgba(255, 0, 0, 0.6);
}

/* Notification Dropdown */
.dropdown-menu {
    width: 350px !important;
    max-height: 400px;
    overflow-y: auto;
    border-radius: 15px;
    box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.4);
    animation: fadeIn 0.3s ease-in-out;
    background: linear-gradient(135deg, #ffffff, #e0eaff);
    border: 2px solid #007bff;
}

/* Notification Item */
.dropdown-item {
    white-space: normal !important;
    padding: 12px 15px;
    border-radius: 10px;
    margin: 5px;
    background: linear-gradient(135deg, #d1ecf1, #f8f9fa);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: start;
    transition: 0.3s ease-in-out;
}

/* Hover Effect */
.dropdown-menu .dropdown-item:hover {
    background: linear-gradient(135deg, #b3e5fc, #ffffff);
    transform: scale(1.03);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Notification Icon */
.dropdown-item i {
    font-size: 18px;
    margin-right: 10px;
}

/* Notification Time */
.text-muted {
    font-size: 12px;
    font-style: italic;
    color: #555 !important;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0px); }
}

.delete-icon {
    color: red;
    cursor: pointer;
    font-size: 16px;
    margin-left: 10px;
    transition: transform 0.3s ease;
}

.delete-icon:hover {
    color: darkred;
    transform: scale(1.3);
}
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Move Notifications to Right -->
    <ul class="navbar-nav ml-auto">
        <!-- Notification Icon in Navbar -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-bell"></i>
                <span class="badge badge-danger" id="notificationCount"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header" style="font-weight: bold; font-size: 16px; color: #343a40;">Recent Notifications</span>
                <div class="dropdown-divider"></div>

                <?php
                include('./conf/config.php');
                function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'Just now';
    elseif ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    elseif ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    elseif ($diff < 604800) return floor($diff / 86400) . ' days ago';
    else return date("d-M-Y H:i", $time);
}


                 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_notification_id'])) {
                    $del_id = intval($_POST['delete_notification_id']);
                    $client_id = $_SESSION['client_id'];
                    $stmtDel = $mysqli->prepare("DELETE FROM ib_notifications WHERE notification_id = ? AND client_id = ?");
                    $stmtDel->bind_param("ii", $del_id, $client_id);
                    $stmtDel->execute();
                    exit; // stop further output if it's an AJAX call
                }

                if (isset($_SESSION['client_id'])) {
                    $client_id = $_SESSION['client_id'];

$ret = "SELECT * FROM ib_notifications 
        WHERE client_id = ? 
        AND notification_for = 'client'
        ORDER BY created_at DESC 
        LIMIT 5";

                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param("i", $client_id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $count = 0;

while ($row = $res->fetch_object()) {
    echo "
    <div class='dropdown-item' id='notif-{$row->notification_id}'>
        <div>
            <i class='fas fa-info-circle text-info'></i> 
            <span>{$row->notification_details}</span>
            <div class='text-muted'>" . timeAgo($row->created_at) . "</div>
        </div>
        <i class='fas fa-trash-alt delete-icon' title='Delete' onclick='deleteNotification({$row->notification_id})'></i>
    </div>";
    $count++;
}


                    if ($count == 0) {
                        echo "<div class='dropdown-item text-center'>No New Notifications</div>";
                    }

                    $stmt->close();
                } else {
                    echo "<div class='dropdown-item text-center text-danger'>Not Logged In</div>";
                }
                ?>
            </div>
        </li>
    </ul>

<script>
    document.getElementById("notificationCount").innerText = "<?php echo isset($count) ? $count : 0; ?>";

    function deleteNotification(id) {
        if (confirm("Are you sure you want to delete this notification?")) {
            const formData = new FormData();
            formData.append("delete_notification_id", id);

            fetch("", {
                method: "POST",
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    const notifItem = document.getElementById("notif-" + id);
                    if (notifItem) notifItem.remove();

                    // Update count visually
                    let countSpan = document.getElementById("notificationCount");
                    let currentCount = parseInt(countSpan.innerText);
                    countSpan.innerText = currentCount > 0 ? currentCount - 1 : 0;

                    // ✅ Check if all notifications are deleted
                    const remainingNotifs = document.querySelectorAll('.dropdown-item[id^="notif-"]');
                    if (remainingNotifs.length === 0) {
                        const dropdownMenu = document.querySelector('.dropdown-menu');
                        const noNotif = document.createElement('div');
                        noNotif.className = 'dropdown-item text-center';
                        noNotif.innerText = 'No New Notifications';
                        dropdownMenu.appendChild(noNotif);
                    }
                }
            })
            .catch(err => {
                console.error("Error deleting notification:", err);
                alert("Something went wrong while deleting.");
            });
        }
    }
</script>

</nav>
