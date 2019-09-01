<?php
include_once 'function.php';
$sql = "SELECT * FROM `_kicked` WHERE `user_id` like '$u_id';";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0){
        $sql_ = "SELECT * FROM `_kicked` WHERE `board_id` like '$board' and `user_id` like '$u_id';";
        $result_ = mysqli_query($conn, $sql_);
        if(mysqli_num_rows($result_) == 1){
            while($row = mysqli_fetch_array($result_)){
                    $term = $row['term']*60;
                    $date = strtotime($row['date']);
                    $time = time();
                    $kickterm = $term + $date;
                    $t = $kickterm - $time;
                    $tm = $t / 60;
                    if($kickterm >= $time){
                        echo '<h4>접근 권한이 없습니다.</h4>';
                        echo '<p class="text-muted">게시판 소유주에 의해 차단되었거나, 접근이 제한된 게시판입니다.</p>';
                            echo '<span>'.ceil($tm).'분 <sub><span class="text-muted">('.$t.'초)</span></sub> 후 차단 해제</span>';
                        include 'down.php';
                        exit;
                    }
                    if($kickterm < $time){
                        $sql__ = "DELETE FROM `_kicked` WHERE `board_id` like '$board' and `user_id` like '$u_id'";
                        $result__ = mysqli_query($conn, $sql__);
                        if($result__ === FALSE){
                            echo '데이터베이스 연결 오류 -- id_ban_delete';
                            exit;
                        }
                    }
            }
        }
        $sql___ = "SELECT * FROM `_kicked` WHERE `user_id` like '$u_id' and `type` like '1';";
        $result___ = mysqli_query($conn, $sql___);
        if(mysqli_num_rows($result___) == 1){
            while($row = mysqli_fetch_array($result___)){
                $term = $row['term']*60;
                $date = strtotime($row['date']);
                $time = time();
                $kickterm = $term + $date;
                $t = $kickterm - $time;
                $tm = $t / 60;
            }
            echo '<h4>접근 권한이 없습니다.</h4>';
            echo '<p class="text-muted">당신은 사이트 소유주에 의해 광역 차단되셨습니다.</p>';
                echo '<span>'.ceil($tm).'분 <sub><span class="text-muted">('.$t.'초)</span></sub> 후 차단 해제</span>';
            include 'down.php';
            exit;
        }
}
?>