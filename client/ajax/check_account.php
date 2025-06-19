<?php
include('../conf/config.php');

if (isset($_GET['acc_no'])) {
    $acc_no = $_GET['acc_no'];

    $stmt = $mysqli->prepare("
        SELECT acc_name, client_name
        FROM ib_bankaccounts
        WHERE account_number = ? AND acc_status = 'Approved'
    ");
    $stmt->bind_param("s", $acc_no);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($acc_name, $client_name);
        $stmt->fetch();
        echo json_encode([
            'status' => 'valid',
            'account_name' => $acc_name,
            'client_name' => $client_name
        ]);
    } else {
        echo json_encode([
            'status' => 'invalid',
            'message' => 'Account number not approved or does not exist.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No account number provided.'
    ]);
}
?>
