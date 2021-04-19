<?php

session_start();

require_once( 'includes/dbh.inc.php' );


$projectid = mysqli_real_escape_string( $connection, $_GET[ 'projectid' ] );
//$project_name = mysqli_real_escape_string( $connection, $_GET[ 'name' ] );

$sql = "SELECT * FROM project_list WHERE projectid = '$projectid'";
//echo $id;
$result = mysqli_query( $connection, $sql );
while ( $projectinfo = $result->fetch_assoc() ):

$project_name = $projectinfo['project_name'];

endwhile;
?>

	<?php 	if ( isset( $_GET[ 'error' ] ) ) {
			$fmsg = $_GET[ 'error' ]; } ?>
		<?php 	if ( isset( $_GET[ 'success' ] ) ) {
			$smsg = $_GET[ 'success' ]; } ?>

							<?php if(isset($smsg)){ ?>
							<div class="alert alert-success" role="alert" style="margin-top: 20px;">
								<?php echo $smsg; ?> </div>
							<?php } ?>
							<?php if(isset($fmsg)){ ?>
							<div class="alert alert-danger" role="alert" style="margin-top: 20px;">
								<?php echo $fmsg; ?> </div>
							<?php } ?>


<?php

if ( isset( $_POST ) & !empty( $_POST ) ) {
	$username = mysqli_real_escape_string( $connection, $_POST[ 'username' ] );
	//$studentid = mysqli_real_escape_string( $connection, $_POST[ 'studentid' ] );
	$password = md5( $_POST[ 'password' ] );

	//Querying to create sessions for each of the officers/admins


	$adminsql = "SELECT * FROM `login` WHERE username = '$username' AND password = '$password' and exec_rights = 'yes';";
	$adminresult = mysqli_query( $connection, $adminsql );
	$count = mysqli_num_rows( $adminresult );
	if ( $count == 1 ) {
		$admin_rights = 'yes';

		$_SESSION[ 'exec_rights' ] = $admin_rights;
		//echo "created admin session but u cant see this anyway";

	}


	$adminsql = "SELECT * FROM `login` WHERE username = '$username' AND password = '$password' and is_leadership = 'yes';";
	$adminresult = mysqli_query( $connection, $adminsql );
	$count = mysqli_num_rows( $adminresult );
	if ( $count == 1 ) {
		$leadership = 'yes';

		$_SESSION[ 'is_leadership' ] = $leadership;
		//echo "created nhs session but u cant see this anyway";

	}


	$sql = "SELECT * FROM `login` WHERE username = '$username' AND password = '$password';";
	$result = mysqli_query( $connection, $sql );
	$count = mysqli_num_rows( $result );
	if ( $count == 1 ) {

		while ( $student = $result->fetch_assoc() ):

		$studentid = $student['studentid'];

		endwhile;



		$_SESSION[ 'studentid' ] = $studentid;








	} else {
		//echo "Invalid Username/Password";
		echo '<script>window.location.href = "login.php?error=Invalid Username/Password";</script>';
		//echo "<script>window.location.href = 'login.php?error='$ello' Username/Password';</script>";

	}


}?>

<?php
				if ( isset( $_SESSION[ 'studentid' ] ) ) {
					?>


					<?php

					if ( isset( $_GET[ 'projectid' ] ) ) {

						echo "<script> window.location.href = 'searchprojectdesc.php?name=" . $project_name . "&id=" . $projectid . "'</script>";
					}





					echo '<script>window.location.href = "hub.php";</script>';
					} ?>




<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>SMS Login</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>



      <link rel="stylesheet" href="css/style.css">


</head>

<body>

  <div class="page">
  <div class="container">
    <div class="left">
      <div class="login">Login</div>
      <div class="eula">By logging in you agree to the ridiculously long terms that you didn't bother to read. <br> <a href="register.php">Don't have an account? Click me</a></div>

    </div>
    <div class="right">
      <svg viewBox="0 0 320 300">
        <defs>
          <linearGradient
                          inkscape:collect="always"
                          id="linearGradient"
                          x1="13"
                          y1="193.49992"
                          x2="307"
                          y2="193.49992"
                          gradientUnits="userSpaceOnUse">
            <stop
                  style="stop-color:#ff00ff;"
                  offset="0"
                  id="stop876" />
            <stop
                  style="stop-color:#ff0000;"
                  offset="1"
                  id="stop878" />
          </linearGradient>
        </defs>
        <path d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143" />
      </svg>
      <div class="form">
		  <form method="POST">
        <label for="username">Username</label>
        <input type="username" id="email" name = "username">
        <label for="password">Password</label>
        <input type="password" id="password" name = "password">
        <input type="submit" id="submit" value="Login">
			  </form>
      </div>
    </div>
  </div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js'></script>



    <script  src="js/index.js"></script>




</body>

</html>
