<?php
require 'function.php';

if(!empty($_SESSION['userid'])){
    if(!empty($_POST['name'])){
        $sql = "SELECT * FROM `_account` WHERE `point` > 1000 and `id` like '$u_id'";
        $result = mysqli_query($conn, $sql);
        if(1 == mysqli_num_rows($result)){
            $name = Filt($_POST['name']);
            $suf = Filt($_POST['suffix']);
            $exp = Filt($_POST['exp']);
            $slug = Filt($_POST['slug']);
            $priv = Filt($_POST['priv']);
                $sql_ = "INSERT INTO `_board` (`id`, `name`, `suffix`, `owner`, `text`, `stat`) 
                VALUES ('$slug', '$name', '$suf', '$u_id', '$exp', '0')";
                $result_ = mysqli_query($conn, $sql_);
                if($result_ === FALSE){
                    echo '데이터베이스 접근 에러';
                    exit;
                }else{
                        $sql__ = "UPDATE `_account` SET `point` = `point` - 1000 WHERE `_account`.`id` = '$u_id'";
                        $result__ = mysqli_query($conn, $sql__);
                    echo '<script>alert("'.$suf.'이(가) 생성되었습니다.");location.href="/b/'.$slug.'";</script>';
                    exit;
                }
        }else{
            echo '<script>alert("포인트가 부족합니다.");history.back();</script>';
            exit;
        }
    }else{
        echo '<script>alert("내용을 입력해주세요.");history.back();</script>';
        exit;
    }
}else{
    echo '<script>alert("회원가입 후 이용해주세요.");history.back();</script>';
    exit;
}
?>