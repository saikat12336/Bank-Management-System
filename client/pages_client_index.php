<?php
session_start();
include('conf/config.php'); // get configuration file

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details
    $stmt = $mysqli->prepare("SELECT client_id, password FROM iB_clients WHERE email=?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($client_id, $stored_password);
    $stmt->fetch();
    $stmt->close();

    // Check if user exists
    if ($client_id) {
        // 🔹 Check if the stored password is in old format (MD5/SHA1)
        if (strlen($stored_password) == 32 || strlen($stored_password) == 40) {
            // Old password format (MD5/SHA1) - Compare manually
            if ($stored_password === sha1(md5($password))) {
                // ✅ Convert old password to new hashed format
                $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $update_query = "UPDATE iB_clients SET password=? WHERE client_id=?";
                $update_stmt = $mysqli->prepare($update_query);
                $update_stmt->bind_param('si', $new_hashed_password, $client_id);
                $update_stmt->execute();
                $update_stmt->close();

                // Set new hashed password for login
                $stored_password = $new_hashed_password;
            } else {
                $err = "Access Denied. Please Check Your Credentials.";
            }
        }

        // ✅ Verify hashed password
        if (password_verify($password, $stored_password)) {
            $_SESSION['client_id'] = $client_id;
            header("Location: pages_dashboard.php");
            exit();
        } else {
            $err = "Access Denied. Please Check Your Credentials.";
        }
    } else {
        $err = "User not found!";
    }
}

/* Persist System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings`";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>iBank | Client Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- FontAwesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="icon" type="image/png" sizes="16x16" href="../admin/dist/img/<?php echo $auth->sys_logo; ?>">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #1e3c72, #2a5298);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-box {
      background: #ffffff;
      padding: 35px 40px;
      border-radius: 18px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
      width: 380px;
      transition: transform 0.3s ease-in-out;
    }

    .login-box:hover {
      transform: translateY(-5px);
    }

    .login-logo p {
      font-size: 24px;
      color: #003366;
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
    }

    .login-box-msg {
      text-align: center;
      margin-bottom: 20px;
      font-size: 14px;
      color: #444;
    }

    .form-group {
      position: relative;
      margin-bottom: 22px;
    }

    .form-group input {
      width: 100%;
      padding: 12px 42px 12px 14px;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      background: linear-gradient(to right, #f8f9fc, #e2e6f3);
      box-shadow: inset 2px 2px 6px rgba(0, 0, 0, 0.08),
                  inset -2px -2px 6px rgba(255, 255, 255, 0.8);
      transition: all 0.3s ease;
    }

    .form-group input:focus {
      outline: none;
      background: linear-gradient(to right, #e6f0ff, #d0e2ff);
      box-shadow: 0 0 6px #66aaff;
    }

    .form-group i {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #666;
    }

    .remember-me {
      display: flex;
      align-items: center;
      font-size: 13px;
      margin-bottom: 18px;
      color: #333;
    }

    .remember-me input {
      margin-right: 8px;
      transform: scale(1.2);
    }

    .login-btn {
      background: linear-gradient(to right, #00c6ff, #0072ff);
      color: #fff;
      border: none;
      width: 100%;
      padding: 13px;
      font-size: 15px;
      font-weight: bold;
      border-radius: 12px;
      cursor: pointer;
      box-shadow: 0 6px 12px rgba(0, 114, 255, 0.3);
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .login-btn:hover {
      background: linear-gradient(to right, #0095ff, #0055cc);
      transform: scale(1.02);
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
      font-size: 13px;
      color: #fff;
    }

    .register-link a {
      color: green;
      text-decoration: none;
      font-weight: 600;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <div class="login-logo">
      <p><?php echo $auth->sys_name; ?></p>
    </div>

    <p class="login-box-msg">Log In To Start Client Session</p>

    <?php if (isset($err)) { ?>
  <div style="background: #ffe0e0; color: #d10000; padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: bold;">
    <?php echo $err; ?>
  </div>
<?php } ?>

    <form method="post">
      <div class="form-group">
        <input type="email" name="email" placeholder="Email" required>
        <i class="fas fa-envelope"></i>
      </div>

      <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
        <i class="fas fa-lock"></i>
      </div>

      <div class="remember-me">
        <input type="checkbox" id="remember">
        <label for="remember">Remember Me</label>
      </div>

      <button type="submit" name="login" class="login-btn">Log In</button>
    </form>

    <div class="register-link">
      <p style="color:#666;">Don't have an account? <a href="pages_client_signup.php">Register Now</a></p>
    </div>
  </div>

</body>
</html>


<?php } ?>
