<?php
session_start();
include('conf/config.php');

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = sha1(md5($_POST['password'])); //double encrypt to increase security
  $stmt = $mysqli->prepare("SELECT email, password, staff_id  FROM iB_staff  WHERE email=? AND password=?"); //sql to log in staff
  $stmt->bind_param('ss', $email, $password); //bind fetched parameters
  $stmt->execute(); //execute bind
  $stmt->bind_result($email, $password, $staff_id); //bind result
  $rs = $stmt->fetch();
  $_SESSION['staff_id'] = $staff_id;
  if ($rs) { //if its sucessfull
    header("location:pages_dashboard.php");
  } else {
    $err = "Access Denied Please Check Your Credentials";
  }
}
?>

<?php
// Load system settings (branding)
$ret = "SELECT * FROM `iB_SystemSettings`";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
$auth = $res->fetch_object();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <meta charset="utf-8">
  <title>Staff Login | <?php echo $auth->sys_name; ?></title>
  <?php include("dist/_partials/head.php"); ?>
</head>
<style>
  .login-box {
    margin: 5% auto;
    max-width: 450px;
  }

  .login-logo p {
    font-size: 2.2rem;
    font-weight: bold;
    color: #0556ff;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
  }

  .card {
    border-radius: 25px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.35);
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid #ddd;
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
  }

  .login-card-body {
    padding: 2rem;
    border-radius: 25px;
    background: linear-gradient(to bottom right, #ffffff, #f7f9fc);
  }

  .form-control {
    border-radius: 30px;
    padding: 0.75rem 1.25rem;
    transition: all 0.3s ease-in-out;
    border: 1px solid #ced4da;
  }

  .form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
    background-color: #fff;
  }

  .input-group-text {
    background-color: #28a745;
    border: none;
    border-radius: 0 30px 30px 0;
    color: #fff;
  }

  .btn-success {
    border-radius: 30px;
    padding: 0.6rem 1.2rem;
    transition: all 0.3s ease-in-out;
    font-weight: bold;
  }

  .btn-success:hover {
    background-color: #218838;
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(33, 136, 56, 0.3);
  }

  .icheck-primary label {
    color: #333;
    font-size: 0.95rem;
  }

  .login-box-msg {
    font-weight: 600;
    font-size: 1.1rem;
    color: #444;
  }
</style>

<body class="hold-transition login-page" style=" background: linear-gradient(to right, #e0f7fa, #e1f5fe);

    background-attachment: fixed;
    background-repeat: no-repeat;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

  <div class="login-box">
    <div class="login-logo">
      <p><?php echo $auth->sys_name; ?></p>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Log In To Start Staff Session</p>

        <form method="post">
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>

          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">Remember Me</label>
              </div>
            </div>

            <div class="col-4">
              <button type="submit" name="login" class="btn btn-success btn-block">Log In</button>
            </div>
          </div>
        </form>

        <!-- Optional Password Reset -->
        <!--
        <p class="mb-1">
          <a href="pages_reset_pwd.php">I forgot my password</a>
        </p>
        -->

      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE -->
  <script src="dist/js/adminlte.min.js"></script>

</body>
</html>
