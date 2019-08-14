<?php
require 'function.php';
if(empty($_POST['mode'])){
if(!empty($_SESSION['userid'])){
    $p = $_SERVER['REMOTE_ADDR']; //아이피
    $b = FnFilter($_POST['from']); //상위 게시판
    $m = FnFilter($_POST['id']); //글 번호
    $t = $_POST['title']; //원글 제목
    $n = $_SESSION['userid'];  //부른 사람
    $a = FnFilter($_POST['a']); //부를 사람
        $sql = "SELECT * FROM `_account` WHERE `id` LIKE '$a'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)){
            $a_ck = $row['name'];
        }
    $r = FnFilter($_POST['knockReason']);

    if(empty($t)){
        exit;
    }

            if($_SESSION['userid'] !== $a){
            $linktxt = $fnSite.'/b/'.$b.'/1/'.$m;
            $msgtxt = "[$n]님이 [$t] 게시글을 보고싶어합니다.";
            $sql = "INSERT INTO `_ment` (`name`, `to`, `read`, `msg`, `link`, `type`) VALUES ('$n', '$a', '0', '$msgtxt', '$linktxt', 'knock')";
            $result = mysqli_query($conn, $sql);
            $a = '<a href="/user.php?a='.$a.'">'.$a_ck.'</a>';
            $id = $_SESSION['userid'];
            $sql = "
        INSERT INTO `_comment`
            (board, original, id, name, content, remarks, stat, created, blame, secret, reply, ment)
            VALUES(
                '{$b}',
                '{$m}',
                '{$id}',
                '{$n}',
                '{$a}',
                '{$r}',
                '0',
                NOW(),
                '0',
                '1',
                '0',
                '2'
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
}
    echo '<script>history.go(-2)</script>';
}else{
    echo '<script>alert("로그인이 필요합니다.");history.back()</script>';
    exit;
}
}elseif($_POST['mode'] === 'accept'){
    $f = $_POST['from'];
    $i = $_POST['id'];
    $h = FnFilter($_POST['by']);
    $n = FnFilter($_POST['num']);
    
    $sql = "SELECT * FROM `_article` WHERE `id` = {$i} and `from` = '{$f}';";
    $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)){
            $v = $row['issectxt'];
        }
    $v = $v.', '.$h;
    $sql = "UPDATE `_article` SET `issectxt` = '$v', `issec` = '2' WHERE `id` = '$i' and `to` = '$f';";
    $result = mysqli_query($conn, $sql);
    if($result === false){
    echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
    error_log(mysqli_error($conn));
    }else{
        $sql = "UPDATE `_comment` SET `ment` = '3' WHERE `num` = '$n';";
        $result = mysqli_query($conn, $sql);
        echo '<script>history.back()</script>';
        exit;
    }
}
?>