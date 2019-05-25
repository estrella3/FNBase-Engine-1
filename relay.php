<?php

if($ment !== ''){
  $ment = explode(",", $ment);
  $cnt = count($ment);
  $i = 0;
  for($count = 1 ; $count <= $cnt ; $count++){
    $mentck = $ment[$i];
    $linktxt = $fnSite.'/b/'.$i.'/1/'.$i;
    $msgtxt = "\"$uck\"님이 \"$mentck\"님을 \"bb\"에서 불렀어요.";
    $sql = "INSERT INTO `_ment` (`name`, `to`, `read`, `msg`, `link`, `type`) VALUES ('$uck', '$mentck', '0', '$msgtxt', '$linktxt', 'ment')";
    $result = mysqli_query($conn, $sql);
    $i++;
  }
}
?>