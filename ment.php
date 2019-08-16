<?php
require 'function.php';
if(!empty($_SESSION['userck'])){
    $p = $_SERVER['REMOTE_ADDR']; //아이피
    $b = FnFilter($_POST['from']); //상위 게시판
    $m = FnFilter($_POST['id']); //글 번호
    $t = $_POST['title']; //원글 제목
    $i = $_SESSION['userid'];  //부른 사람
    $n = $_SESSION['userck'];
    $a = FnFilter($_POST['mentID']); //부를 사람
        $sql = "SELECT * FROM `_account` WHERE `id` LIKE '$a'";
        $result = mysqli_query($conn, $sql);
        if($result === FALSE){
            echo '<script>alert("없는 사용자입니다.");history.back()</script>';
            exit;
        }
        while($row = mysqli_fetch_array($result)){
            $a_nick = $row['id'];
        }
    $r = FnFilter($_POST['mentReason']);

    $sql = "SELECT * from `_ment` WHERE `name` = '$i' ORDER BY `time` DESC limit 1";
    $result = mysqli_query($conn, $sql);
    date_default_timezone_set('Asia/Seoul');
    if(mysqli_num_rows($result) < 1){
        $CaValue = 0;
    }
    while($row = mysqli_fetch_array($result)){
        $time = strtotime($row['time']);
        $CaValue = strtotime(date("Y-m-d H:i:s")) - $time;
    }
    if($CaValue < 60){
        $Value = 60 - $CaValue;
        echo '<script>alert("'.$CaValue.'초 뒤에 다시 시도해주세요."); history.back()</script>';
        exit;
    }
            if($i !== $a){
            $linktxt = $fnSite.'/b/'.$b.'/1/'.$m;
            $msgtxt = "[$n]님이 [$t] 게시글에 [$a_nick]님을 호출하셨습니다.";
            $sql = "INSERT INTO `_ment` (`name`, `to`, `read`, `msg`, `link`, `type`) VALUES ('$i', '$a', '0', '$msgtxt', '$linktxt', 'ment')";
            $result = mysqli_query($conn, $sql);
            $a = '<a href="/user.php?a='.$a.'">'.$a_nick.'</a>';
            
            $sql = "
        INSERT INTO `_comment`
            (board, original, id, name, content, remarks, stat, created, blame, secret, reply, ment)
            VALUES(
                '{$b}',
                '{$m}',
                '{$i}',
                '{$n}',
                '{$a}',
                '{$r}',
                '0',
                NOW(),
                '0',
                '1',
                '0',
                '1'
            )
        ";
        $result = mysqli_query($conn, $sql);
        if($result === false){
        echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
        error_log(mysqli_error($conn));
        }
        $sql = "SELECT * FROM `_article` WHERE `id` LIKE '{$m}' and `from` like '{$b}'";
        $result = mysqli_query($conn, $sql);
        while($raw = mysqli_fetch_array($result)){
        $comment = $raw['comment'];
        }

        $comment = $comment + 1;
        $sql = "UPDATE `_article` set comment = '{$comment}' where `id` like '{$m}' and `from` like '{$b}'";
        $result = mysqli_query($conn, $sql);
        if($result === false){
        echo '데이터베이스 연결 오류';
        }
}else{
    echo '<script>alert("자기 자신을 호출할 수 없습니다!")</script>';
}
    echo '<script>history.go(-1)</script>';
}else{
    echo '<script>alert("로그인이 필요합니다.");history.back()</script>';
    exit;
}
?>