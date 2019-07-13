<?php
include 'setting.php';
include 'function.php';
session_start();
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
if(!empty($_SESSION['userck'])){
    $p = $_SERVER['REMOTE_ADDR']; //아이피
    $b = FnFilter($_POST['from']); //상위 게시판
    $m = FnFilter($_POST['id']); //글 번호
    $t = $_POST['title']; //원글 제목
    $n = $_SESSION['userck'];  //부른 사람
    $a = FnFilter($_POST['mentNickname']); //부를 사람

            if($_SESSION['userck'] !== $a){
            $linktxt = $fnSite.'/b/'.$b.'/1/'.$m;
            $msgtxt = "[$n]님이 [$t] 게시글에 [$a]님을 호출하셨습니다.";
            $sql = "INSERT INTO `_ment` (`name`, `to`, `read`, `msg`, `link`, `type`) VALUES ('$n', '$a', '0', '$msgtxt', '$linktxt', 'ment')";
            $result = mysqli_query($conn, $sql);
            $a = '<a href="/user.php?a='.$a.'">'.$a.'</a>';
            $id = $_SESSION['userid'];
            $sql = "
        INSERT INTO `_comment`
            (board, original, id, name, content, stat, created, blame, secret, reply, ment)
            VALUES(
                '{$b}',
                '{$m}',
                '{$id}',
                '{$n}',
                '{$a}',
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
}
    echo '<script>history.go(-1)</script>';
}else{
    echo '<script>alert("로그인이 필요합니다.");history.back()</script>';
    exit;
}
?>