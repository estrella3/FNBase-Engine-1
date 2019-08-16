<?php
require "up.php";
if(isset($_SESSION['userid'])){
  $idNpw = '<input type="hidden" name="id" value="'.$_SESSION['userid'].'"><input type="hidden" name="pw" value="_logged"><input type="hidden" name="islogged" value="true">';
}else{
  /* $idNpw = '<p align="left"><input style="width: 6em;" maxlength="5" type="text" name="id" placeholder="익명_"></p>
  <p align="left"><input type="password" style="width: 6em;" name="pw" maxlength="6" placeholder="비밀번호"></p>'; */
  echo "<script>alert('아직 비로그인 회원은 글을 쓸 수 없습니다. 회원가입을 권장합니다.');history.back()</script>";
  echo "JavaScript를 허용해주세요.";
  exit;
}

$sql = "SELECT * from `_article` WHERE `author_id` = '$u_id' ORDER BY `created` DESC limit 1";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) < 1){
    $CaValue = 61;
}
while($row = mysqli_fetch_array($result)){
    $time = strtotime($row['created']);
    $CaValue = strtotime(date("Y-m-d H:i:s")) - $time;
}
if($CaValue < 60){
    $Value = 60 - $CaValue;
    echo '<script>alert("'.$Value.'초 뒤에 다시 시도해주세요."); history.back()</script>';
    exit;
}

    $board = FnFilter($_POST['from']);
    $sql = "SELECT * FROM `_board` WHERE `id` LIKE '$board'";
    $result = mysqli_query($conn, $sql);
    
    while($row = mysqli_fetch_array($result)){
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
            $boardstat = '<span class="badge badge-warning">특수</span>';
            $nowrite = true;
        }elseif($boardstat == 9){
            $boardstat = '<span class="badge badge-danger">차단됨</span>';
            $nowrite = true;
        }elseif($boardstat == 2){
          $boardstat = '<span class="badge badge-info">제휴</span>';
        }elseif($boardstat == 3){
          $boardstat = '<span class="badge badge-secondary">도움말</span>';
          $nowrite = true;
        }
    }
    $kpr = strpos($keeper, $_SESSION['userid']);
    if($kpr === true){
    $sql = "SELECT * FROM `_userRights` WHERE `type` like '3'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
        if($row['editBoardInfo'] == 1){
            $editBoard = true;
        }
        if($row['kickAnother'] == 1){
            $canKick = true;
        }
        if($row['deleteAnother'] == 1){
            $canKick = true;
        }
        if($row['makeBoardNotice'] == 1){
            $makeNotice = true;
        }
        $isOwner = false;
    }
    }
    $vlt = strpos($volun, $_SESSION['userid']);
    if($vlt === true){
    $sql = "SELECT * FROM `_userRights` WHERE `type` like '2'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
        if($row['editBoardInfo'] == 1){
            $editBoard = true;
        }
        if($row['kickAnother'] == 1){
            $canKick = true;
        }
        if($row['deleteAnother'] == 1){
            $canKick = true;
        }
        if($row['makeBoardNotice'] == 1){
            $makeNotice = true;
        }
        $isOwner = false;
    }
    }
    if($owner === $_SESSION['userid']){
    $makeNotice = true;
    $canKick = true;
    $canDelete = true;
    $editBoard = true;
    $isOwner = true;
    }
        if(1 > mysqli_num_rows($result)){
        echo '<script>alert("없는 게시판입니다.")</script>';
        include_once 'down.php';
        exit;
    }
    ?><div style="padding-left:3px;padding-right:3px">
    <hr>
    <form method="post" action="/write.php">
            <h4><?php echo '<a style="color:black" href="/b/'.$board.'">'.$boardname.'</a>'; if(!$nowrite === true){echo
                '<button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button><span style="float:right">&nbsp;</span>';}
                if($editBoard == true){
                    echo '<a href="/b/'.$board.'/admin"><button type="button" class="btn-sm btn-danger" style="float: right">채널 설정</button></a>';}
                    $sql1 = "SELECT * FROM `_account` WHERE `id` LIKE '$owner'";
                    $result1 = mysqli_query($conn, $sql1);
                    while($row1 = mysqli_fetch_array($result1)){
                        $owner_n = $row1['name'];
                    }
                    ?>
            <span style="color: gray; font-size: 0.5em; text-decoration: none">| 소유주 : <a href="/user.php?a=<?=$owner?>">@<?php echo $owner_n;?></a></span><br>
            <?php echo '<span class="h6">'.$boardstat.'</span>&nbsp;'; echo $boardtext;?></h4>
            <input type="hidden" name="from" value="<?php echo $board ?>">
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
 <form action="save.php" method="POST" id="wrtatc">
    <h6>글쓰기</h6>
    <button class="badge badge-info" type="button" style="float:right"
    type="button" data-toggle="collapse" data-target="#etc" aria-expanded="false" aria-controls="etc">기타 설정</button><div>
    <div id="etc" class="collapse">
    <div class="card card-body">
    <label for="topSecret"><input type="checkbox" name="issec" id="topSecret" onclick="document.getElementById(\'secexp\').style = \'\';">열람 제한 기능</label>
    <div id="secexp" style="display:none"><p style="color:gray>지정한 사람 이외에는 글을 볼 수 없게 합니다.</p>
    <label for="0"><input type="radio" name="secopt" id="0" value="0" required checked />전체 공개</label>
    <label for="1"><input type="radio" name="secopt" id="1" value="1" onclick="document.getElementById(\'secline\').style = \'display:none\';" required />나만 보기</label>
    <label for="2"><input type="radio" name="secopt" id="2" value="2" onclick="document.getElementById(\'secline\').style = \'\';" required />사용자 지정</label>
    <div id="secline" style="display:none"><input type="text" name="secnick" ><span style="color:gray">아이디를 쉼표(,)로 구분해주세요.</span></div>
    </div>
    </div>
    </div>
    <hr>
      <p><input type="text" style="outline: 0; width: 100%; border: none; background-color: transparent" name="title" placeholder="제목" required></p>
    <hr>    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.css">
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/theme/monokai.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/codemirror.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/3.20.0/mode/xml/xml.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/2.36.0/formatting.js"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-lite.js"></script>
    <textarea id="summernote" name="description"></textarea><div style="width:100%;height:5px">&shy;</div>
    ';
    echo "<script>
              $('#summernote').summernote({
        placeholder: '내용 작성',
        tabsize: 2,
        height: 300,
        lang: 'ko-KR',
        focus: true,
        shortcuts: false,
        codeviewFilter: true,
        codeviewIframeFilter: true,
        toolbar: [
        ['style', ['bold', 'italic', 'underline', 'strikethrough', 'color', 'fontsize', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['video', 'link', 'table', 'hr']],
        ['misc', ['codeview', 'undo', 'redo']]
        ],
        codemirror: {
            theme: 'monokai'
            }
              });
    </script>
";
echo $idNpw.'<br><input class="btn btn-success" style="width: 100%" type="submit" value="작성 완료"></p></div>
      <input type="hidden" name="UIP" value="'.$_SERVER['REMOTE_ADDR'].'">
      <input type="hidden" name="b" value="'.$board.'">';
echo '<input type="hidden" name="editor" value="true">';
echo '
    </form>
    </div>
';
}
require "down.php";?>