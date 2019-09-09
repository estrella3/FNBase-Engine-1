<?php
require 'up.php';

if($ban == 9){
    if(!empty($_POST['name'])){
            $url = Filt($_POST['url']);
            $name = Filt($_POST['name']);
            $desc = Filt($_POST['desc']);
            $tz = Filt($_POST['timezone']);
            $main = Filt($_POST['main']);
            $footer = $_POST['footer'];
            $menu = $_POST['menu'];
            $fab = Filt($_POST['fab']);
            $suf = Filt($_POST['suffix']);
            $google = $_POST['google'];
            $color = Filt($_POST['color']);
            $subcolor = Filt($_POST['subcolor']);
            $email = Filt($_POST['email']);
                $sql_ = "UPDATE `_Setting` SET `Site`='$url',`SiteName`='$name',`SiteColor`='$color',`SiteSubColor`='$subcolor',
                `SiteDesc`='$desc',`SiteFab`='$fab',`SiteEmail`='$email',`SiteBoardList`='$menu',`SiteFooter`='$footer',`SiteBoardSuffix`='$suf',
                `Site_google`='$google',`SiteTimezone`='$tz' WHERE `num` like 1";
                $result_ = mysqli_query($conn, $sql_);
                if($result_ === FALSE){
                    echo '데이터베이스 접근 에러';
                    exit;
                }else{
                    echo '<script>alert("적용 완료");history.go(-2)</script>';
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