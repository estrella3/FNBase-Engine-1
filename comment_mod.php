<?php
require 'up.php';
$ip = $_SERVER['REMOTE_ADDR'];
$a = FnFilter($_GET['a']);
$id = FnFilter($_GET['n']);

if($a == 'edit'){
    $uid = $_SESSION['userid'];
    $sql = "SELECT * FROM `_comment` WHERE `num` LIKE '$id' AND `id` LIKE '$uid'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
    $c = $row['content'];
    }
    echo '<form action="comment_mod.php?a=save&n='.$id.'" method="post">
    <textarea class="border text-dark" style="width:100%" name="ec">'.$c.'</textarea><br>
    <button type="submit" style="width:100%" class="btn btn-success">수정 완료</button></form>';
    include 'down.php';
}
if($a == 'save'){
    $c = $_POST['ec'];
    $sql = "UPDATE `_comment` set content = '$c', edited = 1 WHERE `num` LIKE '$id'";
    $result = mysqli_query($conn, $sql);
    if($result === FALSE){
    echo "데이터베이스 접속 오류";
    }

    echo '<script>history.go(-2)</script>';
}
if($a == 'delete'){
    if($_GET['c'] == 'ok'){
        $sql = "SELECT * FROM `_comment` WHERE `num` LIKE '$id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)){
        $uid = $row['id'];
        $b = $row['board'];
        $o = $row['original'];
        }
        if($uid === $_SESSION['userid']){
            $sql = "UPDATE `_comment` set id = '', name = '', content = '<sup><b><mark>*삭제됨</mark></b></sup>' WHERE `num` LIKE '$id'";
            $result = mysqli_query($conn, $sql);
            if($result === FALSE){
                print '삭제 실패, 데이터베이스 수정 불가';
            }else{
                $sql = "SELECT * FROM `_article` WHERE `id` LIKE '$o' AND `from` LIKE '$b'";
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($result)){
                $c = $row['comment'];
                }
                $c = $c - 1;
                $sql = "UPDATE `_article` set comment = $c WHERE `id` LIKE '$o' AND `from` LIKE '$b'";
                $result = mysqli_query($conn, $sql);
                if($result === FALSE){
                echo "데이터베이스 접속 오류";
                }

                echo '<script>history.go(-2)</script>';
            }
        }
    }else{
            echo '<div class="container"><form action="comment_mod.php?a=delete&c=ok&n='.$id.'" method="post">
            <h2>삭제하면 되돌릴 수 없습니다!</h2>
            <p>정말 삭제하시겠습니까?<button type="button" class="btn-sm btn-warning" onclick="history.back()">뒤로가기</button><button class="btn-sm btn-danger" type="submit">삭제하기</button></p>
            <input type="hidden" value="bring" name="me thanos">
            </form></div>';
            include 'down.php';
        }
}
if($a == 'push'){
    $sql = "SELECT * FROM `_push` WHERE `id` = '$id' AND `type` = 'comment'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
    if($row['ip'] == $ip){
        echo "<script>alert('추천 / 비추천은 한 게시글 당 한번만 가능합니다!'); history.go(-1)</script>";
        exit;
    }
    }
    
    $sql = "SELECT * FROM `_comment` WHERE `num` LIKE '$id'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
    $stat = $row['stat'];
    $userid = $row['id'];
    }

    $stat = $stat + 1;
    
    $sql = "UPDATE `_comment` set stat = $stat WHERE `num` LIKE '$id'";
    $result = mysqli_query($conn, $sql);
    if($result === FALSE){
        echo "데이터베이스 접속 오류";
    }else{
        $sql = "
      INSERT INTO _push
        (id, ip, b, type)
        VALUES(
            '{$id}',
            '{$ip}',
            '{$b}',
            'comment'
        )
    ";
    $result = mysqli_query($conn, $sql);
    if($result === FALSE){
        echo "데이터베이스 접속 오류";
    }
    print '<script>history.back()</script>';
}
}
if($a == 'blame'){
    $sql = "SELECT * FROM `_push` WHERE `id` = '$id' AND `type` = 'comment'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
    if($row['ip'] == $ip){
        echo "<script>alert('추천 / 비추천은 한 게시글 당 한번만 가능합니다!'); history.go(-1)</script>";
        exit;
    }
    }
    
    $sql = "SELECT * FROM `_comment` WHERE `num` LIKE '$id'";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
    $stat = $row['blame'];
    $userid = $row['id'];
    }

    $stat = $stat + 1;
    
    $sql = "UPDATE `_comment` set blame = $stat WHERE `num` LIKE '$id'";
    $result = mysqli_query($conn, $sql);
    if($result === FALSE){
        echo "데이터베이스 접속 오류";
    }else{
        $sql = "
      INSERT INTO _push
        (id, ip, b, type)
        VALUES(
            '{$id}',
            '{$ip}',
            '{$b}',
            'comment'
        )
    ";
    $result = mysqli_query($conn, $sql);
    if($result === FALSE){
        echo "데이터베이스 접속 오류";
    }
    print '<script>history.back()</script>';
}
}
?>