<?php
require 'up.php';

$from = Filt($_GET['b']);

$sql = "SELECT * FROM `_board` WHERE `id` LIKE '$from'";
$result = mysqli_query($conn, $sql);
    if($_GET['form'] == 'submit'){
        $from = Filt($_POST['b']);
        echo '저장중...';
        $Ex = Filt($_POST['exp']);
        $hT = Filt($_POST['hashTag']);
                $sqla = "UPDATE `_board` set `text` = '$Ex', `hashtag` = '$hT' where `id` like '$from'";
                $resulta = mysqli_query($conn, $sqla);
                if($resulta === false){
                echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
                exit;
                }else{
                echo "<script>alert('수정 완료!'); history.go(-2)</script>";
                exit;
                }
    }

while($row = mysqli_fetch_array($result)){

    $board = $row['id'];
    $boardname = $row['name'].' '.$row['suffix'];
    $boardsuf = $row['suffix'];
        if(empty($boardsuf)){
            $boardsuffix = '게시판';
        }else{
            $boardsuffix = $boardsuf;
        }
    $owner = $row['owner'];
    $keeper = $row['keeper'];
    $volun = $row['volunteer'];
    $boardtext = $row['text'];
    $boardtag = $row['hashtag'];
    $boardstat = $row['stat'];
    $boardnum = $row['num'];
    $notice = $row['notice'];

    if($boardstat == 1){
        $boardcat = '<span class="badge badge-primary">공식</span>';
    }elseif($boardstat == 0){
        $boardcat = '<span class="badge badge-light">사설</span>';
    }elseif($boardstat == 8){
        $boardcat = '<span class="badge badge-warning">특수</span>';
        $nowrite = true;
    }elseif($boardstat == 9){
        $boardcat = '<span class="badge badge-danger">차단됨</span>';
        $nowrite = true;
    }elseif($boardstat == 2){
        $boardcat = '<span class="badge badge-info">제휴</span>';
    }elseif($boardstat == 3){
        $boardcat = '<span class="badge badge-secondary">도움말</span>';
        $nowrite = true;
    }
}
    if(1 > mysqli_num_rows($result)){
    echo '<script>alert("없는 게시판입니다.")</script>';
    include_once 'down.php';
    exit;
}

        $kpr = strpos($keeper, $_SESSION['userid']);
        if($kpr === true){
            $sql = "SELECT * FROM `_userRights` WHERE `type` like '3'";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
                if($row['editBoardInfo'] == 1){
                    $editBoard = true;
                }
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
            }
        }
        if($owner === $_SESSION['userid']){
            $editBoard = true;
            $isOwner = true;
        }

    if($editBoard !== TRUE){
        echo '접근 권한 없음';
        exit;
    }
?>

<h3><?=$boardcat.' '.$boardname.' '.$boardsuf?> 관리 페이지</h3>

<h5>정보 수정</h5>
    <form method="post" action="/admin.php?form=submit">
    <div class="form-group">
        <label for="name"><?=$boardsuffix?> 이름</label>
        <input type="name" class="form-control" placeholder="<?=$boardname?>" disabled>
        <small class="form-text text-muted">이름은 수정이 불가능합니다.</small>
    </div>
    <div class="form-group">
        <label for="name"><?=$boardsuffix?> 설명</label>
        <textarea name="exp" class="form-control"><?=$boardtext?></textarea>
    </div>
    <div class="form-group">
        <label for="name"><?=$boardsuffix?> 해시태그</label>
        <input name="hashTag" class="form-control" placeholder="<?=$boardname.' '.$boardsuffix?>의 특성을 표현할 태그를 적어주세요. "
         value="<?=$boardtag?>">
    </div>
    <input type="hidden" name="b" value="<?=$from?>">
    <button type="submit" class="btn btn-primary" style="float:right">저장</button>
    </form>

<?php
include 'down.php';
?>