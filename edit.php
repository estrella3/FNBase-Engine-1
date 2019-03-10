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
if($_SESSION['userid'] == 'admin'){
$notice = '<label>제목 굵게 : <input type="checkbox" name="notice" value="yes"></label>';
}
echo '<div class="container"><form action="keepedit.php" method="post">
<hr>
<p><input type="text" style="outline: 0; width: 100%; border: none; background-color: transparent" value="'.$t.'" name="title" placeholder="제목" required></p>
<hr>
<textarea name="description" id="editor" style="width: 100%; border: none; background-color: white; min-height: 350px">'.$n.'</textarea>
<script src="assets/minified/formats/bbcode.min.js"></script>
<script>
// Replace the textarea #example with SCEditor
var textarea = document.getElementById("editor");
sceditor.create(textarea, {
format: "bbcode",
toolbar: "bold,italic,strike,superscript|left,center,right|color,size,font|bulletlist,orderedlist,table|link,image,youtube|source,maximize",
fonts: "",
style: "assets/minified/themes/content/default.min.css"
});
</script>

<p align="right"><input class="btn btn-success" type="submit" value="작성"></p><p align="left">'.$notice.'</p></div>
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