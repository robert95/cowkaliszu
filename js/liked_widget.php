<?php
	include_once 'mysql.php';
	include_once 'function.php';
	if(isset($_GET['cowkaliszu'])) $cowkalisz = "?cowkaliszu=1";
	else $cowkalisz = "";
?>
<div id="widget_co_w_kaliszu">
	<style>
		#widget_co_w_kaliszu{
			position: fixed;
			top: 70px;
			right: 0;
			width: 320px;
			height: 1200px;
			transition: right 0.5s ease-out 0s;
			right: -259px;
		}
		iframe{
			margin: 0;
			padding: 0;
			border: none;
			width: 320px;
			height: 98%;
		}
		.widget_alway_show{
			right: 0 !important;
		}
	</style>
	<iframe src="https://co.wkaliszu.pl/ulubionenowe.php<?php echo $cowkalisz; ?>" id="cowkaliszu_widget" scrolling="no" seamless="seamless"></iframe>
</div>