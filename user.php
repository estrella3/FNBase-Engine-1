<?php
require 'up.php';
$user = $_GET['a'];
$user = FnFilter($user);

$sql = "SELECT * from `_account` WHERE `id` like '".$user."'";
$result = mysqli_query($conn, $sql);
if(1 > mysqli_num_rows($result)){
    echo '<script>alert("존재하지 않는 사용자입니다."); history.go(-1)</script>';
    exit;
}
while($row = mysqli_fetch_array($result)){
    $name = $row['name'];
}

echo '<h3>'.$name.'<small class="text-muted">('.$user.')</small>님의 활동 내역</h3>';
$user = $name;
echo '<h5>최근 게시글</h5>';
        echo '<table class="table table-striped"><thead>
            <tr>
            <th scope="col">글 제목</th>
            <th scope="col">작성 시간</th>
            </tr></thead><tbody>';
    $sql = "SELECT * from `_article` WHERE `name` like '$user' order by id desc";
    $result = mysqli_query($conn, $sql);
    $i = 1;
    while ($row = mysqli_fetch_array($result)){
        $con = $row['title'];
        $conlen = mb_strlen("$con", 'UTF-8');
        if($conlen >= 15){
            $dot = '...';
        }else{
            $dot = '';
        }
        $con = mb_substr($con, 0, 15, 'UTF-8');
        echo '<tr><td><a href="'.$row['from'].'-'.$row['id'].'.base">'.$con.$dot.'</a></td>';
        echo '<td>'.$row['created'].'</td>';
        $i++;
        if($i > 10){
        break;
        }
    }
    if(1 > mysqli_num_rows($result)){
         echo '<td>작성한 글이 없습니다.</td><td></td>';
    }
    echo '</tbody></table>';


echo '<h5>댓글</h5>';
        echo '<table class="table table-striped"><thead>
        <tr>
        <th scope="col">댓글 내용</th>
        </tr></thead><tbody>';
        $sql = "SELECT * from `_account` WHERE `name` like '$user'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)){
            $user_id = $row['id'];
            $user_birth = $row['at'];
            $user_point = $row['point'];
            $user_email = $row['email'];
            $user_intro = $row['introduce'];
        }
        $sql = "SELECT count(*) as cnt from `_article` WHERE `author_id` like '$user_id'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)){
            $user_write = $row['cnt'];
        }
        $sql = "SELECT count(*) as cnt from `_board` WHERE `owner` like '$user'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)){
            $user_own = $row['cnt'];
        }
    $sql = "SELECT * from `_comment` WHERE `id` like '$user_id' and `secret` like '0' order by num desc";
    $result = mysqli_query($conn, $sql);
    $i = 1;
    while ($row = mysqli_fetch_array($result)){
        $con = $row['content'];
        $conlen = mb_strlen("$con", 'UTF-8');
        if($conlen >= 15){
            $dot = '...';
        }else{
            $dot = '';
        }
        $con = mb_substr($con, 0, 15, 'UTF-8');
        echo '<tr><td><a href="'.$row['board'].'-'.$row['original'].'.base">'.$con.$dot.'</a>';
        echo '<br><span style="color: gray; font-size: 0.8em"> 작성 시간 : '.$row['created'].'</span></td></tr>';
        $i++;
            if($i > 10){
            break;
            }
    }
    if(1 > mysqli_num_rows($result)){
    echo '<td>작성한 댓글이 없습니다.</td><td></td>';
    }
    echo '</tbody></table>';


echo '<h5>기타 정보</h5>';
$m = 'title';
echo '<table class="table table-striped"><thead>
    <tr>
      <th scope="col">항목</th>
      <th scope="col">내용</th>
    </tr></thead><tbody>';
echo '<tr>';
echo '<td>아이디</td><td>'.$user_id.'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>가입일</td><td>'.$user_birth.'</td>';
echo '</tr>';
echo '<tr>';
if($user_intro == ''){
	$user_intro = '<span style="color:gray">없음</span>';
}
echo '<td>본인 소개</td><td>'.$user_intro.'</td>';
echo '</tr>';
echo '<tr>';
echo '<td>포인트</td><td>'.$user_point.' <span style="color:gray;font-size:0.7em">p</span></td>';
echo '</tr>';
echo '<tr>';
echo '<td>작성한 글 개수</td><td>'.$user_write.'개</td>';
echo '</tr>';
echo '<tr>';
echo '<td>소유한 게시판 개수</td><td>'.$user_own.'개</td>';
echo '</tr>';
if($user_id == $_SESSION['userid']){
	echo '<tr>';
echo '<td>가입한 이메일 주소</td><td>'.$user_email.'</td>';
echo '</tr>';
}
echo '</tbody></table>';


include 'down.php';
?>