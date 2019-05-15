<?php
include "./up.php";
session_start();
$id =  $_POST['id'];
$b = $_POST['b'];
$session = $_SESSION['userid'];
$sql = "SELECT * FROM `_article` WHERE `author_id` LIKE '$session' and `from` like '$b' and `id` like '$id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);
if($count == 1){
if($_POST['v'] == 'viewer'){

$sql = "SELECT * FROM `_article` WHERE `id` LIKE '$id' and `from` like '$b'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
    $t = $row['title'];
    $n = $row['description'];
}
echo '<div class="container"><form action="keepedit.php" method="post">
<hr>
<p><input type="text" style="outline: 0; width: 100%; border: none; background-color: transparent" value="'.$t.'" name="title" placeholder="제목" required></p>
<hr>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.js"></script>
<textarea id="summernote" name="description">'.$n.'</textarea>';
echo "<script>
              $('#summernote').summernote({
        placeholder: '내용 작성',
        tabsize: 2,
        height: 300,
        lang: 'ko-KR',
        focus: true,
		shortcuts: false,
			toolbar: [
			['style', ['bold', 'italic', 'underline', 'strikethrough', 'color', 'fontsize', 'clear']],
			['para', ['ul', 'ol', 'paragraph']],
			['insert', ['video', 'link', 'table', 'hr']],
			['misc', ['codeview', 'undo', 'redo']]
		]
              });
    </script>";
echo '<p align="right"><br><input class="btn btn-success" style="width: 100%" type="submit" value="작성 완료"></p></div>
<input type="hidden" name="id" value="'.$id.'">
<input type="hidden" name="b" value="'.$b.'">
</form></div>';
}else{
    echo "잘못된 경로";
}}else{
    echo '본인 확인 불가';
}
include "down.php";
?>