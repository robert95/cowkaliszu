<?php
	include_once 'mysql.php';
	include_once 'function.php';

	if (!isset($_GET['keyword'])) {
		die("");
	}
	$conn = sqlConnect();
	$keyword = $_GET['keyword'];
	$data = serachPlaceForKeyword($keyword, $conn);
	sqlClose($conn);
	echo json_encode($data);
?>