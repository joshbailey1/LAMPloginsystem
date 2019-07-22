<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
//connection info
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'redacted';
$DATABASE_PASS = 'redacted';
$DATABASE_NAME = 'redacted';

//attempting connection with above info
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	//if there is an error kill session and display error
        echo '<br> <br>';
        echo '<a href="registerpage.php"><i class="fas fa-registerpage"></i>Back</a>';
	$err = 'Failed to connect to MySQL: ';
	trigger_error($err, E_USER_ERROR);
}

//now check if data was submitted, isset() checks if data exists
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	//couldn't get the data that should have been sent
        echo '<br> <br>';
        echo '<a href="registerpage.php"><i class="fas fa-registerpage"></i>Back</a>';
	$err = 'Please complete the registration form';
	trigger_error($err, E_USER_ERROR);
}

//make sure the submitted values are not empty
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	//one or more values are empty
        echo '<br> <br>';
        echo '<a href="registerpage.php"><i class="fas fa-registerpage"></i>Back</a>';
        $err = 'Please complete the registration form';
        trigger_error($err, E_USER_ERROR);
}

//check email is valid
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo '<br> <br>';
        echo '<a href="registerpage.php"><i class="fas fa-registerpage"></i>Back</a>';
	$err = 'Email is invalid';
        trigger_error($err, E_USER_ERROR);
}
//check username is valid
if (preg_match('/[A-Za-z0-9]+/', $_POST['username']) == 0) {
        echo '<br> <br>';
        echo '<a href="registerpage.php"><i class="fas fa-registerpage"></i>Back</a>';
        $err = 'Username is invalid';
        trigger_error($err, E_USER_ERROR);
}
//check password is right length
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        echo '<br> <br>';
        echo '<a href="registerpage.php"><i class="fas fa-registerpage"></i>Back</a>';
        $err = 'Password has to be between 5 and 20 characters in length';
        trigger_error($err, E_USER_ERROR);
}


//check the account with that username exists already
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	//bind parameters and hash the password
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	//store result so we can check if username exists
	if ($stmt->num_rows > 0) {
		//username already exists
	        echo '<br> <br>';
	        echo '<a href="registerpage.php"><i class="fas fa-registerpage"></i>Back</a>';
               	$err = 'Username already exists please pick another';
        	trigger_error($err, E_USER_ERROR);
	} else {
		//username does not exist, insert new account
		if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
			//dont expose passwords so hash the pw then use pw verify when a user logs in
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
			$stmt->execute();
			echo 'Your account has been created';
			echo '<br> <br>';
			echo '<a href="index.html"><i class="fas fa-home"></i>Home</a>';
		} else {
			//something is wrong with the sql statement
			echo 'Could not prepare statement';
		}
	}
	$stmt->close();
}
$con->close();

?>
