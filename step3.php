<?php
require "up.php";



function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$code = generateRandomString(64);
$to = Filt($_POST['email']);
$id = Filt($_POST['id']);
$ip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT * FROM `_account` WHERE `email` like '$to'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0){
    echo '<h5>이미 등록된 이메일입니다.</h5>';
    echo '<script>alert("이미 등록된 이메일입니다.");history.back()</script>';
    exit;
}

$sql = "
  INSERT INTO `_mailAuth`
    (`id`, `email`, `ip`, `code`)
    VALUES(
        '{$id}',
        '{$to}',
        '{$ip}',
        '{$code}'
    )
";
$result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM `_mailAuth` WHERE `ip` like '$ip' and `code` like '$code'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
    $code = $row['code'];
    $num = $row['num'];
}

$link = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/step4.php?code='.$code.'&num='.$num;

$subject = $id."님, 이메일 주소를 인증해주세요.";
$contents = '<html><body>
<div style="background-color:skyblue;color:white">
<h3>이메일 인증</h3>
<hr>
<p>
<b>'.$id.'</b>(을)를 회원님이 등록하신게 맞다면,
아래 링크를 클릭하여 이동해주세요.
<a href="'.$link.'">이어서 진행하기</a><br>
가입이 계속 진행될 것입니다.
</p>
</div>
<span style="color:gray;float:right">--'.$fnSiteName.'</span>
</body>
</html>';
$headers = "From: noreply@".$_SERVER['SERVER_NAME']."\r\n";
$headers .= 'Content-Type: text/html; charset=utf-8'."\r\n";

mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $contents, $headers);
?>
<h4>이메일 인증</h4>
<p class="text-muted">이메일 주소를 인증하는 단계입니다.</p><hr>
<div class="container">
                <legend><h3>Step 3</h3></legend>
                <fieldset>
                        <p style="color:gray">혹시 메일이 오지 않으셨나요? 스팸 메일로 분류되었을 수도 있습니다.<br>
                    스팸 메일함을 확인해보세요.</p>
                </fieldset>
        </div>
<?php
        require "down.php";
?>