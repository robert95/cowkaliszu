<?php
	include_once 'mysql.php';
	include_once 'function.php';

	echo getCommentForEvent($_GET['id']);
?>