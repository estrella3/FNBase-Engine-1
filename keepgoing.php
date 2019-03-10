<?php
function HTTT($id){
        $id = str_replace('<', 'n', $id);
        $id = str_replace(';', 'n', $id);
        $id = str_replace('}', 'n', $id);
        $id = str_replace('/', 'n', $id);
        $id = str_replace('*', 'n', $id);
        $id = str_replace('+', 'n', $id);
        $id = str_replace('-', 'n', $id);
        $id = str_replace('=', 'n', $id);
        $id = str_replace('(', 'n', $id);
        $id = str_replace('&', 'n', $id);
        $id = str_replace('^', 'n', $id);
        $id = str_replace('%', 'n', $id);
        $id = str_replace('$', 'n', $id);
        $id = str_replace('#', 'n', $id);
        $id = str_replace('@', 'n', $id);
        $id = str_replace('!', 'n', $id);
        $id = str_replace('`', 'n', $id);
        $id = str_replace('~', 'n', $id);
        $id = str_replace('"', 'n', $id);
        $id = str_replace("'", 'n', $id);
        return $id;
        }
        $conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
        $id = HTTT($_POST['id']);
        $pw = HTTT($_POST['pw']);
        $email = HTTT($_POST['email']);
        $nickname = HTTT($_POST['nickname']);
        $uip = HTTT($_POST['UIP']);
        $id = mysqli_real_escape_string($conn, $id);
        $pw = mysqli_real_escape_string($conn, $pw);
        $email = mysqli_real_escape_string($conn, $email);
        $nickname = mysqli_real_escape_string($conn, $nickname);
        $uip = mysqli_real_escape_string($conn, $uip);
        if(empty($id)){
                ?><script>alert('id가 비어있습니다.');history.back();</script><?php
        }elseif(empty($pw)){
                ?><script>alert('비밀번호가 비어있습니다.');history.back();</script><?php
        }elseif(empty($email)){
                ?><script>alert('메일 주소가 비어있습니다.');history.back();</script><?php
        }elseif(empty($nickname)){
                ?><script>alert('닉네임이 비어있습니다.');history.back();</script><?php
        }else{
                $pw = password_hash($pw, PASSWORD_BCRYPT);
        //입력받은 데이터를 DB에 저장
        $query = "INSERT into _account (id, pw, name, email, at, cert, point, UIP) values ('$id', '$pw', '$nickname', '$email', NOW(), 0, 0, '$uip')";
        $result = $conn->query($query);
        //저장이 됬다면 (result = true) 가입 완료
        if($result) {
        ?>      <script>
                alert('가입 되었습니다.');
                location.replace("./login.php?from=keepgoing");
                </script>
<?php   }
        else{
?>              <script>
                        alert("가입에 실패하였습니다!");
                </script>
<?php   }
        }
        mysqli_close($conn);
?>