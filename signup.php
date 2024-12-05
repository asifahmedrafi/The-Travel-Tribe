<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendVerificationEmail($userEmail, $userName, $verificationCode) {
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
        $mail->Subject = 'Verify Your Email Address';
        $verificationLink = "http://localhost/tourism-management-system/verify.php?code=$verificationCode";
        $mail->Body    = "
            <h1>Verify Your Email</h1>
            <p>Hi $userName,</p>
            <p>Thank you for registering. Please click the link below to verify your Email:</p>
            <a href='$verificationLink'>Verify Email</a>
            <p>If you did not register, please ignore this Email.</p>
        ";
        
        $mail->send();
        echo 'Verification email has been sent.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

error_reporting(0);
if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $mnumber = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation
    if (!preg_match("/(^[A-Z]([a-z]{1,})$)|(^[A-Z]([a-z]{1,})(\s|_)[A-Z]([a-z]{1,})$)/", $fname)) {
        die("Invalid name format.");
    }

    if (!preg_match("/^01[3-9][0-9]{8}$/", $mnumber)) {
        die("Invalid mobile number format.");
    }

    if (!preg_match("/([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})/", $email)) {
        die("Invalid email format.");
    }

    if (!preg_match("/^[A-Z](?=.*[!@#$%^&*()_+[\]{};':\"\\\\|,.<>\/?])(?=.*[0-9]).{7,}$/", $password)) {
        die("Password must start with a capital letter, contain at least one special character and one number.");
    }

    // Hash password
    $hashedPassword = md5($password);

	$code = md5(uniqid(rand(), true));
    $is_verified = 0;

	$sql = "INSERT INTO tblusers(FullName, MobileNumber, EmailId, Password, Code, IsVerified) VALUES(:fname, :mnumber, :email, :password, :code, :is_verified)";
	$query = $dbh->prepare($sql);
	$query->bindParam(':fname', $fname, PDO::PARAM_STR);
	$query->bindParam(':mnumber', $mnumber, PDO::PARAM_STR);
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
	$query->bindParam(':code', $code, PDO::PARAM_STR);
	$query->bindParam(':is_verified', $is_verified, PDO::PARAM_STR);
	$query->execute();
	
	$lastInsertId = $dbh->lastInsertId();
	if ($lastInsertId) {
		sendVerificationEmail($email, $fname, $code);
		$_SESSION['msg'] = "You are successfully registered. Please check your Email to verify.";
		header('location:thankyou.php');
	} else {
		$_SESSION['msg'] = "Something went wrong. Please try again.";
		header('location:thankyou.php');
	}
}
?>

<!--Javascript for check email availability-->
<script>
	function checkAvailability() {
		$("#loaderIcon").show();
		jQuery.ajax({
			url: "check_availability.php",
			data: 'emailid=' + $("#email").val(),
			type: "POST",
			success: function(data) {
				$("#user-availability-status").html(data);
				$("#loaderIcon").hide();
			},
			error: function() {}
		});
	}
</script>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<section>
				<div class="modal-body modal-spa">
					<div class="login-grids">
						<div class="login">
							<div class="login-right">
								<form name="signup" method="post">
									<h3>Create your account </h3>
									<input type="text" value="" placeholder="Full Name" name="fname" autocomplete="off" required="">
									<input type="text" value="" placeholder="Mobile number" maxlength="11" name="mobilenumber" autocomplete="off" required="">
									<input type="text" value="" placeholder="Email id" name="email" id="email" onBlur="checkAvailability()" autocomplete="off" required="">
									<span id="user-availability-status" style="font-size:12px;"></span>
									<input type="password" value="" placeholder="Password" name="password" required="">
									<input type="submit" name="submit" id="submit" value="CREATE ACCOUNT">
								</form>
							</div>
							<div class="clearfix"></div>
						</div>
						<p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> or <a href="page.php?type=privacy">Privacy Policy</a></p>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>