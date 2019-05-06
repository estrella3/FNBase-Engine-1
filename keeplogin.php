<?php
include 'setting.php';
if(empty($_POST['id'])){
        echo '잘못된 접근';
        exit;
}
$ip = $_SERVER['REMOTE_ADDR'];
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
        session_start();
 
        $connect = $conn;
        function idpw($id){
                $id = str_replace('<', '0', $id);
                $id = str_replace(';', '0', $id);
                $id = str_replace('}', '0', $id);
                $id = str_replace('/', '0', $id);
                $id = str_replace('\\', '0', $id);
                $id = str_replace('+', '0', $id);
                $id = str_replace('|', '0', $id);
                $id = str_replace('=', '0', $id);
                $id = str_replace('(', '0', $id);
                $id = str_replace('&', '0', $id);
                $id = str_replace('"', '0', $id);
                $id = str_replace("'", '0', $id);
                return $id;
                } 
        //입력 받은 id와 password
        $id = idpw($_POST['id']);
        $pw = idpw($_POST['pw']);
        //아이디가 있는지 검사
        $query = "select * from _account where id='$id'";
        $result = $connect->query($query);


 
        //아이디가 있다면 비밀번호 검사
        if(mysqli_num_rows($result)==1) {
 
                $row=mysqli_fetch_assoc($result);
                $hash = $row['pw'];
                //비밀번호가 맞다면 세션 생성
                if(password_verify($pw, $hash)){
                        if($_POST['autologin']){
                                setcookie('keeplogin', 'FNBase.xyz', time() + 86400 * 31);
                                setcookie('keeplogin-id', "$id", time() + 86400 * 32);
                                setcookie('keeplogin-password', "$pw", time() + 86400 * 32);
                        }
                        $_SESSION['userid']=$id;
                        $_SESSION['userpw']=$pw;
                        $_SESSION['userck']=$row['name'];
                        if(isset($_SESSION['userid'])){
                                if($_POST['petCheetar'] == 'Jason'){
                        ?>      <script>
                                        alert("<?=$fnSiteName?>에 오신 것을 환영합니다!");
                                        window.location.href = '<?=$fnSite?>';
                                </script>
<?php                                        
                                }else{
                                        $right = TRUE;
                                        $go = 1;
                        }}
                        else{
                                echo "session fail";
                        }
                }
 
                else {
                        $right = FALSE;
                        $go = 2;
                }
 
        }else{
                $right = 'empty';
?>              
<?php
        }

if($right == TRUE){
        $sql = "
  INSERT INTO `_log`
    (`ip`,`id`,`right`,`type`,`at`)
    VALUES(
        '{$ip}',
        '{$id}',
        '1',
        '1',
        NOW()
    )
";
$result = mysqli_query($conn, $sql);
if($result === false){
        echo '데이터베이스 연결 실패';
}
}elseif($right = FALSE){
        $sql = "
  INSERT INTO `_log`
    (`ip`,`id`,`right`,`type`,`at`)
    VALUES(
        '{$ip}',
        '{$id}',
        '0',
        '1',
        NOW()
    )
";
$result = mysqli_query($conn, $sql);
if($result === false){
        echo '데이터베이스 연결 실패';
}
}elseif($right = 'empty'){
        echo '없는 아이디';
}else{
        echo '알 수 없는 동작 이상 발생';
}

if($go == 1){
        echo '<script>
        alert("로그인 되었습니다.");
        history.go(-2);
</script>';
}
if($go == 2){
        echo '<script>
        alert("아이디 혹은 비밀번호가 잘못되었습니다.");
        history.back();
</script>';
}
if($go == 3){
        echo '<script>
        alert("존재하지 않는 아이디입니다.");
        history.back();
</script>';
}
?>