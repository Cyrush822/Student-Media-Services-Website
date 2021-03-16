<?php
include 'includes/dbh.inc.php';
include 'emailheader.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

//This is to record the IP of whoever visits this page for security purposes. We have concerns regarding people spamming us.

	$sql = "INSERT INTO ip_addresses (page_location, ip_address) VALUES ('request page', '$ip');";
	$result = mysqli_query( $connection, $sql );

	//echo $sql;
	if ( $result ) {
	   // echo "its suppose to be working";
	}

function create_days_dropdown($a){
  if($a <= 31){
    echo "<option value=".$a.">".$a."</option>";
    create_days_dropdown($a+1);
  }
}

function create_years_dropdown($a){
  if($a <= 2030){
    echo "<option value=".$a.">".$a."</option>";
    create_years_dropdown($a+1);
  }
}









if ( isset( $_POST ) & !empty( $_POST ) ) {

	$name = mysqli_real_escape_string( $connection, $_POST[ "name" ] );
	$requestee = mysqli_real_escape_string( $connection, $_POST[ "requestee" ] );
	$contact = mysqli_real_escape_string( $connection, $_POST[ "contact" ] );
	$description = mysqli_real_escape_string( $connection, $_POST[ "description" ] );
// 	$due_date = mysqli_real_escape_string( $connection, $_POST[ "datetime_due" ] );
	$goal = mysqli_real_escape_string( $connection, $_POST[ "goal" ] );
	$keep_raw = mysqli_real_escape_string( $connection, $_POST[ "keep_raw" ] );

	//creating due date
	$month = mysqli_real_escape_string( $connection, $_POST[ "month" ] );
$day = mysqli_real_escape_string( $connection, $_POST[ "day" ] );
$year = mysqli_real_escape_string( $connection, $_POST[ "year" ] );



$due_date = $year . "-" . $month . "-" . $day;
// echo $date;

	//Inserting the data into the projects...
	$sql = "INSERT INTO requests (request_name, requestor_name, requestor_contact, request_description, datetime_due, request_goal, keep_raw, ip_address) VALUES ('$name', '$requestee', '$contact' , '$description' , '$due_date' , '$goal' , '$keep_raw', '$ip');";

	//echo $sql;


	$result = mysqli_query( $connection, $sql );

	if ( $result ) {
		$smsg = "Request successfully sent!";


		$to = $project_manager_email;
		$to3 = $organizer_email;
		$to4 = $organizer_email2;
		$to5 = $supervisor_email;
		$subject = "SMS Request Submission - '$name'";
		$message = "<h2>Someone has submitted a request!</h2>
		<p>
		<strong>Name of Project: </strong>'$name'
		<br>
		<br>
		<strong>Requestor: </strong> '$requestee'
		<br>
		<br>
		<strong>Contact: </strong>'$contact'
		<br>
		<br>
		<strong>Description: </strong> '$description'
		<br>
		<br>
		<strong>Due Date: </strong>'$due_date'
		<br>
		<br>
		<strong>Goal: </strong>'$goal'
		<br>
		<br>
		<strong>(If Documentation) Keep raw footage?: </strong>'$keep_raw'
		<br>

		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

		$headers = 'From: SMS Network <SMS@database.com>' . PHP_EOL .
		'Reply-To: SMS Network <SMS@database.com>' . PHP_EOL .
		'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

		//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');
    $mail->addAddress($to3, 'Recipient');
    $mail->addAddress($to4, 'Recipient');   // Add a recipient
    $mail->addAddress($to5, 'Recipient');   // Add a recipient
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
   // echo 'Message has been sent';
   echo 'Loading...just give me a second :)';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

		//echo "<br>email to requestee  sent";



		$to = $contact;
		$subject = "Thank you for submitting an SMS request!";
		$message = "You've submitted a project request regarding '$name'. <br><br>Thank you for submitting a project request! Your request is being carefully considered. We will get back to you shortly within the next week. Here is the information regarding the project that you've submitted:
		<br>	Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'

	<br>
	<br>
	If you want to make any changes, please email rinka at $project_manager_email.

		<br><br> Thanks,<br> $name_of_emailing_person";

		$headers = 'From: SMS Network <SMS@database.com>' . PHP_EOL .
		'Reply-To: SMS Network <SMS@database.com>' . PHP_EOL .
		'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

		//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

		//echo "<br>email to requestee  sent";




	} else {
		$fmsg = "Request failed to send";
	}


}




if ( isset( $_POST[ "visualdesign" ] ) ) {
	$to = $visual_design_email;
	$to2 = $visual_design_email2;
	$to3 = $visual_design_email3;
	$subject = "SMS Request to Visual Design";
	//$message = "Hi";
			$message = "<h2>Someone has submitted a request!</h2>

		<p>
		Hey leader(s) of visual graphics! We've received a request that might pertain to your field of expertise. This project request will be in sms.concordiashanghai.org waiting to be activated when you find someone who could lead it. If this project request has nothing to do with this SIG then I guess just contact the project manager saying s/he needs to fix something in his buggy code.
		<br>
		<br>
		Anyhow, here are the details of the request:
		Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'
		<br>
		(If Documentation) Keep raw footage?: '$keep_raw'
		<br>
		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

	$headers = 'From: SMS Database <SMS@database.com>' . PHP_EOL .
	'Reply-To: SMS Database <SMS@database.com>' . PHP_EOL .
	'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

	//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient
    $mail->addAddress($to2, 'Recipient');     // Add a recipient
    $mail->addAddress($to3, 'Recipient');     // Add a recipient
    $mail->addAddress($project_manager_email, 'Recipient');     // Add a recipient
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

	//echo "<br>email to requestee  sent";

}


if ( isset( $_POST[ "filmmaking" ] ) ) {
	$to = $filmmaking_email;
	$to2 = $filmmaking_email2;
	$to3 = $filmmaking_email3;
	$subject = "SMS Request to Filmmaking";
	//$message = "Hi";
			$message = "<h2>Someone has submitted a request!</h2>

		<p>
		Hey leader(s) of Filmmaking! We've received a request that might pertain to your field of expertise. This project request will be in sms.concordiashanghai.org waiting to be activated when you find someone who could lead it. If this project request has nothing to do with this SIG then I guess just contact the project manager saying s/he needs to fix something in his buggy code.
		<br>
		<br>
		Anyhow, here are the details of the request:
		Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'
		<br>
		(If Documentation) Keep raw footage?: '$keep_raw'
		<br>
		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

	$headers = 'From: SMS Database <SMS@database.com>' . PHP_EOL .
	'Reply-To: SMS Database <SMS@database.com>' . PHP_EOL .
	'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

	//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient
    $mail->addAddress($to2, 'Recipient');     // Add a recipient
    $mail->addAddress($to3, 'Recipient');     // Add a recipient
    $mail->addAddress($project_manager_email, 'Recipient');     // Add a recipient
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

	//echo "<br>email to requestee  sent";

}


if ( isset( $_POST[ "animation" ] ) ) {
	$to = $principia_email;
	$to2 = $principia_email2;
	$subject = "SMS Project Requestion for Principia";
	//$message = "Hi";
				$message = "<h2>Someone has submitted a request!</h2>

		<p>
		Hey leader(s) of animation/motiongraphics/principia! We've received a request that might pertain to your field of expertise. This project request will be in sms.concordiashanghai.org waiting to be activated when you find someone who could lead it. If this project request has nothing to do with this SIG then I guess just contact the project manager saying s/he needs to fix something in his buggy code.
		<br>
		<br>
		Anyhow, here are the details of the request:
		Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'
		<br>
		(If Documentation) Keep raw footage?: '$keep_raw'
		<br>
		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

	$headers = 'From: SMS Database <SMS@database.com>' . PHP_EOL .
	'Reply-To: SMS Database <SMS@database.com>' . PHP_EOL .
	'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

	//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient

    $mail->addAddress($to2, 'Recipient');     // Add a recipient
    $mail->addAddress($project_manager_email, 'Recipient');     // Add a

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

	//echo "<br>email to requestee  sent";

}

if ( isset( $_POST[ "livestream" ] ) ) {
	$to = $livestream_email;
	$to2 = $livestream_email2;

	$subject = "SMS Project Request for LIVESTREAAM";
	//$message = "Hi";
				$message = "<h2>Someone has submitted a request!</h2>

		<p>
		Hey leader(s) of livestream! We've received a request that might pertain to your field of expertise. This project request will be in sms.concordiashanghai.org waiting to be activated when you find someone who could lead it. If this project request has nothing to do with this SIG then I guess just contact the project manager saying s/he needs to fix something in his buggy code.
		<br>
		<br>
		Anyhow, here are the details of the request:
		Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'
		<br>
		(If Documentation) Keep raw footage?: '$keep_raw'
		<br>
		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

	$headers = 'From: SMS Database <SMS@database.com>' . PHP_EOL .
	'Reply-To: SMS Database <SMS@database.com>' . PHP_EOL .
	'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

	//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient
    $mail->addAddress($to2, 'Recipient');     // Add a recipient
    $mail->addAddress($project_manager_email, 'Recipient');     // Add a
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    //echo 'Im Loading...just give me a second';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

	//echo "<br>email to requestee  sent";

}

if ( isset( $_POST[ "documentation" ] ) ) {
	$to = $united_herald_email;
	$to2 = $united_herald_email2;
	$subject = "SMS Project Request for United Herald";
	//$message = "Hi";
				$message = "<h2>Someone has submitted a request!</h2>

		<p>
		Hey leader(s) of United Herald! We've received a request that might pertain to your field of expertise. This project request will be in sms.concordiashanghai.org waiting to be activated when you find someone who could lead it. If this project request has nothing to do with this SIG then I guess just contact the project manager saying s/he needs to fix something in his buggy code.
		<br>
		<br>
		Anyhow, here are the details of the request:
		Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'
		<br>
		(If Documentation) Keep raw footage?: '$keep_raw'
		<br>
		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

	$headers = 'From: SMS Database <SMS@database.com>' . PHP_EOL .
	'Reply-To: SMS Database <SMS@database.com>' . PHP_EOL .
	'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

	//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient
    $mail->addAddress($to2, 'Recipient');     // Add a recipient
    $mail->addAddress($project_manager_email, 'Recipient');     // Add a
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    //echo 'Message has been sent';
    //echo 'Im Loading...just give me a second';
} catch (Exception $e) {
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

	//echo "<br>email to requestee  sent";

}

if ( isset( $_POST[ "music" ] ) ) {
	$to = $music_composition_email;
	$to2 = $music_composition_email2;
	$subject = "Project Request for Music Productions";
	//$message = "Hi";
				$message = "<h2>Someone has submitted a request!</h2>

		<p>
		Hey leader(s) of visual graphics! We've received a request that might pertain to your field of expertise. This project request will be in sms.concordiashanghai.org waiting to be activated when you find someone who could lead it. If this project request has nothing to do with this SIG then I guess just contact the project manager saying s/he needs to fix something in his buggy code.
		<br>
		<br>
		Anyhow, here are the details of the request:
		Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'
		<br>
		(If Documentation) Keep raw footage?: '$keep_raw'
		<br>
		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

	$headers = 'From: SMS Database <SMS@database.com>' . PHP_EOL .
	'Reply-To: SMS Database <SMS@database.com>' . PHP_EOL .
	'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

	//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient
    $mail->addAddress($to2, 'Recipient');     // Add a recipient
    $mail->addAddress($project_manager_email, 'Recipient');     // Add a
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
    //echo 'Message has been sent';
} catch (Exception $e) {
   // echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

	//echo "<br>email to requestee  sent";

}

if ( isset( $_POST[ "videogame" ] ) ) {

	$to = $game_development_email;
	$to2 = $game_development_email2;
	$subject = "Project Request for Game Development";
	//$message = "Hi";
				$message = "<h2>Someone has submitted a request!</h2>

		<p>
		Hey leader(s) of Game Dev! We've received a request that might pertain to your field of expertise. This project request will be in sms.concordiashanghai.org waiting to be activated when you find someone who could lead it. If this project request has nothing to do with this SIG then I guess just contact the project manager saying s/he needs to fix something in his buggy code.
		<br>
		<br>
		Anyhow, here are the details of the request:
		Name of Project: '$name'
		<br>
		Requestor: '$requestee'
		<br>
		Contact: '$contact'
		<br>
		Description: '$description'
		<br>
		Due Date: '$due_date'
		<br>
		Goal: '$goal'
		<br>
		(If Documentation) Keep raw footage?: '$keep_raw'
		<br>
		</p>
		<h5> This project request is in sms.concordiashanghai.org pending requests.</h5>
		";

	$headers = 'From: Student Media Services <SMS@database.com>' . PHP_EOL .
	'Reply-To: SMS Database <SMS@database.com>' . PHP_EOL .
	'X-Mailer: PHP/' . phpversion() . "Content-type: text/html";

	//mail( $to, $subject, $message, $headers );

	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.office365.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $very_secure_email_username;                 // SMTP username
    $mail->Password = $very_secure_email_password;                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to
    //Recipients
    $mail->setFrom($very_secure_email_username, 'Nick');
    $mail->addAddress($to, 'Recipient');     // Add a recipient
    $mail->addAddress($to2, 'Recipient');     // Add a recipient
    $mail->addAddress($project_manager_email, 'Recipient');     // Add a
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = $message;

    $mail->send();
   // echo 'Message has been sent';
} catch (Exception $e) {
   // echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

	//echo "<br>email to requestee  sent";
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SMS Project Request Form</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<!-- Optional theme -->
	<!--	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">-->
	<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
	<link rel="stylesheet" href="form.css">
	<!--        <script src="form.js"></script>-->
</head>
<?php if(isset($smsg)){ ?>
<div class="alert alert-success" role="alert">
	<?php echo $smsg; ?> </div>
<?php } ?>
<?php if(isset($fmsg)){ ?>
<div class="alert alert-danger" role="alert">
	<?php echo $fmsg; ?> </div>
<?php } ?>



<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 " id="form_container">
				<h2>Send in a Request</h2>
				<h4> Welcome to SMS's New Project Request form! By filling out information here, we'll be able to give you more timely responses and you will be updated more frequently regarding the project. <br></h4>

				<br>
				<form role="form" method="post" id="reused_form">


					<!--Your Name						-->
					<div class="row">
						<div class="col-sm-6 form-group">
							<label for="name"> Your Name:</label>
							<input type="text" class="form-control" id="name" name="requestee" required>
						</div>

						<!--Name of the request							-->

						<div class="col-sm-6 form-group">
							<label for="email"> Email:</label>
							<input type="email" class="form-control" id="email" name="contact" required>
						</div>
					</div>

					<!--Goal?					-->
					<div class="row">
						<div class="col-sm-12 form-group">
							<label for="name"> Name of Request</label>
							<input type="text" class="form-control" id="name" name="name" required>
						</div>

					</div>
					<br>



					<!--	Detailed Summary					-->
					<div class="row">
						<div class="col-sm-12 form-group">
							<label for="message"> Detailed list/summary of everything you need in this project. If this is a documentation request, what are the high priority things that need be documented? If this is a music request, then how long should the clip be? What time to what time would you like something to take place? The more specific the better.</label>
							<textarea class="form-control" type="textarea" id="message" name="description" maxlength="6000" rows="7"></textarea>
						</div>
					</div>


					<!--Goal?					-->
					<div class="row">
						<div class="col-sm-8 form-group">
							<label for="name"> Goal <br> (Be specific regarding the intended purpose of the project): </label>
							<input type="text" class="form-control" id="name" name="goal" required>
						</div>

					</div>

					<!--Date?					-->
					<div class="row">
						<div class="col-sm-8 form-group">
							<h4><label for="name"> Need this by:</h4>
							<!--<input type="date" class="form-control" id="name" name="datetime_due" required>-->

<label for="">Month</label>
  <select class="form-control" name="month">
    <option value="1">January</option>
    <option value="2">February</option>
    <option value="3">March</option>
    <option value="4">April</option>
    <option value="5">May</option>
    <option value="6">June</option>
    <option value="7">July</option>
    <option value="8">August</option>
    <option value="9">September</option>
    <option value="10">October</option>
    <option value="11">November</option>
    <option value="12">December</option>
  </select>


<!--  -->
  <label for="">Day</label>
  <select class="form-control" name="day">
<?php
$days = 1;
create_days_dropdown($days);
?>
</select>



<!--  -->
<label for="">Year</label>
<select class="form-control" name="year">
<?php
$year = 2018;
create_years_dropdown($year);
?>
</select>


							<label>Note: SMS has a 7/14 day request policy where the due date for this project should not be within 7 days of the submission of this request IF this project is for documentation, and 14 days for anything else. You can still submit the request, however, know that we might reject the project request. </label>
						</div>

					</div>
					<br>

					<!--Goal?					-->
					<div class="row">
						<div class="col-sm-8 form-group">
							<label for="name"> Involves: </label><br>
							<input type="checkbox" name="visualdesign" value="visualdesign"> Visual Design<br><br>
							<input type="checkbox" name="livestream" value="livestream"> Livestream Video Capture<br><br>
							<input type="checkbox" name="music" value="music"> Music Composition<br><br>
							<input type="checkbox" name="videogame" value="videogame"> Video Game Development<br><br>
							<input type="checkbox" name="filmmaking" value="filmmaking"> Video Production/Filmmaking <br><br>
							<input type="checkbox" name="documentation" value="documentation"> Video/Photographic Event Documentation<br><br>

						</div>

					</div>

					<!--Goal?					-->
					<div class="row">
						<div class="col-sm-7">
							<label for="">Would you like to keep the raw footages? (This is for video productions. Otherwise we will delete the footage) </label>
							<!--							<div class = "form-control">-->
							<select name="keep_raw" class="form-control">
								<option value="no">No, I only want the final video production</option>
								<option value="yes">Yes, Please give me all the raw footage and the final video production.</option>
								<option value="no">This is not a video production request</option>


							</select>
							<!--							</div>-->
						</div>

					</div>

					<br>
					<br>
					<label for="">By sending this request, you accept that SMS has the right to redistribute or share the work created for this request. If you have any issues with this please email cyrus2021262@concordiashanghai.org or alan2021233@concordiashanghai.org.</label>
					<br>
					<label for="">Please remember that SMS is a 100% free service offered by our hardworking students. </label>



					<div class="row">
						<div class="col-sm-12 form-group">
							<button type="submit" class="btn btn-lg btn-default pull-right">Send &rarr;</button>
						</div>
					</div>




				</form>

			</div>
		</div>
	</div>
</body>
</html>
