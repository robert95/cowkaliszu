<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
		
	$k = $_GET['kat'];
	$wyj = getRecomendateEvent($conn, $k);
	if($wyj=="")
	{
		$wyj = "<span class='noMainEvent'>Przykro mi, ale nie ma zaplanowanych polecanych wydarzeń w tej kategorii:(</span>";
	}
	echo $wyj;
	sqlClose($conn);
?>