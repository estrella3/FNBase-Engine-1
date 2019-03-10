<?php
include 'up.php';

if(empty($_GET['b'])){
$board = 'board';
}else{
$board = $_GET['b'];
}
$sql = "SELECT * FROM `_board` WHERE `id` LIKE '$board'";
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
        $boardstat = '<span class="badge badge-warning">비활성</span>';
    }elseif($boardstat == 9){
        $boardstat = '<span class="badge badge-danger">차단</span>';
    }
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
$oneSection = 10;
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
$paging = '<tr class="table-dark">';
if($page != 1) {
    $paging .= '<td class="page"><a href="./index.php?page=1">|←</a></td>';
}
if($currentSection != 1) { 
    $paging .= '<td class="page"><a href="./index.php?page=' . $prevPage . '">←</a></td>';
}
for($i = $firstPage; $i <= $lastPage; $i++) {
    if($i == $page) {
        $paging .= '<td class="page">' . $i . '</td>';
    } else {
        $paging .= '<td class="page"><a href="./index.php?page=' . $i . '">' . $i . '</a></td>';
    }
}
if($currentSection != $allSection) { 
    $paging .= '<td class="page"><a href="./index.php?page=' . $nextPage . '">→</a></td>';
}
if($page != $allPage) { 
    $paging .= '<td class="page"><a href="./index.php?page=' . $allPage . '">→|</a></td>';
}
$paging .= '</tr>';
$currentLimit = ($onePage * $page) - $onePage;
$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage;
$sql = 'select * from `_article` WHERE `to` LIKE "'.$board.'" order by id desc' . $sqlLimit;
$result = $db->query($sql);
?>
<article>
    <div class="container">
            <hr>
                <form method="post" action="write.php">
                <h4><?php echo $boardname?><button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button>
                <span style="color: gray; font-size: 0.5em; text-decoration: none">| 소유주 : <a href="user.php?a=<?php echo $owner;?>">@<?php echo $owner;?></a></span><br>
                <?php echo '<span class="h6">'.$boardstat.'</span>&nbsp;'; echo $boardtext;?></h4>
                <input type="hidden" name="from" value="<?php echo $board ?>">
                </form>
            <hr>
        </div>
        <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">제목</th>
                    <th scope="col" style="min-width: 5em; max-width: 9em">작성자</th>
                    <th scope="col" style="min-width: 4em">추천</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                        <a class="links" href="?" data-toggle="modal" data-target="#example" aria-controls="example"><?php echo $boardname; ?> 이용 안내</a><br>
                        <div class="modal fade" id="example" tabindex="-1" role="dialog" aria-labelledby="example" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $boardname?>의 사용 규칙</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <?php echo nl2br($notice) ?>
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
                <td><?php echo $owner;?></td>
                <td>&nbsp;</td>
                </tr>
                    <?php

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
                        if($row['view'] > 999){$row['view'] = '1000+';}
                        echo '<a class="links" href="./'; echo $board.'-'.$id.'.base">'; echo $row['title']; echo ' &nbsp; <span class="badge badge-secondary">'.$row['comment'].'</span>'; ?></a><br>
                        <span style="color: gray; font-size: 8pt"><?php echo $create; ?> /</span><span style="color: gray; font-size: 7pt"> 조회수 </span><span style="color: green; font-size: 7pt"><?php echo $row['view'];?></span>
                    </td>
                    <td><?php echo $row['name']; ?></td>
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
            </tbody>
        </table>
        <table class="table" style="text-align: center">
        <?php echo $paging ?>
                        </table>
        </div>
    </div>
</article>
<?php mysqli_close($conn);
$is_board = TRUE;
include 'down.php';
?>