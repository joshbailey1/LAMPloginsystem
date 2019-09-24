<?php
session_start();
//Connection info
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'redacted';
$DATABASE_PASS = 'redacted';
$DATABASE_NAME = 'firstdatabase';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	//if there's an error kill script and display
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//now we check if the data from the login was submitted
if ( !isset($_POST['username'], $_POST['password']) ) {
	// couldn't get the data
	die ('Please fill both the username and password field!');
}

//Prepare our SQL, preparing the SQL statement will prevent SQL injection
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	//bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	//store the result so we can check if the account exists in the database
	$stmt->store_result();
}

if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $password);
	$stmt->fetch();
	// Account exists, now we verify the password.
	// Note: remember to use password_hash in your registration file to store the hashed passwords.
	if (password_verify($_POST['password'], $password)) {
		// Verification success! User has loggedin!
		// Create sessions so we know the user is logged in, they basically act like cookies but remember the data on the server.
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		header('Location: home.php');
	} else {
		echo 'Incorrect password!';
	}
} else {
	echo 'Incorrect username!';
}
$stmt->close();
