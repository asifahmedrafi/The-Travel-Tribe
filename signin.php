<?php
session_start();
error_reporting(0);
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
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="banner-1">
        <div class="container">
            <h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">Sign In</h1>
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
                                    <h3 class="text-center">Sign In with your account </h3>
                                    <input type="text" name="email" id="email" placeholder="Enter your Email" required="">
                                    <input type="password" name="password" id="password" placeholder="Password" value="" required="">
                                    <h4><a href="forgot-password.php">Forgot Password</a></h4>

                                    <input type="submit" name="signin" value="SIGNIN">
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