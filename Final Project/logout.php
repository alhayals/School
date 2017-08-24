<?php
	include_once("classes/Session.php");

	session_destroy();
	
	header("Location: /60334/jobs/login.php");
?>