<?php
require 'setting.php';
$id = $_POST['id'];
$sql = "SELECT * FROM `_account` WHERE id like '$id'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0){
	echo '0';
}else{
	echo '1';
}
?>