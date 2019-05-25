<?php
include 'up.php';
$no = $_GET['no'];
if($no !== ''){
$sql = "UPDATE `_ment` SET `read` = '1' WHERE `_ment`.`no` = $no;";
$result = mysqli_query($conn, $sql);
}
        $board = $_GET['b'];
        $id = $_GET['id'];
        echo $_GET['a'];
        $sql = "SELECT * FROM `$fnSiteDBname`.`_article` where id like '{$id}' and `to` LIKE '".$board."'";
        $result = mysqli_query($conn, $sql);
        $n = 1;
        while($raw = mysqli_fetch_array($result)){
        $aid = $raw['author_id'];
        $views = $raw['view'];
        }
        $views = $views + 1;

        $sql = "UPDATE `_article` set view = {$views} where id like '{$id}' and `to` LIKE '".$board."'";
        $result = mysqli_query($conn, $sql);
        if($result === false){
          echo '조회수 집계 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
        }
        $ednrm = '<form method="post"><input name="id" type="hidden" value="'.$id.'">
        <input name="b" type="hidden" value="'.$board.'"><button type="submit" class="dropdown-item" formaction="/push.php">추천</button>
        <button type="submit" class="dropdown-item" formaction="/push.php?mode=un">비추천</button>';
        if($aid == $_SESSION['userid']){
        $ednrm .= '<input name="v" type="hidden" value="viewer">
        <button type="submit" formaction="/edit.php" class="dropdown-item">수정</button>
        <button type="submit" formaction="/delete.php" class="dropdown-item">삭제</button></form>';
        }else{
        $ednrm .= '</form>';
        }
        $sql = "SELECT * FROM `_board` WHERE `id` LIKE '$board'";
        $result = mysqli_query($conn, $sql);
        
        while($row = mysqli_fetch_array($result)){
        
            $board1 = $row['id'];
            $boardname = $row['name'].' '.$row['suffix'];
            $boardsuffix = $row['suffix'];
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
                $boardstat = '<span class="badge badge-primary">공식 '.$boardsuffix.'</span>';
            }elseif($boardstat == 0){
                $boardstat = '<span class="badge badge-light">사설 '.$boardsuffix.'</span>';
            }elseif($boardstat == 8){
                $boardstat = '<span class="badge badge-warning">비활성</span>';
                $nowrite = true;
            }elseif($boardstat == 9){
                $boardstat = '<span class="badge badge-danger">차단됨</span>';
                $nowrite = true;
            }
        }
            if(1 > mysqli_num_rows($result)){
            echo '<script>alert("없는 게시판입니다.")</script>';
            include_once 'down.php';
            exit;
        }
        ?><div style="padding-left:3px;padding-right:3px">
        <hr>
            <form method="post" action="/write.php">
            <h4><?php echo '<a style="color:black" href="/b/'.$board1.'">'.$boardname.'</a>'; if(!$nowrite === true){echo'<button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button>';}?>
            <span style="color: gray; font-size: 0.5em; text-decoration: none">| 주인 : <a href="/user.php?a=<?php echo $owner;?>">@<?php echo $owner;?></a></span><br>
            <?php echo '<span class="h6">'.$boardstat.'</span>&nbsp;'; echo $boardtext;?></h4>
            <input type="hidden" name="from" value="<?php echo $board1 ?>">
            </form>
        <hr>
    </div><?php
        $sql = "SELECT * FROM `_article` where id like '{$id}' and `to` LIKE '".$board."'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)){
        	if($row['stat'] == 0){
        					$g = '<span style="color: black">0</span>';
        	}elseif($row['stat'] < 1){
        		 $g = '<span style="color: red">'.$row['stat'].'</span>';
        	}else{
        		$g = '<span style="color: blue">'.$row['stat'].'</span>';
            }
            if($row['edited'] == 1){
                $commentedited = '<sup><b><mark>*수정됨</mark></b></sup> ';
            }else{
                $commentedited = '';
            }
        echo '<div class="card"><div class="card-header" style="background-color: #ddeaff"><h5 style="float:left">'
        .$row['title'].'</h5><div class="btn-group" role="group" style="float:right;">
         <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle"
          data-toggle="dropdown" aria-haspopupage="true" aria-expanded="false">이 글을</button>
           <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> '.$ednrm.'</div> </div></div><div class="card-body">';
        $rowtitle = $row['title'];
        $rowarname = $row['name'];
        if($row['view'] > 9999){$row['view'] = '10000+';}elseif($row['view'] > 999){$row['view'] = '1000+';}
        echo '<form method="post" action="/edit.php"><b><a href="/user.php?a='
        .$row['name'].'">'.$row['name'].'</a></b><span style="color: gray; font-size: 7pt">('
        .$row['author_id'].')</span> / <span style="color: gray">'
        .$row['created']. '</span> <a class="badge badge-secondary" data-toggle="collapse" 
        href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">상세정보</a>
        <div class="collapse" id="collapseExample">
        <br>'.$commentedited.'<span style="color: gray">댓글 수 <span style="color: green">'
        .$row['comment'].'</span> &nbsp; 조회수 <span style="color: green">'
        .$row['view'].'</span> &nbsp; 추천 수 '.$g.'</span>';
        $sqi = "SELECT * FROM `_account` WHERE id = '".$row['author_id']."'";
        $resulp = mysqli_query($conn, $sqi);
        while ($raw = mysqli_fetch_array($resulp)){
            $user_email = $raw['email'];
            $user_exp = $raw['introduce'];
        }
        $hash = md5( strtolower( trim( "$user_email" ) ) );
        echo '<img src="https://secure.gravatar.com/avatar/'.$hash.'?s=64&d=identicon" style="float:left" class="mr-3" alt="Gravatar">';
        echo '<br>'.$user_exp;
        echo "</div></div></form>";
        $cont = $row['description'];
        echo '<div class="card-body border-white">'.nl2br($cont).'</div>';
        $count = $row['comment'];
        $n++;
        }
        echo "</div>";
        echo '<table width="100%">';
        $sql = "SELECT * FROM `_comment` WHERE board = '".$board."' AND original = '{$id}'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)){
            $sqi = "SELECT * FROM `_account` WHERE id = '".$row['id']."'";
            $resulp = mysqli_query($conn, $sqi);
            while ($raw = mysqli_fetch_array($resulp)){
                $user_email = $raw['email'];
            }
            $hash = md5( strtolower( trim( "$user_email" ) ) );
            if($row['stat'] > 6){
                $commentheadline = 'style="background-color:lightgreen"';
                $commentheadtext = '<span class="badge badge-primary">추천 : '.$row['stat'].'명</span> ';
            }elseif($row['blame'] > 6){
                $commentheadline = 'style="background-color:#ffcccb"';
                $commentheadtext = '<span class="badge badge-warning">반대 : '.$row['blame'].'명</span> ';
            }else{
                $commentheadline = '';
                $commentheadtext = '';
            }
            if($row['edited'] == 1){
                $commentedited = '<sup><b><mark>*수정됨</mark></b></sup> ';
            }else{
                $commentedited = '';
            }
            echo '<tr style="width:90%"><td><hr><div class="media">
            <img src="https://secure.gravatar.com/avatar/'.$hash.'?s=64&d=identicon" class="mr-3 rounded" alt="Gravatar">
            <div class="media-body" '.$commentheadline.'>
            ';
            $rowname = $row['name'];
            echo '<h5 class="mt-0"><a href="/user.php?a='.$row['name'].'">'.$row['name'].'</a><span style="color: gray;font-size:0.5em">('.$row['id'].')</h5>';
            echo '<p>'.$commentheadtext.$commentedited.$row['content'].'</p>';
            echo '<span style="color: gray">'.$row['created'].'</span>';
            if($_SESSION['userid'] == $row['id']){
                echo ' <a class="badge badge-secondary text-white" href="/comment_mod.php?a=edit&n='.$row['num'].'">수정</a>
                <a class="badge badge-danger text-white" href="/comment_mod.php?a=delete&n='.$row['num'].'">삭제</a>';
            }
            $num = $row['num'];
            if(!empty($_SESSION['userck'])){
                    if($row['id'] !== $_SESSION['userid']){
                        echo ' <a class="badge badge-success" href="/comment_mod.php?a=push&n='.$row['num'].'">추천</a>
                        <a class="badge badge-warning" href="/comment_mod.php?a=blame&n='.$row['num'].'">반대</a>';
                    }
            echo ' <button class="badge badge-light" data-toggle="collapse" href="#reply'.$num.'" role="button" aria-expanded="false" aria-controls="#reply'.$num.'">답변</button>';
            }
            echo '<div class="collapse" id="reply'.$num.'">
            <form action="/reply.php" id="wrtrpl'.$num.'" method="post">
            <textarea name="d" id="rpltxt'.$num.'" class="border text-dark" style="width:100%"></textarea>
            <button type="submit" style="width: 100%" type="button" class="btn btn-success">답변 작성</button>
            <input type="hidden" name="o" value="'.$num.'">
            <input type="hidden" name="m" value="'.$id.'">
            <input type="hidden" name="b" value="'.$board.'">
            <input type="hidden" name="title" value="'.$rowtitle.'">
            <input type="hidden" name="to" value="'.$rowname.'">
            </form></div>
            <script>
            $(function ()
            {
                $(document).on("keydown", "#rpltxt'.$num.'", function(e)
                {
                    if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey)
                    {
                      $("#wrtrpl'.$num.'").submit();
                    }
                });
            });
            </script>
            ';
        $sqi = "SELECT * FROM `_reply` WHERE original = '$num' AND step = 1";
        $resuld = mysqli_query($conn, $sqi);
        while ($raw = mysqli_fetch_array($resuld)){
                $user_email = $raw['email'];
                $hash = md5( strtolower( trim( "$user_email" ) ) );
        echo '<br><br><div class="media">
        <img src="https://secure.gravatar.com/avatar/'.$hash.'?s=64&d=identicon" class="mr-3 rounded" alt="Gravatar">
        <div class="media-body">
          <h5 class="mt-0"><a href="/user.php?a='.$raw['name'].'">'.$raw['name'].'</a><span style="color: gray;font-size:0.5em">('.$raw['id'].')</h5>
            '.$raw['content'].'
        </div>
        </div>';
        }
        }echo '</div></div></td></tr>';        echo '<tr><td><hr></td></tr><p><br><br></p>';
        if(!empty($_SESSION['userid'])){
        echo '<tr><td><form id="rwtcmt" method="post" action="/comment.php">
        <textarea id="cmttxt" class="border text-dark" style="width: 100%" name="description" placeholder="댓글 내용" required"></textarea>
        <button type="submit" style="width: 100%" type="button" class="btn-lg btn-primary">댓글 작성</button>
        <input type="hidden" name="id" value="'.$_SESSION['userid'].'"><input type="hidden" name="islogged" value="true">
        <input name="origin" type="hidden" value="'.$id.'">
        <input name="b" type="hidden" value="'.$board.'">
        <input type="hidden" name="ip" value="'.$uip.'">
        <input type="hidden" name="title" value="'.$rowtitle.'">
        <input type="hidden" name="user" value="'.$rowarname.'">
        </form></td></tr>
        <script>
$(function ()
{
    $(document).on("keydown", "#cmttxt", function(e)
    {
        if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey)
        {
          $("#rwtcmt").submit();
        }
    });
});
</script>';
        }
        mysqli_close($conn, $sql);
echo '</table></div></div>';
$db = $conn;

if(isset($_GET['page'])) {
    $page = $_GET['page'];
}else{
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
    <article>
        <div class="container">
                <hr>
                    <form method="post" action="/write.php">
                    <button type="submit" formaction="/blame.php" style="float: left" class="btn-sm btn-warning">이 게시글 신고</button>
                    <input type="hidden" name="from" value="<?php echo $board ?>">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="title" value="<?php echo $rowtitle ?>">
                    <button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button>
                    </form><br>
                    </h4>
                <hr>
            </div>
            <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">제목</th>
                        <th scope="col">작성자</th>
                        <th scope="col">추천</th>
                    </tr>
                </thead>
                <tbody>
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
                    <tr><td>
                    <?php
                        if($readpage == ''){
                            $readpage = 1;
                        }
                        if($row['view'] > 999){$row['view'] = '1000+';}
                        echo '<a class="links" href="/b/'.$board.'/'.$readpage.'/'.$id.'">'; echo $row['title']; echo ' &nbsp; <span class="badge badge-secondary">'.$row['comment'].'</span>'; ?></a><br>
                        <span style="color: gray; font-size: 8pt"><?php echo $create; ?> /</span><span style="color: gray; font-size: 7pt"> 조회수 </span><span style="color: green; font-size: 7pt"><?php echo $row['view'];?></span>
                    </td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php 
                    if($row['stat'] == 0){
                        $c = 'light';
                    }elseif($row['stat'] <= -10){
                        $c = 'danger';
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
<?php
$is_board = TRUE;
include 'down.php';
?>