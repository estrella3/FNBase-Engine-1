<?php
require 'up.php';
        $board = Filt($_GET['b']);
        $id = Filt($_GET['id']);
        include 'id_ban.php';
        echo Filt($_GET['a']);
        $sql = "SELECT * FROM `$fnSiteDBname`.`_article` where id like '{$id}'";
        $result = mysqli_query($conn, $sql);
        $n = 1;
        while($raw = mysqli_fetch_array($result)){
        if($board == 'recommend' or 'trash'){
        	$board = $raw['from'];
        }
        $aid = $raw['author_id'];
        $views = $raw['view'];
        $push = $raw['stat'];
        }
        $views = $views + 1;
        $sql = "UPDATE `_article` set view = {$views} where id like '{$id}' and `from` LIKE '".$board."'";
        $result = mysqli_query($conn, $sql);
        if($result === false){
          echo '조회수 집계 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
        }
        $sql = "SELECT * FROM `_article` WHERE `from` like '$board' AND `id` like '$id'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) !== 1){
            echo '<script>alert("위치가 맞지 않는 글 입니다.")</script>';
            exit;
        }
        $reportmodal = '
            <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reportModalLabel">게시글 신고</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-inline" method="post" action="/blame.php?a=b">
                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref1">신고 사유</label>
                            <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref1" name="repOpt"
                            onchange="document.getElementById(\'repCheck\').style.display = \'\';">
                            <option disabled selected>--선택해주세요.--</option>
                            <option value="1">선정적이거나 과도한 폭력성을 띔</option>
                            <option value="2">광고</option>
                            <option value="3">극단적 성향의 게시글</option>
                            <option value="4">친목 행위 조장</option>
                            <option value="5">범죄 예고 등 반사회적 게시글</option>
                            <option value="6">명예 훼손 등 국내법을 위반하는 글</option>
                            <option value="7">기타 사유</option>
                            </select>
                        
                            <div class="custom-control custom-checkbox my-1 mr-sm-2" id="repCheck" style="display:none">
                            <input type="checkbox" class="custom-control-input" id="customControlInline1">
                            <label class="custom-control-label" for="customControlInline1"
                            onclick="document.getElementById(\'submitModal\').disabled = \'\';">신고에 대한 책임을 감수하겠습니다.</label>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" id="submitModal" disabled>신고</button>
                        <input type="hidden" name="from" value="'.$board.'">
                        <input type="hidden" name="id" value="'.$id.'">
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        ';
        $ednrm = '<form method="post"><input name="id" type="hidden" value="'.$id.'">
        <input name="b" type="hidden" value="'.$board.'"><button type="submit" class="dropdown-item" formaction="/push.php">추천</button>
        <button type="submit" class="dropdown-item" formaction="/push.php?mode=un">비추천</button>
        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#reportModal"">신고</button>';
        if($aid == $_SESSION['userid']){
        $ednrm .= '<input name="v" type="hidden" value="viewer">
        <button type="submit" formaction="/edit.php" class="dropdown-item">수정</button>
        <button type="submit" formaction="/delete.php" class="dropdown-item">삭제</button>';
            if($push > 7){
                $ednrm .= '<button type="submit" formaction="/push.php?mode=go" id="goHead" onmousemove="aaaa()" class="dropdown-item">추천글로!</button>';
                echo '<script>function aaaa(){
                    document.getElementById("goHead").style.cssText = "color:red";
                    setTimeout(function() { 
                        document.getElementById("goHead").style.cssText = "color:orange";
                     }, 100)
                     setTimeout(function() { 
                        document.getElementById("goHead").style.cssText = "color:yellow";
                     }, 200)
                     setTimeout(function() { 
                        document.getElementById("goHead").style.cssText = "color:green";
                     }, 300)
                    setTimeout(function() { 
                        document.getElementById("goHead").style.cssText = "color:blue";
                     }, 400)}</script>';
            }
        $ednrm .= '</form>';
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
                $boardcat = '<span class="badge badge-warning">특수</span>';
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
        $u_id = $_SESSION['userid'];
        if(!empty($u_id)){
        $kpr = preg_match("/^$u_id$/", $keeper, $kpr_res);
        if(!empty($kpr_res)){
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
        $vlt = preg_match("/^$u_id$/", $volun, $vlt_res);
        if(!empty($vlt_res)){
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
        if($makeNotice === TRUE){
            $sql = "SELECT * FROM `_pinned` WHERE `board_id` like '$board' and `article_id` like '$id'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == 1){
                $isPinned = TRUE;
            }else{
                $isPinned = FALSE;
            }
        }
    }
        ?><div style="padding-left:3px;padding-right:3px"><hr>
            <form method="post" action="/write.php">
            <h4><?php echo '<a style="color:black" href="/b/'.$board1.'">'.$boardname.'</a>'; if(!$nowrite === true){echo
                '<button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button><span style="float:right">&nbsp;</span>';}
                if($editBoard == true){
                    echo '<a href="../admin"><button type="button" class="btn-sm btn-danger" style="float: right">채널 설정</button></a>';}
                    $sql1 = "SELECT * FROM `_account` WHERE `id` LIKE '$owner'";
                    $result1 = mysqli_query($conn, $sql1);
                    while($row1 = mysqli_fetch_array($result1)){
                        $owner_n = $row1['name'];
                    }
                    ?>
            <span style="color: gray; font-size: 0.5em; text-decoration: none">| 소유주 : <a href="/user.php?a=<?=$owner?>">@<?php echo $owner_n;?></a></span><br>
            <?php echo '<span class="h6">'.$boardcat.'</span>&nbsp;'; echo $boardtext;?></h4>
            <input type="hidden" name="from" value="<?php echo $board1 ?>">
            </form>
        <hr>
    </div><?php
        $sql = "SELECT * FROM `_article` where id like '{$id}' and `from` LIKE '".$board."'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)){
            $a_id = $row['author_id'];
            $title = $row['title'];
            #열람제한기능
            if($row['issec'] == 1){ #나만보기
                $isp = false;
                if($row['author_id'] !== $_SESSION['userid']){
                    echo '<div style="text-align:center"><h5>비밀글입니다.</h5>';
                        if(!empty($_SESSION['userid'])){
                            echo '열람 허가를 요청하시겠습니까?<br>';
                            echo '<form method="post" action="/knock.php">
                            <input type="hidden" name="from" value="'.$board.'">
                            <input type="hidden" name="id" value="'.$id.'">
                            <input type="hidden" name="a" value="'.$a_id.'">
                            <input type="hidden" name="title" value="'.$title.'">
                            <textarea name="knockReason" class="form-control-sm" placeholder="열람 신청 사유"></textarea>
                            <button type="submit" class="btn btn-primary">신청</button>
                            </form>';
                        }
                    echo '</div>';
                    include_once 'down.php';
                    exit;
                }
            }elseif($row['issec'] == 2){ #특정사용자만 공개
                $isp = false;
                $strrst = strpos($row['issectxt'], $_SESSION['userck']);
                if($strrst === false){
                    if($row['author_id'] !== $_SESSION['userid']){
                    echo '<div style="text-align:center"><h5>열람이 허용되지 않았습니다.</h5>';
                        if(!empty($_SESSION['userid'])){
                            echo '열람 허가를 요청하시겠습니까?<br>';
                            echo '<form method="post" action="/knock.php">
                            <input type="hidden" name="from" value="'.$board.'">
                            <input type="hidden" name="id" value="'.$id.'">
                            <input type="hidden" name="a" value="'.$a_id.'">
                            <input type="hidden" name="title" value="'.$title.'">
                            <textarea name="knockReason" class="form-control-sm" placeholder="열람 신청 사유"></textarea>
                            <button type="submit" class="btn btn-primary">신청</button>
                            </form>';
                        }
                    echo '</div>';
                    include_once 'down.php';
                    exit;
                    }
                }
            }else{
                $isp = true;
            }


        	if($row['stat'] == 0){
        					$g = '<span style="color: black">0</span>';
        	}elseif($row['stat'] < 1){
        		 $g = '<span style="color: red">'.$row['stat'].'</span>';
        	}else{
        		$g = '<span style="color: blue">'.$row['stat'].'</span>';
            }
            if($row['edited'] == 1){
                    $sqla = "SELECT * FROM `_edit` WHERE `id` like '$id' ORDER BY `count` DESC LIMIT 1";
                    $resulta = mysqli_query($conn, $sqla);
                    while ($rowa = mysqli_fetch_array($resulta)){
                        $editT = $rowa['time'];
                        $editP = $rowa['author_id'];
                    }
                $commentedited = '<button type="button" data-toggle="tooltip" data-placement="top" title="'.$editT.'<br>'.$editP.'" data-html="true"
                class="badge badge-primary">*수정됨</button> ';
            }else{
                $commentedited = '';
            }
        echo '<div class="card"><div class="card-header" style="background-color: #ddeaff"><h5 style="float:left">'
        .$row['title'].'</h5><div class="btn-group" role="group" style="float:right;">
         <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle"
          data-toggle="dropdown" aria-haspopupage="true" aria-expanded="false">이 글을</button>
           <div class="dropdown-menu" aria-labelledby="btnGroupDrop1"> '.$ednrm.'</div> </div></div><div class="card-body">';
        $rowtitle = $row['title'];
        $rowarid = $row['author_id'];
        if($row['view'] > 9999){$row['view'] = '10000+';}elseif($row['view'] > 999){$row['view'] = '1000+';}
        echo '<form method="post" action="/edit.php"><b><a href="/user.php?a='
        .$row['author_id'].'">'.$row['name'].'</a></b><span style="color: gray; font-size: 7pt">('
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
        if($makeNotice === TRUE){
            echo '<form method="post" action="/owner_tool.php?mode=pin"><table class="table"><tr><td>
            <input type="hidden" name="from" value="'.$board.'">
            <input type="hidden" name="id" value="'.$id.'">';
            if($isPinned === FALSE){
                echo '<button type="submit" class="badge badge-primary text-white" style="float:right">공지로 지정</button>';
            }elseif($isPinned === TRUE){
                echo '<button type="submit" formaction="/owner_tool.php?mode=cancel" class="badge badge-primary text-white" style="float:right">공지 내리기</button>';
            }
            
                if($canDelete == TRUE){
                    if($board !== 'trash'){
                        echo '<button type="submit" formaction="/owner_tool.php?mode=blind" class="badge badge-dark text-white" style="float:left">게시글 비공개</button>';
                    }else{
                        echo '<button type="submit" formaction="/owner_tool.php?mode=restore" class="badge badge-dark text-white" style="float:left">게시글 복원</button>';
                    }
                }
                if($canKick == TRUE){
                    $sqlb = "SELECT * FROM `_kicked` WHERE `board_id` like '$board' and `user_id` like '$rowarid';";
                    $resultb = mysqli_query($conn, $sqlb);
                    if(mysqli_num_rows($resultb) == 0){
                        echo '<button type="button" class="badge badge-danger text-white" style="float:left"
                        data-toggle="modal" data-target="#kickModal">작성자 차단</button>';
                        echo '
                        <div class="modal fade" id="kickModal" tabindex="-1" role="dialog" aria-labelledby="kickModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="kickModalLabel">작성자 차단</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                    <label class="my-1 mr-2" for="inlineFormCustomSelectPref">차단 기간</label>
                                    <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref" name="kicOpt"
                                    onchange="document.getElementById(\'kickCheck\').style.display = \'\';">
                                    <option disabled selected>--선택해주세요.--</option>
                                    <option value="5">5분</option>
                                    <option value="30">30분</option>
                                    <option value="60">1시간</option>
                                    <option value="1440">1일</option>
                                    <option value="10080">1주</option>
                                    <option value="43800">1개월</option>
                                    <option value="262800">6개월</option>
                                    <option value="525600">1년</option>
                                    <option value="52560000">무기한</option>
                                    </select>
                                    <input type="hidden" name="writer" value="'.$rowarid.'">
                                    <div class="custom-control custom-checkbox my-1 mr-sm-2" id="kickCheck" style="display:none">
                                    <input type="checkbox" class="custom-control-input" id="customControlInline">
                                    <label class="custom-control-label" for="customControlInline"
                                    onclick="document.getElementById(\'submitkickModal\').disabled = \'\';">제재에 대한 책임을 감수하겠습니다.</label>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" formaction="/owner_tool.php?mode=kick" class="btn btn-warning" id="submitkickModal" disabled>차단</button>

                            </div>
                            </div>
                        </div>
                    </div>
                        ';
                }elseif(mysqli_num_rows($resultb) == 1){
                    echo '<button type="submit" class="badge badge-danger text-white" formaction="/owner_tool.php?mode=unkick" style="float:left">작성자 차단 해제</button>
                    <input type="hidden" name="writer" value="'.$rowarid.'">';
                }
            }
            echo '</td></tr></table></form>';
        }
        echo '<table style="width:100%">';
        $sql = "SELECT * FROM `_comment` WHERE board = '".$board."' AND original = '{$id}'";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)){
            $sqi = "SELECT * FROM `_account` WHERE id = '".$row['id']."'";
            $resulp = mysqli_query($conn, $sqi);
            while ($raw = mysqli_fetch_array($resulp)){
                $user_email = $raw['email'];
            }
            $hash = md5( strtolower( trim( "$user_email" ) ) );
                $accept = '<button class="btn btn-sm btn-light" style="display:inline">허가</button>';
            if($row['stat'] > 6){
                $commentheadline = 'style="background-color:lightgreen"';
                $commentheadtext = '<span class="badge badge-primary">추천 : '.$row['stat'].'명</span> ';
            }elseif($row['blame'] > 6){
                $commentheadline = 'style="background-color:#ffcccb"';
                $commentheadtext = '<span class="badge badge-warning">반대 : '.$row['blame'].'명</span> ';
            }elseif($row['ment'] == 1){
                $commentheadline = 'style="background-color:#E5FCFD;color:gray;border: dashed 1px royalblue"';
                $commentment = '님을 호출하셨습니다.';
                $commentment .= '<br>"<span style="color:black;">'.$row['remarks'].'</span>"';
            }elseif($row['ment'] == 2){
                $commentheadline = 'style="background-color:#fde6e5;color:gray;border: dashed 1px royalblue"';
                $commentment = '<form method="post" action="/knock.php">
                <input type="hidden" name="from" value="'.$board.'">
                <input type="hidden" name="id" value="'.$id.'">
                <input type="hidden" name="by" value="'.$row['id'].'">
                <input type="hidden" name="mode" value="accept">
                <input type="hidden" name="num" value="'.$row['num'].'">
                님이 이 글을 보고싶어합니다. '.$accept.'</form>';
                $commentment .= '<br>"<span style="color:black">'.$row['remarks'].'</span>"';
            }elseif($row['ment'] == 3){
                $commentheadline = 'style="background-color:#fde6e5;color:gray;border: dashed 1px royalblue"';
                $commentment = '게시글 열람 허가됨';
                $commentment .= '<br>"<span style="color:black">'.$row['remarks'].'</span>"';
            }else{
                $commentheadline = '';
                $commentheadtext = '';
                $commentment = '';
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
            $rowcmid = $row['id'];
            echo '<h5 class="mt-0"><a href="/user.php?a='.$row['id'].'">'.$row['name'].'</a><span style="color: gray;font-size:0.5em">('.$row['id'].')</h5>';
            echo '<p>'.$commentheadtext.$commentedited.$row['content'].$commentment.'</p>';
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
            <button type="submit" style="width: 100%" class="btn btn-success">답변 작성</button>
            <input type="hidden" name="o" value="'.$num.'">
            <input type="hidden" name="m" value="'.$id.'">
            <input type="hidden" name="b" value="'.$board.'">
            <input type="hidden" name="title" value="'.$rowtitle.'">
            <input type="hidden" name="to" value="'.$rowname.'"><input type="hidden" name="id" value="'.$rowcmid.'">';
            echo '</form></div>
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
                $rep_num = $raw['num'];
                $rowcmid = $raw['id'];
        echo '<br><br><div class="media">
        <img src="https://secure.gravatar.com/avatar/'.$hash.'?s=64&d=identicon" class="mr-3 rounded" alt="Gravatar">
        <div class="media-body">
          <div id="fn_reply_'.$rep_num.'"><h5 class="mt-0">
          <a href="/user.php?a='.$raw['id'].'">'.$raw['name'].'</a><span style="color: gray;font-size:0.5em">('.$raw['id'].')</h5>
            <p>'.$raw['content'].'</p></div><span style="color:gray">'.$raw['created'].'</span>';
            if($_SESSION['userid'] == $raw['id']){
                echo ' <a class="badge badge-secondary text-white" href="/reply_mod.php?a=edit&n='.$raw['num'].'">수정</a>
                <a class="badge badge-danger text-white" href="/reply_mod.php?a=delete&n='.$raw['num'].'">삭제</a>';
            }
            $num = $raw['num'];
            if(!empty($_SESSION['userck'])){
                    if($raw['id'] !== $_SESSION['userid']){
                        echo ' <a class="badge badge-success" href="/reply_mod.php?a=push&n='.$raw['num'].'">추천</a>
                        <a class="badge badge-warning" href="/reply_mod.php?a=blame&n='.$raw['num'].'">반대</a>';
                    }
            echo ' <button class="badge badge-light" data-toggle="collapse" href="#reply'.$num.'" role="button" aria-expanded="false" aria-controls="#reply'.$num.'">답변</button>';
            }
            echo '<div class="collapse" id="reply'.$num.'">
            <form action="/reply.php?step=1" id="wrtrpl2'.$num.'" method="post">
            <textarea name="d" id="rpltxt2'.$num.'" class="border text-dark" style="width:100%"></textarea>
            <button type="submit" style="width: 100%" class="btn btn-success">답변 작성</button>
            <input type="hidden" name="o" value="'.$num.'">
            <input type="hidden" name="m" value="'.$id.'">
            <input type="hidden" name="b" value="'.$board.'">
            <input type="hidden" name="title" value="'.$rowtitle.'">
            <input type="hidden" name="to" value="'.$rowname.'"><input type="hidden" name="id" value="'.$rowcmid.'">
            </form></div>
            <script>
            $(function ()
            {
                $(document).on("keydown", "#rpltxt2'.$num.'", function(e)
                {
                    if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey)
                    {
                      $("#wrtrpl2'.$num.'").submit();
                    }
                });
            });
            </script>
            ';
                $sqt = "SELECT * FROM `_reply` WHERE resp = '$rep_num' AND step = 2";
                $resuli = mysqli_query($conn, $sqt);
                while ($riw = mysqli_fetch_array($resuli)){
                    $user_emailt = $riw['email'];
                    $hasht = md5( strtolower( trim( "$user_emailt" ) ) );
                    $rep_num = $riw['num'];
                    echo '<div class="media mt-3"><img src="https://secure.gravatar.com/avatar/'.$hasht.'?s=64&d=identicon" class="mr-3 rounded" alt="Gravatar">
                    <div class="media-body"><div id="fn_reply_'.$rep_num.'">
                    <h6 class="mt-0"><a href="/user.php?a='.$riw['id'].'">'.$riw['name'].'
                    </a><span style="color: gray;font-size:0.5em">('.$riw['id'].')</h6>'.''.$riw['content'].'</div></div>
                    </div>
                    ';
            }
        echo '</div>
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
        <input type="hidden" name="user" value="'.$rowarid.'">';
        if($isp === false){
            echo '<input type="hidden" name="s" value="true">';
        }
        echo '</form></td></tr>
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
echo '</table></div></div>';
$db = $conn;
if(isset($_GET['page'])) {
    $page = $_GET['page'];
}else{
    $page = 1;
}
$sql = 'select count(*) as cnt from `_article` WHERE `from` LIKE "'.$board.'" order by id desc';
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
    $sql = 'select * from `_article` WHERE `from` LIKE "'.$board.'" order by id desc' . $sqlLimit;
    $result = $db->query($sql);
    ?>
    <article>
        <div class="container">
                <hr>
                    <form method="post" action="/write.php">
                    <button type="button" data-toggle="modal" data-target="#mentModal" style="float: left" class="btn-sm btn-info text-white">사용자 호출</button>
                    <input type="hidden" name="from" value="<?php echo $board ?>">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="title" value="<?php echo $rowtitle ?>">
                    <button type="submit" class="btn-sm btn-success" style="float: right">글쓰기</button>
                    </form><br>
                    </h4>
                <hr>
            </div>
                            <div class="modal fade" id="mentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">사용자 호출</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                            <div class="modal-body">
                            <form class="form-inline" method="post" action="/ment.php"><table class="table">
                            <label><tr><td>호출할 아이디</td><td><input name="mentID" type="text" style="width:100%" class="form-control-sm" 
                            placeholder="아이디를 입력해주세요." required></td></tr></label>
                            <label><tr><td>부른 이유</td><td><textarea name="mentReason" class="form-control-sm" style="width:100%;height:5em" 
                            placeholder="반드시 입력해주세요." required></textarea></td></label></table>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-info">전송</button>
                            <input type="hidden" name="from" value="<?=$board?>">
                            <input type="hidden" name="id" value="<?=$id?>">
                            <input type="hidden" name="title" value="<?=$rowtitle?>">
                            </form>
                            </div>
                            </div>
                            </div>
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
                            $o_id = Filt($_GET['id']);
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
                    <?php
                        if($o_id == $id){
                            $row['title'] = '<b>'.$row['title'].'</b>';
                            echo '<tr class="table-primary"><td>';
                        }else{
                            echo '<tr><td>';
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
                        echo '<a class="links" href="/b/'.$board.'/'.$readpage.'/'.$id.'">'; echo $islock.$row['title']; echo ' &nbsp; <span class="badge badge-secondary">'.$row['comment'].'</span>'; ?></a><br>
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
echo $reportmodal;
$is_board = TRUE;
require 'down.php';
?>