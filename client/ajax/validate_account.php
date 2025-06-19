<?php
include('../conf/config.php'); // Include your database connection file

if (isset($_GET['account_number'])) {
    $account_number = $_GET['account_number'];

    // Check if the account number exists and is approved by the admin
    $query = "SELECT account_number, acc_status FROM iB_bankAccounts WHERE account_number = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $account_number);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($acc_no, $acc_status);
    
    if ($stmt->num_rows > 0) {
        // Fetch the account status
        $stmt->fetch();

if (strtolower(trim($acc_status)) == 'approved') {
    echo 'valid';
} else {
    echo 'not_approved';
}

    } else {
        echo 'invalid';  // Account number does not exist
    }

    $stmt->close();
} else {
    echo 'invalid';  // No account number provided
}
?>
