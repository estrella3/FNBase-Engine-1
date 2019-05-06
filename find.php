<?php
include "up.php";
include 'function.php';
$id = $_POST['id'];
$sql = "SELECT * FROM _account WHERE `id` LIKE '$id'";
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

if ($count == 0){
echo '<div class="container"><div style="height: 50px; width: 100%">
<p>&shy;</p>
</div>
                <form method="post" action="going.php">
                <legend><h3>Step 1</h3></legend>
                <fieldset>
                        <p>아이디: <input type="text" value="'.$id.'" name="id" readonly></p>
                                <input type="submit" value="다음 단계로">
                                <span id="chkt" style="color: gray; font-size: 0.8em">아이디 중복 확인이 완료되었습니다.</span>
                </fieldset>
                </form></div>';
}else{
echo '<div style="height: 200px; width: 100%">
<p>&shy;</p>
</div><p align="center"><span style="color: red;">이 아이디는 사용하실 수 없습니다.</span><button onclick="history.back();">뒤로가기</button></p><div style="height: 200px; width: 100%">
<p>&shy;</p>
</div></div></div>';
}
        include "down.php";
?>