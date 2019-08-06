<!DOCTYPE html>
<html>
	<head>
		<link href="styles.css" rel="stylesheet" type="text/css">
		<meta charset="utf-8">
		<title>Register</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
		<div class="register">
			<h1>Register</h1>
			<form action="registerprocess.php" method="post" autocomplete="off">
				<?php if (isset($_GET["err"])) {
					if ($_GET["err"] == "formincomplete"){
						echo "Please complete the form<br><br>";
					}
					if ($_GET["err"] == "emailinvalid"){
						echo "Please enter a valid email<br><br>";
					}
					if ($_GET["err"] == "userinvalid"){
						echo "Please enter a valid username<br><br>";
					}
					if ($_GET["err"] == "pwlength"){
						echo "Password must be between 5 and 20 characters long<br><br>";
					}
					if ($_GET["err"] == "userexists"){
						echo "Username already exists, please pick another<br><br>";
					}
				} ?>
				<label for="username">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="username" placeholder="Username" id="username" required>
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<label for="email">
					<i class="fas fa-envelope"></i>
				</label>
				<input type="email" name="email" placeholder="Email" id="email" required>
				<input type="submit" value="Register">
			</form>
		</div>
	</body>
	<nav class="navtop">
		<div>
			<a href="index.html"><i class="fas fa-index"></i>Home</a>
			<a href="contact.html"><i class="fas fa-contact"></i>Contact Us</a>
		</div>
	</nav>
</html>
