<?php
	include_once 'mysql.php';
	include_once 'function.php';
	
	$m = $_GET['m'];
	$n = $_GET['n'];
	echo $m." -> ".$n;
	loginByFacebook($n, $m);
?>