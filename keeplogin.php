<?php
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");
        session_start();
 
        $connect = $conn;
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
        //입력 받은 id와 password
        $id = HTTT($_POST['id']);
        $pw = HTTT($_POST['pw']);
        $id = mysqli_real_escape_string($conn, $id);
        $pw = mysqli_real_escape_string($conn, $pw);
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
                                        ?>      <script>
                                        alert("로그인 되었습니다.");
                                        history.go(-2);
                                </script>
<?php
                        }}
                        else{
                                echo "session fail";
                        }
                }
 
                else {
        ?>              <script>
                                alert("아이디 혹은 비밀번호가 잘못되었습니다.");
                                history.back();
                        </script>
        <?php
                }
 
        }
 
                else{
?>              <script>
                        alert("아이디 혹은 비밀번호가 잘못되었습니다.");
                        history.back();
                </script>
<?php
        }

include "../include_down.php"; 
?>