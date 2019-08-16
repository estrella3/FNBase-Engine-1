<?php
$ip_ori = $_SERVER['REMOTE_ADDR'];
$ip_arr = explode('.', $ip_ori);
$ip = $ip_arr[0].'.'.$ip_arr[1];
$sql = "SELECT * FROM `_ipban` where `ip` like '$ip%'";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)==1) {
    echo '<html><head><title>차단됨 - '.$fnSiteName.' - FNBE</title></head><body><h1>접근 제한</h1>
    <p>귀하의 아이피 주소는 '.$fnSiteName.'에서 차단되었습니다.</p>
    <p>부당하게 차단되셨다고 생각하시나요? '.$fnSiteEmail.'로 문의하세요.</p></body><footer>';
    session_start();
    session_destroy();
    echo '<br>'.$ip_ori.'<p align="center" style="text-shadow: 1px 1px 1px gray;">proudly powered by 
    <span style="color:royalblue">FNB</span>ase <span style="color:yellow">E</span>ngine</p></footer></html>';
    exit;
}
?>