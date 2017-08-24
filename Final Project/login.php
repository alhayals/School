<?php
	include_once("classes/Session.php");
	include_once("classes/Database.php");
	include_once("classes/Member.php");
	include_once("classes/Data.php");
	$error="";
	if($session->is_logged_in())
		header("Location: /60344/jobs");
	// Remember to give your form's submit tag a name="submit" attribute!
	if(isset($_POST['username']) && !empty($_POST['username'])) 
	{ // Form has been submitted.
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		// Check database to see if username/password exist.
		$found_member = Member::authenticate($username, $password);
		if ($found_member) 
		{
			$session->login($found_member);
			header("Location: /60334/jobs");
			//log_action('Login', "{$found_user->username} logged in.");
		} 
		else 
		{
			// username/password combo was not found in the database
			$error = "Username/password combination incorrect.";
		}
	} 
	else 
	{ // Form has not been submitted.
		$username = "";
		$password = "";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>DMG</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	
<link href='http://fonts.googleapis.com/css?family=Lato:400,600,700,800,300' rel='stylesheet' type='text/css'>
</head>

<body class="login-bg">
<div class="container-fluid">
	<div class = "row" style = "margin-left:38px; margin-top:40px;">
		<!--<div class = "col-md-3"><img class = "img-responsive" alt = "dmgLogo" src = "images/dmglogo.png"></div>
		--><div class = "col-md-9"></div>
	</div>
	<div class = "row">
		<div class = "loginTitle col-md-12 col-sm-12">
			<p>JobCatcher</p>
			<p><span class = "establishConnections">establish new connections</span></p>
		</div>

		<div class = "col-md-12 col-sm-12 login">
			<form method="post" action="">
				<p><input type="text" name="username" value="" placeholder= "USERNAME"></p>
				<p><input type="password" name="password" value="" placeholder= "PASSWORD"></p>
				<p><input type="submit" name="submit" value="LOG IN"><br></p>
				<p><a href = "forgot.php" style = "color:#fff;">FORGOT PASSWORD OR USERNAME?</a></p>
				<?php echo $error; ?>
			</form>
		</div>
	</div>
</div>

<!--end wrap-->
</body>
</html>