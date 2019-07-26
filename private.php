<?php
require 'up.php';
echo '<hr><h2>전체 게시판 목록</h2><hr>';
echo '<span style="float:right" class="text-muted">관리진이 직접 운영하는 채널입니다.</span>';
echo '<h4>공설 게시판</h4>';

echo '<br><table class="table table-sm"><thead class="thead-light"><tr><th>이름</th><th>#태그</th></tr></thead><tbody>';

$sql = "SELECT * FROM `_board` WHERE `stat` like '1'";
$result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
        $name = $row['name'];
        $hashtag = $row['hashtag'];
        $suffix = ' '.$row['suffix'];
        $id = $row['id'];
            echo '<tr><td style="width:16em"><span class="badge badge-primary">공식</span> <a class="font-weight-lighter" href="/b/'.$id.'">'.$name.$suffix.'</a></td>
            <td style="color:gray">'.$hashtag.'</td></tr>';
    }
echo '</tbody></table><br>';

echo '<span style="float:right" class="text-muted">이용자들이 만든 채널입니다. <a href="https://fnbase.xyz/b/guide/1/554" class="links">더 알아보기</a></span>';
echo '<h4>사설 게시판</h4>';

echo '<br><table class="table table-sm"><thead class="thead-light"><tr><th>이름</th><th>#태그</th></tr></thead><tbody>';

$sql = "SELECT * FROM `_board` WHERE `stat` not like '1' and `stat` not like '8' ORDER BY `sub` DESC;";
$result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_array($result)){
        $name = $row['name'];
        $hashtag = $row['hashtag'];
        $suffix = ' '.$row['suffix'];
        $id = $row['id'];
            if($row['stat'] == 0){
                $color = 'light';
                $cat = '사립';
            }elseif($row['stat'] == 8){
                $color = 'warning';
                $cat = '비활성';
            }elseif($row['stat'] == 2){
                $color = 'info';
                $cat = '제휴';
            }
echo '<tr><td style="width:16em"><span class="badge badge-'.$color.'">'.$cat.'</span> <a class="font-weight-lighter" href="/b/'.$id.'">'.$name.$suffix.'</a></td>
<td style="color:gray">'.$hashtag.'</td></tr>';
    }

echo '</tbody></table><br>';

include 'down.php';
?>