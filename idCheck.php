<?php
require 'setting.php';
$id = $_POST['id'];
$re = '/[^a-z, A-Z, 0-9, _]/m';
$id = preg_replace($re, '', $id);

if(empty($id)){
	echo '0';
	exit;
}

$id = mysqli_real_escape_string($conn, $id);

$sql = "SELECT * FROM `_account` WHERE id like '$id'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0){
	echo '0';
}elseif($result === FALSE){
	echo '0';
}else{
	echo '1';
}
?>