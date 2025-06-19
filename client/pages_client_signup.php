<?php
session_start();
include('conf/config.php');

//register new account
if (isset($_POST['create_account'])) {
  //Register  Client
  $name = $_POST['name'];
  $national_id = $_POST['national_id'];
  $client_number = $_POST['client_number'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $password = sha1(md5($_POST['password']));
  $address  = $_POST['address'];

  //$profile_pic  = $_FILES["profile_pic"]["name"];
  //move_uploaded_file($_FILES["profile_pic"]["tmp_name"],"dist/img/".$_FILES["profile_pic"]["name"]);

  //Insert Captured information to a database table
  $query = "INSERT INTO iB_clients (name, national_id, client_number, phone, email, password, address) VALUES (?,?,?,?,?,?,?)";
  $stmt = $mysqli->prepare($query);
  //bind paramaters
  $rc = $stmt->bind_param('sssssss', $name, $national_id, $client_number, $phone, $email, $password, $address);
  $stmt->execute();

  //declare a varible which will be passed to alert function
  if ($stmt) {
    $success = "Account Created";
  } else {
    $err = "Please Try Again Or Try Later";
  }
}

/* Persisit System Settings On Brand */
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute(); //ok
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>iBank | Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- FontAwesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #1e3c72, #2a5298);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      color: #333;
    }
    .container {
      display: flex;
      width: 760px;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      overflow: hidden;
    }
    .left-panel {
      background: #003366;
      color: #fff;
      padding: 28px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: start;
    }
    .left-panel h3 {
      margin-bottom: 18px;
      font-size: 20px;
      font-weight: 600;
    }
    .feature {
      display: flex;
      align-items: center;
      margin-bottom: 12px;
      background: rgba(255, 255, 255, 0.1);
      padding: 8px 12px;
      border-radius: 6px;
      font-size: 13px;
      transition: transform 0.3s ease;
    }
    .feature i {
      color: #00ffea;
      margin-right: 10px;
      font-size: 15px;
    }
    .feature:hover {
      transform: scale(1.05);
      background: rgba(255, 255, 255, 0.15);
    }

    .form-panel {
      flex: 1.4;
      padding: 30px 35px;
    }
    .form-panel h2 {
      font-size: 22px;
      margin-bottom: 5px;
      color: #003366;
    }
    .form-panel p {
      font-size: 13px;
      margin-bottom: 20px;
      color: #666;
    }
.form-group {
  margin-bottom: 16px;
  position: relative;
}
    .form-group label {
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 4px;
      display: block;
    }
.form-group i {
  position: absolute;
  right: 12px;
  top: 65%;
  transform: translateY(-50%);
  color: #888;
  font-size: 14px;
  transition: color 0.3s ease;
}
.form-group input {
  width: 100%;
  padding: 10px 40px 10px 12px; /* add right padding for icon space */
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 13px;
  transition: all 0.3s ease;
}
.form-group input:focus + i {
  color: #0052D4;
}
    .form-group input:focus {
      border-color: #0052d4;
      box-shadow: 0 0 5px rgba(0, 82, 212, 0.3);
    }
    .form-group input:hover {
      border-color: #0040a0;
    }

    .submit-btn {
      background: #0052D4;
      color: #fff;
      padding: 10px;
      border: none;
      width: 100%;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      font-size: 14px;
      transition: background 0.3s ease;
    }
    .submit-btn:hover {
      background: #003caa;
    }
    .form-panel p a {
      color: #0052D4;
      text-decoration: none;
    }
    .form-panel p a:hover {
      text-decoration: underline;
    }

    @media screen and (max-width: 768px) {
      .container {
        flex-direction: column;
        width: 90%;
      }
      .left-panel {
        padding: 20px;
        align-items: center;
        text-align: center;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="left-panel">
    <h3>Welcome to iBank</h3>
    <div class="feature"><i class="fas fa-lock"></i> Secure & Private</div>
    <div class="feature"><i class="fas fa-bolt"></i> Fast Access</div>
    <div class="feature"><i class="fas fa-globe"></i> Anytime, Anywhere</div>
  </div>
  <div class="form-panel">
    <h2>Create Account</h2>
    <p>Sign Up To Use Our iBanking System.</p>
    <form method="post">
      <div class="form-group">
        <label>Full Name</label>
        <i class="fas fa-user"></i>
        <input type="text" name="name" required>
      </div>
      <div class="form-group">
        <label>National ID</label>
        <i class="fas fa-id-card"></i>
        <input type="text" name="national_id" required>
      </div>
      <div class="form-group" style="display:none;">
        <?php
        $length = 4;
        $_Number = substr(str_shuffle('0123456789'), 1, $length);
        ?>
        <input type="text" name="client_number" value="iBank-CLIENT-<?php echo $_Number; ?>">
      </div>
      <div class="form-group">
        <label>Phone Number</label>
        <i class="fas fa-phone"></i>
        <input type="text" name="phone" required>
      </div>
      <div class="form-group">
        <label>Address</label>
        <i class="fas fa-map-marker-alt"></i>
        <input type="text" name="address" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <i class="fas fa-lock"></i>
        <input type="password" name="password" required>
      </div>
      <button class="submit-btn" name="create_account" type="submit">Sign Up</button>
      <p style="margin-top:12px; font-size:13px;">Already have an account? <a href="pages_client_index.php">Login</a></p>
    </form>
  </div>
</div>

</body>
</html>


<?php
} ?>