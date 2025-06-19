<?php
function sendSMS($phone, $message) {
    $api_key = "2sJ6cloqfZGTXdI5yHwRSEPYCU7K4mhW9gkjMNVv8tQbLDzanBv1wQdNJrzp405xMsVCqjLye7ItYlKf";  // Replace with actual Fast2SMS API key
    $sender_id = "FSTSMS";
    $route = "v3";  // "v3" for transactional
    $language = "english";

    // Convert data to URL-encoded format
    $postData = http_build_query(array(
        "sender_id" => $sender_id,
        "message" => $message,
        "language" => $language,
        "route" => $route,
        "numbers" => $phone,
    ));

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulk",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => array(
            "authorization: $api_key",
            "Content-Type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    
    if ($response === false) {
        error_log("SMS API Error: " . curl_error($curl));
    }

    curl_close($curl);

    return $response;
}
?>
