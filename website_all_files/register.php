<?php


require_once( 'includes/dbh.inc.php' );


//print_r($_POST);
if ( isset( $_POST ) & !empty( $_POST ) ) {
	$username = mysqli_real_escape_string( $connection, $_POST[ "username" ] );
	$name = mysqli_real_escape_string( $connection, $_POST[ "name" ] );
	$email = mysqli_real_escape_string( $connection, $_POST[ "email" ] );
	$studentid = mysqli_real_escape_string( $connection, $_POST[ "studentid" ] );
	$password = md5( $_POST[ "password" ] );
	$passwordAgain = md5( $_POST[ "passwordAgain" ] );

	if ( $password == $passwordAgain ) {


		$usernamesql = "SELECT * FROM `login` WHERE username = '$username'";
		$usernameres = mysqli_query( $connection, $usernamesql );
		$count = mysqli_num_rows( $usernameres );
		if ( $count == 1 ) {
			$fmsg = "Username Already Exists! ";
			$error = "true";

		}

		$emailsql = "SELECT * FROM `login` WHERE contact = '$email'";
		$emailsqlres = mysqli_query( $connection, $emailsql );
		$count = mysqli_num_rows( $emailsqlres );

		if ( $count == 1 ) {
			$fmsg = "Email Already Exists! ";
			$error = "true";

		}


		$studentidsql = "SELECT * FROM `login` WHERE studentid = '$studentid'";
		$studentidsqlres = mysqli_query( $connection, $studentidsql );
		$count = mysqli_num_rows( $studentidsqlres );

		if ( $count == 1 ) {
			$fmsg = "Student ID Already Exists ya";
			$error = "true";

		}

		$studentidsql = "SELECT * FROM `students` WHERE studentid = '$studentid'";
		$studentidsqlres = mysqli_query( $connection, $studentidsql );
		$count = mysqli_num_rows( $studentidsqlres );

		if ( $count == 1 ) {
			//Note to self: get rid of all the code that requires students to exist in database prior to registering

			// $csmsg .= "Student exists in database... Attempting to create account.";
			// $studentexists = "true";

		} else {
		    // $error = "true";

		}

		if ( $error != "true"
	/*and $studentexists == "true" */) {

			$sql = "INSERT INTO login (username, contact, password, studentid) VALUES ('$username', '$email', '$password', '$studentid');";
			$year = substr($studentid,0,4);
			$sql2 = "INSERT INTO students (studentid, name, contact, year, position) VALUES ('$studentid', '$name', '$email', '$year', 'Member');";

			//echo $sql;



			$result = mysqli_query($connection, $sql);
			$result2 = mysqli_query($connection, $sql2);
			if ( $result && $result2) {
				$smsg = "Success! You have now taken a step forward in life! *If you are a leader, ask the webdev people to give you leader rights.";

				$to = $email;
				$subject = "Thank you for registering to this!";
				$message = "Hey! Thanks for registering to Student Media Services. Make sure to set this email to not spam in order to get all the nessesary emails.";

				$headers = 'From: SMS <SMS@database.com>' . PHP_EOL .
		    'Reply-To: SMS <SMS@database.com>' . PHP_EOL .
		    'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";


//mail($to, $subject, $message, $headers);
//echo "<br>email to Project Manager sent";


			} else {
				// $fmsg = "User Registration error <br><br> Contact the Project Manager of SMS";


			}
		} else {

		// $fmsg = "Something went wrong! Make sure that your is already registered inside the database before you create your account. If any further issues persist, contact the Project Manager of SMS. ";
}


	} else {
		$fmsg = "Password Does Not Match";
	}


}


?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

<link rel="stylesheet" href="css/loginstyleold.css">



<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Register!</title>
</head>

<body>

	<?php if(isset($smsg)){ ?>
	<div class="alert alert-success" role="alert" style="margin-top: 20px;">
		<?php echo $smsg; ?> </div>
	<?php } ?>

	<?php if(isset($csmsg)){ ?>
	<div class="alert alert-success" role="alert" style="margin-top: 20px;">
		<?php echo $csmsg; ?> </div>
	<?php } ?>

	<?php if(isset($fmsg)){ ?>
	<div class="alert alert-danger" role="alert" style="margin-top: 20px;">
		<?php echo $fmsg; ?> </div>
	<?php } ?>





	<div class="login-container">
		<section class="login" id="login">


			<form class="login-form" action="#" method="post">
				<input type="text" name="username" class="login-input" placeholder="Username" maxlength="100" required/>
				<input type="text" name="name" class="login-input" placeholder="Full Name" maxlength="100" required/>
				<input type="password" name="password" id="input Password" class="login-input" placeholder="Password" maxlength="100" required/>
				<input type="email" name="email" id="inputEmail" class="login-input" placeholder="Email address" maxlength="100" required/>
				<input type="number" name="studentid" class="login-input" placeholder="Student ID (just the numbers. i.e. 2019108)" maxlength="100" required/>

				<input type="password" name="passwordAgain" id="input Password" class="login-input" maxlength="100" placeholder="Password Again" required>
				<button class="btn btn-success">create</button>

			</form>
		</section>
	</div>


	<p class="message">Already registered? <a href="login.php">Sign In</a>
	</p>


	<script src="js/loginindex.js"></script>

</html>
