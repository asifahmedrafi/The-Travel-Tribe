<?php
session_start();
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include('includes/config.php');

function sendOtpEmail($userEmail, $userName, $otpCode) {
  $mail = new PHPMailer(true);

  try {
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'rafiahmed221@gmail.com';
      $mail->Password   = 'onoevbinmhpiuchl';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;

      $mail->setFrom('rafiahmed221@gmail.com', 'The Travel Tribe');
      $mail->addAddress($userEmail, $userName);

      $mail->isHTML(true);
      $mail->Subject = 'Your Login OTP Code';
      $mail->Body    = "
          <h1>Login Verification</h1>
          <p>Hello $userName,</p>
          <p>Your OTP code for login is:</p>
          <h2>$otpCode</h2>
          <p>Please use this code to complete your login.</p>
          <p>If you did not request this, please ignore this email.</p>
      ";

      $mail->send();
  } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}


if (isset($_POST['signin'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $encrypt_password = md5($_POST['password']);

  $otpCode = rand(100000, 999999);

  $sql = "SELECT FullName, EmailId, Password, IsVerified FROM tblusers WHERE EmailId=:email and Password=:password";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':password', $encrypt_password, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetch(PDO::FETCH_OBJ);

  if ($results) {
      if ($results->IsVerified == 1) {
        $userName = $results->FullName;

        $update_sql = "UPDATE tblusers SET VerificationCode = :otp WHERE EmailId = :email";
        $update_stmt = $dbh->prepare($update_sql);
        $update_stmt->execute([':otp' => $otpCode, ':email' => $email]);

        sendOtpEmail($email, $userName, $otpCode);

      } else {
        echo "<script>alert('Email Verification First Thne Try To Login.');</script>";
      }
  } else {
      echo "<script>alert('Invalid Details');</script>";
  }
} else if(isset($_POST['otp_verification'])) {

  $email = $_POST['email'];
  $otp = $_POST['otp'];
  $password = md5($_POST['password']);

  $sql = "SELECT EmailId, Password, IsVerified FROM tblusers WHERE EmailId=:email and Password=:password and VerificationCode=:otp";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':password', $password, PDO::PARAM_STR);
  $query->bindParam(':otp', $otp, PDO::PARAM_STR);
  $query->execute();
  $results = $query->fetch(PDO::FETCH_OBJ);

  if ($results) {
    $_SESSION['login'] = $_POST['email'];
    header("location:package-list.php");
  } else {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = "Wrong OTP";
  }
} else {
  header("location:signin.php");
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>The Travel Tribe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <script type="applijewelleryion/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,700,600' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- Custom Theme files -->
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--animate-->
    <link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
    <script src="js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <?php if (isset($error)) { ?>
      <script>
        setTimeout(function() {
          alert("<?php echo $error; ?>!");
        }, 100);
      </script>
    <?php } ?>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="banner-1">
        <div class="container">
            <h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;"> OTP Verification</h1>
        </div>
    </div>
    <div class="signin" style="padding: 30px 0px;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="login-grids">
                        <div class="login">
                            <div class="login-right">
                                <form method="POST" action="otp-verification.php">
                                    <h3 class="text-center">Please Enter Your OTP Verification Code.</h3>
                                    <input type="hidden" name="email" id="email" value="<?php echo $email; ?>" required="">
                                    <input type="hidden" name="password" id="password" value="<?php echo $password; ?>" required="">
                                    <input class="form-control" name="otp" type="number" placeholder="OTP" autocomplete="off">
          
                                    <input type="submit" name="otp_verification" value="Submit">
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> or <a href="page.php?type=privacy">Privacy Policy</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <?php include('includes/write-us.php'); ?>
</body>

</html>