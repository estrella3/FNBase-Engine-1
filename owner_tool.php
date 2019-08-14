<?php
    require 'function.php';

    $board = Filt($_POST['from']); #게시판 id
    $id = Filt($_POST['id']); #게시글 번호
    $u_id = $_SESSION['userid']; #이용자 id

    if(!empty($u_id)){ #아이디가 없지 않으면
        $sql = "SELECT * FROM `_board` WHERE `id` like '$board'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) !== 1){ #게시판이 존재하지 않으면
            echo '<script>alert("없는 게시판입니다.")</script>';
            exit;
        }
            while($row = mysqli_fetch_array($result)){
                $owner = $row['owner'];
                $keeper = $row['keeper'];
                $volun = $row['volun'];
            }
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
    }
    $m = Filt($_GET['mode']);
    if($m == 'pin'){ #공지로 지정
        $sql = "SELECT * FROM `_pinned` WHERE `board_id` like '$board' and `article_id` like '$id';";
        $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == 0){
                $sql = "INSERT INTO `_pinned` (`num`, `board_id`, `did_id`, `article_id`) VALUES (NULL, '$board', '$u_id', '$id')";
                $result = mysqli_query($conn, $sql);
                 if($result === FALSE){
                     echo '데이터베이스 연결 오류 -- pin';
                 }else{
                     echo '<script>alert("공지로 지정했습니다.");history.back()</script>';
                 }
            }
    }
    if($m == 'cancel'){ #공지 지정 해제
        $sql = "SELECT * FROM `_pinned` WHERE `board_id` like '$board' and `article_id` like '$id';";
        $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == 1){
                $sql = "DELETE FROM `_pinned` WHERE `board_id` like '$board' and `article_id` like '$id'";
                $result = mysqli_query($conn, $sql);
                 if($result === FALSE){
                     echo '데이터베이스 연결 오류 -- un_pin';
                 }else{
                     echo '<script>alert("공지에서 내렸습니다.");history.back()</script>';
                 }
            }
    }
    if($m == 'blind'){ #게시글 보이지 않게 하기
        $sql = "SELECT * FROM `_article` WHERE `from` like '$board' and `id` like '$id';";
        $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) == 1){
                $sql = "UPDATE `_article` SET `to`='trash' WHERE `to` like '$board' and `id` like '$id'";
                $result = mysqli_query($conn, $sql);
                 if($result === FALSE){
                     echo '<script>alert("게시글이 이동되었거나 데이터베이스 연결 오류입니다. -- blind");history.back()</script>';
                 }else{
                     echo '<script>alert("비공개 처리했습니다.");history.go(-2)</script>';
                 }
            }
    }
    if($m == 'restore'){ #게시글 다시 보이게 하기
        $sql = "SELECT * FROM `_article` WHERE `id` like '$id';";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_array($result)){
            $from = $row['from'];
        }
            if(mysqli_num_rows($result) == 1){
                $sql = "UPDATE `_article` SET `to`='$from' WHERE `to` like '$board' and `id` like '$id'";
                $result = mysqli_query($conn, $sql);
                 if($result === FALSE){
                     echo '<script>alert("게시글이 이미 이동되었거나 데이터베이스 연결 오류입니다. -- restore");history.back()</script>';
                 }else{
                     echo '<script>alert("다시 공개 처리했습니다.");history.go(-2)</script>';
                 }
            }
    }
?>    