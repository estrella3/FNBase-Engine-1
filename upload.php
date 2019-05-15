<?php
$date = date("sYuw");
include 'up.php';
if(empty($_POST['mode'])){
echo '<form enctype="multipart/form-data" action="upload.php" method="post">
	<input type="file" name="myfile">
    <input type="hidden" name="mode" value="upload">
	<input type="submit" value="업로드">
</form>
3MB 이내의 이미지만 업로드해주세요.';
}else{
// 설정
$uploads_dir = './upload/images';
$allowed_ext = array('jpg','jpeg','png','gif');
 
// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];
$ext = array_pop(explode('.', $name));
 
// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
	switch( $error ) {
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			echo "파일이 너무 큽니다. ($error)";
			break;
		case UPLOAD_ERR_NO_FILE:
			echo "파일이 첨부되지 않았습니다. ($error)";
			break;
		default:
			echo "파일이 제대로 업로드되지 않았습니다. ($error)";
	}
	exit;
}
// 확장자 확인
if( !in_array($ext, $allowed_ext) ) {
	echo "허용되지 않는 확장자입니다. ";
	echo '.jpg/.jpeg, .png, .gif 파일만 올려주세요.';
	exit;
}
if($_FILES['myfile']['size'] > 3e+6){
	echo '파일이 너무 큽니다. ';
	echo '<a href="https://iloveimg.com/ko">여기</a>로 이동하여 이미지의 크기를 3MB 이하로 줄이세요.';
	exit;
}
// 파일 이동
move_uploaded_file( $_FILES['myfile']['tmp_name'], "$uploads_dir/$date-$name");
// 파일 정보 출력
echo "<h2>파일 정보</h2>
<ul>
	<li>파일명: $name</li>
    <li>확장자: $ext</li>
	<li>주소 복사: <input readonly id='imgsrc' value='./upload/images/$date-$name'><button onclick='copy_to_clipboard()'>복사</button></li>


	<img src='./upload/images/$date-$name'>
</ul>";

define('__Limit_Width',600);  // 원하는 가로길이 limit값 
define('__Limit_Height',600); // 원하는 세로길이 limit값
$img = "./upload/images/$date-$name";
$imgsize = getimagesize($img); 
if($imgsize[0]>__Limit_Width || $imgsize[1]>__Limit_Height) { 
// 가로길이가 가로limit값보다 크거나 세로길이가 세로limit보다 클경우 
	  if($imgsize[0]<$imgsize[1]) { 
	  // 가로가 세로보다 클경우 
			  $sumw = (100*__Limit_Height)/$imgsize[1]; 
			  $img_width = ceil(($imgsize[0]*$sumw)/100); 
			  $img_height = __Limit_Height; 
	  } else { 
	  // 세로가 가로보다 클경우 
			  $sumh = (100*__Limit_Width)/$imgsize[0]; 
			  $img_height = ceil(($imgsize[1]*$sumh)/100); 
			  $img_width = __Limit_Width; 
	  } 
} else { 
// limit보다 크지 않는 경우는 원본 사이즈 그대로..... 
	  $img_width = $imgsize[0]; 
	  $img_height = $imgsize[1]; 
}
}
include 'down.php';
?>	
<script>
function copy_to_clipboard() {
var copyText = document.getElementById("imgsrc");
copyText.select();
document.execCommand("Copy");
}
</script>