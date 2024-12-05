<?php
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE HTML>
<html>

<head>
	<title>Verified Succesfully </title>
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
	<!--//end-animate-->
</head>

<body>
	<?php include('includes/header.php'); ?>
	<div class="banner-1 ">
		<div class="container">
			<h1 class="wow zoomIn animated animated" data-wow-delay=".5s" style="visibility: visible; animation-delay: 0.5s; animation-name: zoomIn;">The Travel Tribe</h1>
		</div>
	</div>
	<!--- /banner-1 ---->
	<!--- contact ---->
	<div class="contact">
		<div class="container">
			<div class="col-md-10 contact-left">
				<?php
					if (isset($_GET['code'])) {
						$code = $_GET['code'];
					
						// Check if the code exists and if the user is not verified
						$sql = "SELECT * FROM tblusers WHERE Code = :code AND IsVerified = 0";
						$stmt = $dbh->prepare($sql);
						$stmt->execute([':code' => $code]);
					
						if ($stmt->rowCount() > 0) {
							// Update user status to verified
							$update_sql = "UPDATE tblusers SET IsVerified = 1 WHERE Code = :code";
							$update_stmt = $dbh->prepare($update_sql);
							$update_stmt->execute([':code' => $code]);

							echo '<h3>Email Verification Center</h3>';
							echo'<div class="con-top animated wow fadeInUp animated" data-wow-duration="1200ms" data-wow-delay="500ms" style="margin-bottom: 50px; visibility: visible; animation-duration: 1200ms; animation-delay: 500ms; animation-name: fadeInUp;">
								<h4>Email Verified Succesfully. Please Goto Login Page.</h4>
							</div>';
						} else {
							echo '<h3>Invalid Verification Link</h3>';
							echo'<div class="con-top animated wow fadeInUp animated" data-wow-duration="1200ms" data-wow-delay="500ms" style="margin-bottom: 50px; visibility: visible; animation-duration: 1200ms; animation-delay: 500ms; animation-name: fadeInUp;">
								<h4>Invalid or expired verification link.</h4>
							</div>';
						}
					} else {
						// Unauthorized access message
						echo "<h3>You are not authorized to access this page. Go back to <a href='index.php'>Dashboard</a></h3>";
					}
				?>
				<div class="clearfix"></div>
			</div>
		</div>
		<!--- /contact ---->
		<?php include('includes/footer.php'); ?>
		<!-- sign -->
		<?php include('includes/signup.php'); ?>
		<!-- signin -->
		<?php include('includes/signin.php'); ?>
		<!-- //signin -->
		<!-- write us -->
		<?php include('includes/write-us.php'); ?>
		<!-- //write us -->
	</div>
</body>

</html>