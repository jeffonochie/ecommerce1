<?php
	$server="localhost";
	$user="expADM";
	$pwd="exchange$321#";
	$db="ufitbuy";

	$conn = mysqli_connect($server, $user, $pwd, $db);
	if (!$conn) {
		die("Connection failed " . mysqli_connect_error());
	}
?>