<?php
require 'up.php';
if(empty($_SESSION['userck'])){
    echo '<script>alert("로그인 후 이용 바랍니다.")</script>';
    exit;
}
$user = $_SESSION['userid'];
$userck = $_SESSION['userck'];
echo '<h3>'.$userck.'님, 반갑습니다.</h3>';

$sql = "SELECT * from `_account` WHERE `id` like '".$user."'";
$result = mysqli_query($conn, $sql);
if(1 > mysqli_num_rows($result)){
     echo '<script>alert("존재하지 않는 사용자입니다."); history.go(-1)</script>';
}
while($row = mysqli_fetch_array($result)){
    $user_id = $row['id'];
    $user_nickname = $row['name'];
    $user_email = $row['email'];
    $user_intro = $row['introduce'];
    $ban = $row['ext'];
}

echo '<h5>정보 수정</h5>';
$m = 'title';
echo '<table class="table table-striped"><thead>
    <tr>
      <th scope="col">항목</th>
      <th scope="col">내용</th>
    </tr></thead><tbody>';
echo '<tr>';
echo '<td>아이디/닉네임</td><td>'.$user_id.' / '.$user_nickname.'</td>';
echo '</tr>';
echo '<tr>';
if($user_intro == ''){
	$user_intro = '<span style="color:gray">없음</span>';
}
echo '<td>본인 소개</td><td>'.$user_intro.'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>가입한 이메일 주소</td><td>'.$user_email.'</td>';
echo '</tr>';
echo '<tr><td><button class="btn btn-light" onclick="activeForm()">회원 정보 수정</button></td></tr></tbody></table>';
echo '';
echo '<script>
function activeForm() {
    var a = document.getElementById("form");
    if(a.style.display=="none"){
        a.style.display = "";
    }else{
        a.style.display = "none";
    }
}
</script>
<span id="form" style="display:none">
    <form method="post" action="tools.php?form=submit">';
    echo '<table class="table table-striped"><tr>';
    echo '<td>아이디</td><td><input disabled class="form-control" value="'.$user.'"></td></tr>
    <tr><td>닉네임</td><td><input name="nickname" class="form-control" value="'.$user_nickname.'">
    <input name="original" type="hidden" value="'.$user_nickname.'">
    <small>\'_\'을 제외한 특수 문자는 허용하지 않습니다. 닉네임은 겹쳐도 무방합니다.</small></td></tr>';
    echo '</tr>';
    echo '<tr>';
    if($user_intro == ''){
        $user_intro = '';
        $if_user_intro_empty = 'placeholder="아직 소개문구가 없습니다."';
    }
    echo '<td>본인 소개</td><td><textarea name="intro" class="form-control"'.$if_user_intro_empty.'>'.$user_intro.'</textarea></td>';
    echo '</tr>';
    echo '<tr><td>비밀번호 변경</td><td><input class="form-control" type="password" placeholder="현재 비밀번호 확인" name="prevPWD">
    <input class="form-control" type="password" placeholder="새 비밀번호 입력" name="newPWD">
    <input class="form-control" type="password" placeholder="새 비밀번호 다시 입력" name="newPWDconf"></td></tr>';
    echo '<tr><td>이메일 주소 변경</td><td>
        <input class="form-control" type="email" placeholder="변경할 메일 주소를 입력" name="newMailAddr">
    </td></tr>';
    echo '<tr><td><button type="submit" class="btn btn-secondary">수정 완료</button></td></tr></span></table>';
echo '</form></span>';
if($_GET['form'] == 'submit'){
    echo '저장중...';
    $intro = Filt($_POST['intro']);
    $nick = Filt($_POST['nickname']);
        if($_POST['original'] == $nick){
            $sql = "UPDATE `_account` set introduce = '{$intro}' where `id` like '{$user}'";
            $result = mysqli_query($conn, $sql);
            if($result === false){
            echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
            exit;
            } else {
                $result_up = $result;
            }
        }else{
            $re = '/[^가-힣, ㄱ-ㅣ, a-z, A-Z, à-ź, À-Ź, 0-9, _, ]/m';
            $nick = preg_replace($re, '', $nick);

            $sql = "UPDATE `_account` set introduce = '{$intro}', name = '{$nick}' where `id` like '{$user}'";
            $result = mysqli_query($conn, $sql);
            if($result === false){
            echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
            exit;
            }else {
                $result_up = $result;
            }
        }
    $pwd = $_POST['newPWD'];
    $pwd = mysqli_real_escape_string($conn, $pwd);
    $pwdc = $_POST['newPWDconf'];
    $pwdc = mysqli_real_escape_string($conn, $pwdc);
    $pwdo = $_POST['prevPWD'];
    $pwdo = mysqli_real_escape_string($conn, $pwdo);
        if(!empty($pwd)){
            if($pwd == $pwdc){
                if($pwdo == $_SESSION['userpw']){
                    $pw = password_hash($pwd, PASSWORD_BCRYPT);
                    $sql = "UPDATE `_account` set pw = '$pw' where `id` like '$user'";
                    $result = mysqli_query($conn, $sql);
                    if($result === false){
                        echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
                        exit;
                    }
                }else{
                    echo '<script>alert("변경 전 비밀번호를 다시 한 번 확인해주세요.")</script>';
                    exit;
                }
            }else{
                echo '<script>alert("새로운 비밀번호를 다시 한 번 확인해주세요. 입력하신 새 비밀번호와 새 비밀번호 확인의 값이 다릅니다.")</script>';
                exit;
            }
        }
    if($result_up === TRUE){
        session_unset();
        session_destroy();
        echo "<script>alert('변경 완료! 반영을 위해 다시 로그인 해주세요.'); location.replace('/login.php')</script>";
        exit;
    }
}

$sql = "SELECT * from `_userSetting` WHERE `id` like '".$user."'";
$result = mysqli_query($conn, $sql);
if(1 > mysqli_num_rows($result)){
    $sql = "INSERT INTO `_userSetting` (`id`, `showAlerts`) VALUES ('$user', '1');";
    $result = mysqli_query($conn, $sql);
    $sql = "SELECT * from `_userSetting` WHERE `id` like '".$user."'";
    $result = mysqli_query($conn, $sql);
}

echo '<h5>개인 설정</h5>';
echo '<form method="post" action="tools.php?form=set"> <table class="table table-striped"><thead>
    <tr>
      <th scope="col">항목</th>
      <th scope="col">내용</th>
    </tr></thead><tbody>';
echo '<tr>';
if($showAlerts === '1'){
    $showAlertsCheckBox = '예 : <input type="radio" class="form-control" name="SA" value="yes" checked>
    아니오 :<input type="radio" class="form-control" name="SA" value="no">';
}else{
    $showAlertsCheckBox = '예 : <input type="radio" class="form-control" name="SA" value="yes">
    아니오 : <input type="radio" class="form-control" name="SA" value="no" checked>';
}
echo '<td style="width:50%">알림 허용</td><td>'.$showAlertsCheckBox.'</td>';
echo '</tr>';
echo '<tr><td><button class="btn btn-secondary" type="submit">회원 설정 변경</button></td></tr></tbody></table></form>';

if($_GET['form'] == 'set'){
    if($_POST['SA'] == 'yes'){
        $sql = "UPDATE `_userSetting` set showAlerts = '1' where `id` like '{$user}'";
        $result = mysqli_query($conn, $sql);
    }elseif($_POST['SA'] == 'no'){
        $sql = "UPDATE `_userSetting` set showAlerts = '0' where `id` like '{$user}'";
        $result = mysqli_query($conn, $sql);
    }
    if($result === false){
        echo '데이터베이스에 저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
    } else {
        echo "<script>alert('수정 완료!'); history.go(-2)</script>";
    }
}

if($ban == 9){
    echo '<h5>사이트 전체 설정</h5>';
    echo '<hr><a href="/modify.php" class="btn btn-primary" style="display:block">바로가기</a>';
}

include 'down.php';
?>
