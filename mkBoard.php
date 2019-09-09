<?php
require 'up.php';

if($ban == 9){
    if(!empty($_POST['name'])){
            $slug = Filt($_POST['slug']);
            $name = Filt($_POST['name']);
            $suf = Filt($_POST['suf']);
            $text = $_POST['text'];
                $sql_ = "INSERT INTO `_board` (`num`, `id`, `name`, `suffix`, `owner`, `keeper`, `volunteer`, `text`, `hashtag`, `sub`, `stat`)
                 VALUES (NULL, '$slug', '$name', '$suf', '$u_id', NULL, NULL, '$text', '', '0', '1')";
                $result_ = mysqli_query($conn, $sql_);
                if($result_ === FALSE){
                    echo '데이터베이스 접근 에러';
                    exit;
                }else{
                    echo '<script>alert("게시판 생성 완료");location.href="./b/'.$slug.'"</script>';
                    exit;
                }
    }else{
        echo '<script>alert("내용을 입력해주세요.");history.back();</script>';
        exit;
    }
}else{
    echo '<script>alert("사이트 소유주만 이용 가능합니다.");history.back();</script>';
    exit;
}
?>