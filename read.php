<?php
include 'up.php';
        $board = $_GET['b'];
        $id = $_GET['id'];
        $sql = "SELECT * FROM `$fnSiteDBname`.`_article` where id like '{$id}' and `to` LIKE '".$board."'";
        $result = mysqli_query($conn, $sql);
        echo '<table class="table">';
        echo '<a class="links" href="../'.$board.'.fn">'.$boardname.'</a>';
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

        if($aid == $_SESSION['userid']){
        $ednrm = '<input name="v" type="hidden" value="viewer"><input name="id" type="hidden" value="'.$id.'"><input name="b" type="hidden" value="'.$board.'"><button type="submit" class="btn-sm btn-light">수정하기</button><button type="submit" formaction="../delete.php" class="btn-sm btn-light">삭제하기</button></form>';
        }
        elseif($_SESSION['userid'] == 'admin'){
        $ednrm = '<input name="v" type="hidden" value="viewer"><input name="id" type="hidden" value="'.$id.'"><input name="b" type="hidden" value="'.$board.'"><button type="submit" class="btn-sm btn-light">수정하기</button><button type="submit" formaction="../delete.php" class="btn-sm btn-light">삭제하기</button></form>';
        }else{
        $ednrm = '</form>';
        }
        $sql = "SELECT * FROM `$fnSiteDBname`.`_article` where id like '{$id}' and `to` LIKE '".$board."'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)){
        	if($row['stat'] == 0){
        					$g = '<span style="color: black">0</span>';
        	}elseif($row['stat'] < 1){
        		 $g = '<span style="color: red">'.$row['stat'].'</span>';
        	}else{
        		$g = '<span style="color: blue">'.$row['stat'].'</span>';
        	}
        echo "<tr>";
        echo '<td><h2>'.$row['title']."</h1>";
        $rowtitle = $row['title'];
        echo "</tr>";
        echo "<tr>";
        if($row['author_id'] == '_anon'){
                $row['author_id'] = '익명';
        }
        if($row['view'] > 9999){$row['view'] = '10000+';}elseif($row['view'] > 999){$row['view'] = '1000+';}
        echo '<td><form method="post" action="../edit.php"><b>' . $row['name'].'</b><span style="color: gray; font-size: 7pt">('.$row['author_id'].')</span> / <span style="color: gray">'.$row['created']. '</span>'.$ednrm.'<p style="color: gray">댓글 수 <span style="color: green">'.$row['comment'].'</span> &nbsp; 조회수 <span style="color: green">'.$row['view'].'</span> &nbsp; 추천 수 '.$g.'</p></td>';
        echo "</tr>";
        echo "<tr>";
        $cont = $row['description'];
        $pattern = "/@[a-z,A-Z,가-힣,0-9]+/";
        $ismatch = preg_match_all($pattern, $cont, $matches, PREG_SET_ORDER);
        echo '<td><br>'. nl2br($cont);
        $i = 0;
        session_start();
        if($ismatch > 0){
            echo '<div class="alert alert-info" role="alert"><h6>이 글에서 언급된 이용자</h6>';
        while($i < $ismatch){
            $orr = var_export($matches[$i], true);
            $orr = str_replace("array","",$orr); #배열 문자열로 변환
            $orr = str_replace(" ","",$orr);
            $orr = str_replace(">","",$orr);
            $orr = str_replace("(",'',$orr);
            $orr = preg_replace("/0/", "", $orr, 1);
            $orr = str_replace("=","",$orr);
            $orr = str_replace(",","",$orr);
            $orr = str_replace(")","",$orr);
            $orr = str_replace("@","",$orr);
            $orr = str_replace("\n","",$orr);
            $trs = str_replace("'","",$orr);
            if($_SESSION['userck'] == $trs){
                $asdf =  'btn btn-info';
            }else{
                $asdf = 'btn btn-outline-info';
            }
            echo "<button type='button' class='$asdf' onclick='asdf$i()'>@$trs</button>";
            echo "<script>function asdf$i(){location.replace('user.php?a=$trs')}</script>&nbsp;";
            $i += 1;
        }
        echo '</div>';
    }
        echo "<br></td>";
        echo "</tr><tr><td align=center>";
        echo '<form method="post" action="./push.php">';
        echo '<input type="hidden" value="'.$row['id'].'" name="id">';
        echo '<input type="hidden" value="'.$board.'" name="b">';
        echo '<input class="btn btn-success" type="submit" value="추천"> &nbsp; ';
        echo '<input type="submit" class="btn btn-danger" value="비추천" formaction="./push.php?mode=un">';
        echo '</form></td></tr>';
        $count = $row['comment'];
        $n++;
        }

        echo '</table>';
        echo '<table width="100%">';
        if(!empty($_SESSION['userid'])){echo '<tr><td><form method="post" action="./comment.php">
        <textarea id="pppp" onload="document.getElementById('."'pppp'".').innerHTML = '."' '".'" style="width: 100%" name="description" placeholder="댓글 작성" required"></textarea>
        <button type="submit" style="width: 100%" type="button" class="btn-lg btn-primary">작성</button>
        <input type="hidden" name="id" value="'.$_SESSION['userid'].'"><input type="hidden" name="islogged" value="true">
        <input name="origin" type="hidden" value="'.$id.'">
        <input name="b" type="hidden" value="'.$board.'">
        <input type="hidden" name="ip" value="'.$uip.'">
        </form></td></tr>';
}
        $sql = "SELECT * FROM `_comment` WHERE board = '".$board."' AND original = '{$id}'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)){
            echo '<tr><td id="aa"><hr size="1"><h4>'.$row['name'].'</h4></tr></td>';
            echo '<tr><td id="aa"><p>'.$row['content'].'</p></tr></td>';
            echo '<tr><td id="aa"><span style="color: gray">'.$row['created'].'</span></tr></td>';
        }
        echo '</table><p><br><br></p>';
        mysqli_close($conn, $sql);
echo '</div>';
$db = $conn;
if(isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }
    $sql = 'select count(*) as cnt from `_article` where `from` like "'.$board.'" order by id desc';
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
                    <button type="submit" formaction="blame.php" style="float: left" class="btn-sm btn-warning">이 게시글 신고</button>
                    <h4 class="display-6"><?php echo $boardname?>
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