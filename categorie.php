<?php
	include_once 'mysql.php';
	include_once 'function.php';
	$conn = sqlConnect();
	//$cat = categoriesAsLi($conn);
	//addCategory("tetr", "img/", $conn);
	//deleteCategory(3, $conn);
	
	//$md5file = md5_file($_FILES["fileToUpload"]["tmp_name"]);
	//echo $md5file;
	if(isset($_POST["submit"])) {
		addCategory($conn);
    }
	
	sqlClose($conn);
?>
<!DOCTYPE html>
<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
    Select image to upload:
	<input type="text" name="name">
    <input type="file" name="icon" id="icon">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>