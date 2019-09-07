<?php
require 'function.php';
        $id = $_POST['id'];
        $pw = Filt($_POST['pwd']);
        $pwdc = Filt($_POST['pwd_check']);
        $email = $_POST['email'];
        $name = Filt($_POST['name']);
        $uip = $_POST['ip'];
        $intro = Filt($_POST['intro']);
        if(empty($id)){
                ?><script>alert('id가 비어있습니다.');history.back();</script><?php
        }elseif(empty($pw)){
                ?><script>alert('비밀번호가 비어있습니다.');history.back();</script><?php
        }elseif(empty($email)){
                ?><script>alert('메일 주소가 비어있습니다.');history.back();</script><?php
        }elseif(empty($name)){
                ?><script>alert('닉네임이 비어있습니다.');history.back();</script><?php
        }elseif($pw !== $pwdc){
                ?><script>alert('비밀번호가 일치하지 않습니다.');history.back();</script><?php
        }else{
                $pw = password_hash($pw, PASSWORD_BCRYPT);
        //입력받은 데이터를 DB에 저장
        $query = "INSERT INTO `_account` (`id`, `pw`, `name`, `email`, `cert`, `point`, `UIP`, `introduce`, `ext`, `whyibanned`) 
        VALUES ('$id', '$pw', '$name', '$email', '1', '0', '$uip', '$intro', '0', '')";
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