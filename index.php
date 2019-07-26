<?php
$ifindex = true;
$readpage = $_GET['page'];
include_once 'setting.php';
if($fnSite_Homepage == 'recent'){
    if(empty($_GET['b'])){
    include 'recent.php';
    exit;
    }
}
require 'up.php';
$board = $_GET['b'];
$sql = "SELECT * FROM `_board` WHERE `id` LIKE '$board'";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_array($result)){

    $board = $row['id'];
    $boardname = $row['name'].' '.$row['suffix'];
    $boardsuffix = $row['suffix'];
    $owner = $row['owner'];
    $keeper = $row['keeper'];
    $volun = $row['volunteer'];
    $boardstat = $row['stat'];
    $boardnum = $row['num'];
    $notice = $row['notice'];

    if(!empty($row['text'])){
        $boardtext = '<span style="color: gray; font-size: 0.5em; text-decoration: none">'.$row['text'].'</span><br>';
    }else{
        $boardtext = '';
    }
    if($boardstat == 1){
        $boardcat = '<span class="badge badge-primary">공식 '.$boardsuffix.'</span>';
    }elseif($boardstat == 0){
        $boardcat = '<span class="badge badge-light">사설 '.$boardsuffix.'</span>';
    }elseif($boardstat == 8){
        $boardcat = '<span class="badge badge-warning">비활성</span>';
        $nowrite = true;
    }elseif($boardstat == 9){
        $boardcat = '<span class="badge badge-danger">차단됨</span>';
        $nowrite = true;
    }elseif($boardstat == 2){
        $boardcat = '<span class="badge badge-info">제휴</span>';
    }elseif($boardstat == 3){
        $boardcat = '<span class="badge badge-secondary">도움말</span>';
        $nowrite = true;
    }
}
    if(1 > mysqli_num_rows($result)){
    echo '<script>alert("없는 게시판입니다.")</script>';
    include_once 'down.php';
    exit;
}

$kpr = strpos($keeper, $_SESSION['userid']);
        if($kpr === true){
            $sql = "SELECT * FROM `_userRights` WHERE `type` like '3'";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
                if($row['editBoardInfo'] == 1){
                    $editBoard = true;
                }
                if($row['kickAnother'] == 1){
                    $canKick = true;
                }
                if($row['deleteAnother'] == 1){
                    $canKick = true;
                }
                if($row['makeBoardNotice'] == 1){
                    $makeNotice = true;
                }
                $isOwner = false;
            }
        }
        $vlt = strpos($volun, $_SESSION['userid']);
        if($vlt === true){
            $sql = "SELECT * FROM `_userRights` WHERE `type` like '2'";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result)){
                if($row['editBoardInfo'] == 1){
                    $editBoard = true;
                }
                if($row['kickAnother'] == 1){
                    $canKick = true;
                }
                if($row['deleteAnother'] == 1){
                    $canKick = true;
                }
                if($row['makeBoardNotice'] == 1){
                    $makeNotice = true;
                }
                $isOwner = false;
            }
        }
        if($owner === $_SESSION['userid']){
            $makeNotice = true;
            $canKick = true;
            $canDelete = true;
            $editBoard = true;
            $isOwner = true;
        }

$db = $conn;

if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$sql = 'select count(*) as cnt from `_article` WHERE `to` LIKE "'.$board.'" order by id desc';
$result = $db->query($sql);
$row = $result->fetch_assoc();
$allPost = $row['cnt'];
$onePage = 20;
$allPage = ceil($allPost / $onePage);
if($page < 1 || ($allPage && $page > $allPage)) {
?>
    <script>
        alert("존재하지 않는 페이지입니다.");
        history.back();
    </script>
<?php
}
$oneSection = 5;
$currentSection = ceil($page / $oneSection);
$allSection = ceil($allPage / $oneSection);
$firstPage = ($currentSection * $oneSection) - ($oneSection - 1);
if($currentSection == $allSection) {
    $lastPage = $allPage;
} else {
    $lastPage = $currentSection * $oneSection;
}
$prevPage = (($currentSection - 1) * $oneSection);
$nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1);
$paging = '<tr>';
if($page != 1) {
    $paging .= '<td><a href="/b/'.$board.'/1">|←</a></td>';
}
if($currentSection != 1) { 
    $paging .= '<td><a href="/b/'.$board.'/' . $prevPage . '">←</a></td>';
}
for($i = $firstPage; $i <= $lastPage; $i++) {
    if($i == $page) {
        $paging .= '<td>' . $i . '</td>';
    } else {
        $paging .= '<td><a href="/b/'.$board. '/' . $i . '">' . $i . '</a></td>';
    }
}
if($currentSection != $allSection) { 
    $paging .= '<td><a href="/b/'.$board.'/' . $nextPage . '">→</a></td>';
}
if($page != $allPage) { 
    $paging .= '<td><a href="/b/'.$board.'/' . $allPage . '">→|</a></td>';
}
$paging .= '</tr>';
$currentLimit = ($onePage * $page) - $onePage;
$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage;
$sql = 'select * from `_article` WHERE `to` LIKE "'.$board.'" order by id desc' . $sqlLimit;
$result = $db->query($sql);
?>
<section class="float: left">
<article>
<div class="container">
    <div style="padding-left:3px;padding-right:3px">
            <hr>
                <form method="post" action="/write.php">
                <h4><?php echo '<a style="color:black" href="/b/'.$board.'">'.$boardname.'</a>';
                if(!$nowrite === true){
                    echo '<button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button>
                    <span style="float:right">&nbsp;</span>';}
                    if($editBoard == true){
                echo '<a href="'.$_SERVER['REQUEST_URI'].'/admin">'.'<button type="button" class="btn-sm btn-danger" style="float: right"
                >채널 설정</button></a>';}
                $sql1 = "SELECT * FROM `_account` WHERE `name` LIKE '$owner'";
                $result1 = mysqli_query($conn, $sql1);
                while($row1 = mysqli_fetch_array($result1)){
                    $owner_id = $row1['id'];
                }
                ?>
                <span style="color: gray; font-size: 0.5em; text-decoration: none">| 소유주 : <a href="/user.php?a=<?php echo $owner_id;
                ?>">@<?php echo $owner;?></a></span><br>
                <?php echo '<span class="h6">'.$boardcat.'</span>&nbsp;'; echo $boardtext;?></h4>
                <input type="hidden" name="from" value="<?php echo $board ?>">
                </form>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">제목</th>
                    <th scope="col" style="min-width: 5em; max-width: 9em">작성자</th>
                    <th scope="col" style="min-width: 4em">추천</th>
                </tr>
            </thead>
            <tbody>
            <tr>
            <?php if(!$nowrite === true){ echo '<tr>
                <td>
                        <a class="links" href="?" data-toggle="modal" data-target="#example" aria-controls="example">'.$boardname.' 이용 안내</a><br>
                        <div class="modal fade" id="example" tabindex="-1" role="dialog" aria-labelledby="example" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">'.$boardname.' 의 사용 규칙</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                '.nl2br($notice).'
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">닫기</button>
                                </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                </td>
                <td>'.$owner.'</td>
                <td>&nbsp;</td>
                </tr>';}
                        while($row = $result->fetch_assoc())
                        {
                            $datetime = explode(' ', $row['created']);
                            $date = $datetime[0];
                            $time = $datetime[1];
                            $id = $row['id'];
                            $time = explode(':', $time);
                            $date = explode('-', $date);
                            $date[1] = $date[1].'월 ';
                            $date[2] = $date[2].'일 '; 
                            $d = $date[1].$date[2];
                            if($time[0] <= 11)
                                    $time0 = "오전 ".$time[0].'시 ';
                            if($time[0] >= 12)
                                    $time0 = "오후 ".$time[0].'시 ';
                            if($date == Date('Y-m-d'))
                                        echo $time[1];
                                        $time1 = $time[1];
                                        $time2 = $time[2];
                                    $s = "$time0 $time1 분 $time2 초";
                            if($date !== Date('Y-m-d'))
                                    $create = $d.$s;
                    ?>
                    <tr>
                    <td>
                        <?php
                        if($readpage == ''){
                            $readpage = 1;
                        }
                        if($row['view'] > 999){$row['view'] = '1000+';}
                        $con = $row['title'];
                        if($row['issec'] == 2){
                            $islock = '<span class="badge badge-secondary">기밀</span> ';
                        }elseif($row['issec'] == 1){
                            $islock = '<span class="badge badge-dark">비밀</span> ';
                        }else{
                            $islock = '';
                        }
                        echo '<a class="links" href="/b/'.$board.'/'.$readpage.'/'.$id.'">'; echo $islock.$con.'<span 
                        style="color:gray">'.$dot.'</span>'; echo ' &nbsp; <span class="badge badge-secondary">'.$row['comment'].'</span>'; ?></a><br>
                        <span style="color: gray; font-size: 8pt"><?php echo $create; ?> /</span><span style="color: gray; font-size: 7pt"> 조회수 </span><span style="color: green; font-size: 7pt"><?php echo $row['view'];?></span>
                    </td>
                    <td><?php echo '<a href="/user.php?a='.$row['author_id'].'">'.$row['name'].'</a>'; ?></td>
                    <td><?php 
                    if($row['stat'] == 0){
                        $c = 'light';
                    }elseif($row['stat'] <= -10){
                        $c = 'danger';
                    }elseif($row['stat'] >= 10){
                        $c = 'primary';
                    }elseif($row['stat'] < 0){
                        $c = 'warning';
                    }elseif($row['stat'] > 0){
                        $c = 'success';
                    }
                    echo '<span class="badge badge-'.$c.'" style="font-size: 12pt">'.$row['stat'].'</span>';
                    }?></td>
                </tr>
                <tr>
                <td></td>
                <td></td>
                <td></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-light" style="text-align: center">
        <?php echo $paging ?>
                        </table>
        </div>
    </div>
</article></div>
</div>
                </div>
<hr>
<?php mysqli_close($conn);
$is_board = TRUE;
include 'down.php';
?>