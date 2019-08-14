<?php
require 'up.php';
$sql = "SELECT * FROM `_board` where `id` like 'rct'";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)){
    $board = $row['id'];
    $boardname = $row['name'];
    $owner = $row['owner'];
    $boardstat = $row['stat'];
    $boardnum = $row['num'];
    $notice = $row['notice'];
    if(!empty($row['text'])){
        $boardtext = '<span style="color: gray; font-size: 0.5em; text-decoration: none">'.$row['text'].'</span><br>';
    }else{
        $boardtext = '';
    }
    if($boardstat == 1){
        $boardstat = '<span class="badge badge-primary">공식 '.$fnSiteBoardName.'</span>';
    }elseif($boardstat == 0){
        $boardstat = '<span class="badge badge-light">사설 '.$fnSiteBoardName.'</span>';
    }elseif($boardstat == 8){
        $boardstat = '<span class="badge badge-warning">특수</span>';
        $nowrite = true;
    }
}
$db = $conn;

if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$sql = 'select count(*) as cnt from `_article` order by id desc';
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
    $paging .= '<td><a href="/r/1">|←</a></td>';
}
if($currentSection != 1) { 
    $paging .= '<td><a href="/r/' . $prevPage . '">←</a></td>';
}
for($i = $firstPage; $i <= $lastPage; $i++) {
    if($i == $page) {
        $paging .= '<td>' . $i . '</td>';
    } else {
        $paging .= '<td><a href="/r/' . $i . '">' . $i . '</a></td>';
    }
}
if($currentSection != $allSection) { 
    $paging .= '<td><a href="/r/' . $nextPage . '">→</a></td>';
}
if($page != $allPage) { 
    $paging .= '<td><a href="/r/' . $allPage . '">→|</a></td>';
}
$paging .= '</tr>';
$currentLimit = ($onePage * $page) - $onePage;
$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage;

?>
<article>
    <div class="container">
    <div style="padding-left:3px;padding-right:3px">
            <hr>
                <form method="post" action="/write.php">
                <h4><?php echo $boardname.' 글 목록'; echo '<a href="/subscribe.php">
                <button type="button" class="btn-sm btn-warning" style="float: right">구독 글 보기</button></a>';
                                $sql1 = "SELECT * FROM `_account` WHERE `id` LIKE '$owner'";
                                $result1 = mysqli_query($conn, $sql1);
                                while($row1 = mysqli_fetch_array($result1)){
                                    $owner_n = $row1['name'];
                                }?>
                <span style="color: gray; font-size: 0.5em; text-decoration: none">| 관리인 : <a href="/user.php?a=<?=$owner?>">@<?php echo $owner_n;?></a></span><br>
                <?php echo '<span class="h6">'.$boardstat.'</span>&nbsp;'; echo $boardtext;?></h4>
                <input type="hidden" name="from" value="<?php echo $board ?>">
                </form>
        </div>
        </div>
        <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">제목</th>
                    <th scope="col" style="min-width: 5em; max-width: 9em">작성자</th>
                    <th scope="col" style="min-width: 4em">추천</th>
                </tr>
            </thead>
            <tbody>
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

        $sql_ = "SELECT * from `_pinned` WHERE `board_id` LIKE '$board' and `position` LIKE 'top' ORDER BY `num` desc LIMIT 20"; #상단 공지
        $result_ = $db->query($sql_);
        while($row_ = $result_->fetch_assoc())
        {   
            
            $a_id = $row_['article_id'];
            $sql = "select * from `_article` WHERE `id` LIKE $a_id";
            $result = $db->query($sql);
            while($row = $result->fetch_assoc())
            {
            $from = $row['to'];
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

        if($readpage == ''){
            $readpage = 1;
        }
        if($row['view'] > 999){$row['view'] = '1000+';}
        $title = '<strong>'.$row['title'].'</strong>';
        if($row['issec'] == 2){
            $islock = '<span class="badge badge-secondary">기밀</span> ';
        }elseif($row['issec'] == 1){
            $islock = '<span class="badge badge-dark">비밀</span> ';
        }else{
            $islock = '';
        }
        echo '<tr><td><a class="links" href="/b/'.$from.'/'.$readpage.'/'.$id.'"> <span class="badge badge-light">공지</span> ';
        echo $islock.$title.'<span style="color:gray">'.$dot.'</span></a><br>'; 
        echo '<span style="color: gray; font-size: 8pt">'.$create.'</span><span style="color: gray; font-size: 7pt"> 조회수 </span>';
        echo '<span style="color: green; font-size: 7pt">'.$row['view'].'</span>';
        echo '</td><td><a href="/user.php?a='.$row['author_id'].'">'.$row['name'].'</a></td><td></td></tr>'; }}

                        $sql = "SELECT * from `_article` where `to` not in('trash') order by id desc ".$sqlLimit;
                        $result = $db->query($sql);
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
                        $origin = $row['from'];
                        $sqi = 'SELECT * from `_board` where `id` like "'.$origin.'"';
                        $resuit = mysqli_query($conn, $sqi);
                        while($raw = mysqli_fetch_array($resuit)){
                            $originboard = $raw['name'];
                            $originstat = $raw['stat'];
                        }
                        if($originstat == '1'){
                            $badgecolor = 'primary';
                        }elseif($originstat == '0'){
                            $badgecolor = 'light';
                        }elseif($originstat == '8'){
                            $badgecolor = 'warning';
                        }elseif($originstat == '9'){
                            $badgecolor = 'danger';
                        }elseif($originstat == '2'){
                            $badgecolor = 'info';
                        }elseif($originstat == '3'){
                            $badgecolor = 'secondary';
                        }
                        if($readpage == ''){
                            $readpage = 1;
                        }
                        if($row['issec'] == 2){
                            $islock = '<span class="badge badge-secondary">기밀</span> ';
                        }elseif($row['issec'] == 1){
                            $islock = '<span class="badge badge-dark">비밀</span> ';
                        }else{
                            $islock = '';
                        }
                        if($row['view'] > 999){$row['view'] = '1000+';}
                        $con = $row['title'];
                        echo '<a class="links" href="/b/'.$origin.'/1/'.$id.'">'; echo $islock.$con.'<span 
                        style="color:gray">'.$dot.'</span>'; echo ' &nbsp; <span class="badge badge-secondary">'.$row['comment'].'</span>'; ?></a><br>
                        <span style="color: gray; font-size: 8pt"><?php echo $create; ?> /</span><span style="color: gray; font-size: 7pt"> 조회수 </span><span style="color: green; font-size: 7pt"><?php echo $row['view'];?></span>
                        <?php echo '<a href="/b/'.$origin.'" class="badge badge-'.$badgecolor.'">'.$originboard.'</a>';?>
                    </td>
                    <td><?php echo '<a href="user.php?a='.$row['author_id'].'">'.$row['name'].'</a>'; ?></td>
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
</article>
<hr>
<?php 
            $sql_ = "SELECT * from `_pinned` WHERE `board_id` LIKE '$board' and `position` LIKE 'bottom' ORDER BY `num` desc LIMIT 20"; #상단 공지
            $result_ = $db->query($sql_);
            echo '<div class="container"><table class="table table-striped"><tbody>';
            while($row_ = $result_->fetch_assoc())
            {   
                
                $a_id = $row_['article_id'];
                $sql = "select * from `_article` WHERE `id` LIKE $a_id";
                $result = $db->query($sql);
                while($row = $result->fetch_assoc())
                {
                $from = $row['to'];
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

            if($readpage == ''){
                $readpage = 1;
            }
            if($row['view'] > 999){$row['view'] = '1000+';}
            $title = '<strong>'.$row['title'].'</strong>';
            if($row['issec'] == 2){
                $islock = '<span class="badge badge-secondary">기밀</span> ';
            }elseif($row['issec'] == 1){
                $islock = '<span class="badge badge-dark">비밀</span> ';
            }else{
                $islock = '';
            }
            echo '<tr><td><a class="links" href="/b/'.$from.'/'.$readpage.'/'.$id.'"> <span class="badge badge-light">공지</span> ';
            echo $islock.$title.'<span style="color:gray">'.$dot.'</span></a><br>'; 
            echo '<span style="color: gray; font-size: 8pt">'.$create.'</span><span style="color: gray; font-size: 7pt"> 조회수 </span>';
            echo '<span style="color: green; font-size: 7pt">'.$row['view'].'</span>';
            echo '</td><td><a href="/user.php?a='.$row['author_id'].'">'.$row['name'].'</a></td><td></td></tr>'; }}
            echo '</tbody></table></div>';

$is_board = TRUE;
include 'down.php';
?>