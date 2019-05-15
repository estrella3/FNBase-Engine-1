<?php
if($_COOKIE['dont'] == "yes"){
  setcookie('writed', 'yes', time() + 200);
  echo "<script> alert('연속으로 글을 작성하였습니다. 글 작성이 3분간 제한됩니다.'); history.back(); </script>";
  die();
}elseif($_COOKIE['writed'] == "yes"){
  echo "<script> alert('아직 글을 작성할 수 없습니다. 1분만 기다려주세요.'); history.back(); </script>";
}else{
include "up.php";
if(isset($_SESSION['userid'])){
  $idNpw = '<input type="hidden" name="id" value="'.$_SESSION['userid'].'"><input type="hidden" name="pw" value="_logged"><input type="hidden" name="islogged" value="true">';
}else{
  /* $idNpw = '<p align="left"><input style="width: 6em;" maxlength="5" type="text" name="id" placeholder="익명_"></p>
  <p align="left"><input type="password" style="width: 6em;" name="pw" maxlength="6" placeholder="비밀번호"></p>'; */
  echo "<script>alert('아직 비로그인 회원은 글을 쓸 수 없습니다. 회원가입을 권장합니다.');history.back()</script>";
  echo "JavaScript를 허용해주세요.";
}
    $getturn = $_POST['from'];
		if(isset($getturn)){
			$board = $getturn;
    }

    $sql = "SELECT * FROM `_board` WHERE `id` LIKE '$board'";
    $result = mysqli_query($conn, $sql);
    
    while($row = mysqli_fetch_array($result)){
    
        $board1 = $row['id'];
        $boardname = $row['name'].' '.$row['suffix'];
        $boardsuffix = $row['suffix'];
        $owner = $row['owner'];
        $boardstat = $row['stat'];
        $boardnum = $row['num'];
        $notice = $row['notice'];
        if(!empty($row['text'])){
            $boardtext = '<span style="color: gray; font-size: 0.5em; text-decoration: none">'.$row['text'].'</span><br>';
        }else{
            $boardtext = '';
        }
        if($boardstat == 1){
            $boardstat = '<span class="badge badge-primary">공식 '.$boardsuffix.'</span>';
        }elseif($boardstat == 0){
            $boardstat = '<span class="badge badge-light">사설 '.$boardsuffix.'</span>';
        }elseif($boardstat == 8){
            $boardstat = '<span class="badge badge-warning">비활성</span>';
            $nowrite = true;
        }elseif($boardstat == 9){
            $boardstat = '<span class="badge badge-danger">차단됨</span>';
            $nowrite = true;
        }
    }
        if(1 > mysqli_num_rows($result)){
        echo '<script>alert("없는 게시판입니다.")</script>';
        include_once 'down.php';
        exit;
    }
    ?><div style="padding-left:3px;padding-right:3px">
    <hr>
        <form method="post" action="write.php">
        <h4><?php echo '<a style="color:black" href="'.$board1.'.fn">'.$boardname.'</a>'; if(!$nowrite === true){echo'<button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button>';}?>
        <span style="color: gray; font-size: 0.5em; text-decoration: none">| 주인 : <a href="/user.php?a=<?php echo $owner;?>">@<?php echo $owner;?></a></span><br>
        <?php echo '<span class="h6">'.$boardstat.'</span>&nbsp;'; echo $boardtext;?></h4>
        <input type="hidden" name="from" value="<?php echo $board1 ?>">
        </form>
    <hr>
</div><?php

    //차단 여부 확인
    $query = "select * from _account where id='".$_SESSION['userid']."'";
    $result = $conn->query($query);
    $row=mysqli_fetch_assoc($result);
    $ban_reason = $row['whyibanned'];
    if($row['ban'] == 1){
      echo "<script>alert('게시글을 작성할 수 없습니다! 당신은 $ban_reason 에 의해 공통 차단되었습니다.')</script>";
    }else{
echo '<section class="container">
<div><h6>글쓰기</h6></div>
<div>
    <form action="save.php" method="POST" id="wrtatc">
    <hr>
      <p><input type="text" style="outline: 0; width: 100%; border: none; background-color: transparent" name="title" placeholder="제목" required></p>
    <hr>
      <textarea name="description" id="editor" style="width: 100%; border: none; background-color: white; min-height: 350px"></textarea>
      <script src="assets/minified/formats/bbcode.min.js"></script>
<script>
// Replace the textarea #example with SCEditor
var textarea = document.getElementById("editor");
var textarea = document.getElementById("editor");
sceditor.create(textarea, {
format: "bbcode",
toolbar: "bold,italic,strike,superscript|left,center,right|color,size,font|bulletlist,orderedlist,table|link,image,youtube|source,maximize",
fonts: "",
style: "assets/minified/themes/content/default.min.css"
});
</script>
      <div>'.$idNpw.'
      <p align="right"><br><input class="btn btn-success" style="width: 100%" type="submit" value="작성 완료"></p></div>
      <input type="hidden" name="UIP" value="'.$_SERVER['REMOTE_ADDR'].'">
      <input type="hidden" name="b" value="'.$board.'">';
echo '<input type="hidden" name="editor" value="true">';
echo '
    </form>
    </div>
    <script>
    $(function ()
    {
        $(document).on("keydown", "#editor", function(e)
        {
            if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey)
            {
              $("#wrtatc").submit();
            }
        });
    });
    </script>
    ';

}
include "down.php"; } ?>