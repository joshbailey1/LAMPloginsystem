<?php
//connection info
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'redacted';
$DATABASE_PASS = 'redacted';
$DATABASE_NAME = 'redacted';
//try and connect using above info
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//first check if email and code exists..
if (isset($_GET['email'], $_GET['code'])) {
	if ($stmt = $con->prepare ('SELECT * FROM accounts WHERE email = ? AND activation_code = ?')) {
		$stmt->bind_param('ss', $_GET['email'], $_GET['code']);
		$stmt->execute();
		//store result so we can check if account exists in database
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
			//account exists with requested email and code
			if ($stmt = $con->prepare('UPDATE accounts SET activation_code = ? WHERE email = ? AND activation_code = ?')) {
				//set new activation code to 'activated', this is how we check if a user has activated their account
				$newcode = 'activated';
				$stmt->bind_param('sss', $newcode, $_GET['email'], $_GET['code']);
				$stmt->execute();
				echo 'Your account is now activated, you can now login!<br><a href="index.html">Login</a>';
			}
		} else {
			echo 'The account is already activated or does not exist';
		}
	}
}
?>
