<footer>
    <div class="container" align="center">
<?php if($is_board === TRUE){
echo '<form action="/search.php" method="get"><select class="form-control-sm" name="mode"> <option value="1">제목</option>
<option value="2">내용</option> <option value="3">댓글</option> <option value="4">작성자</option> <option disabled>통합검색</option> </select>
        <input class="form-control-sm" type="text" aria-label="Search" name="query" maxlength="100">
        <span align="right"><button class="btn btn-primary form-control-sm" type="submit">검색</button></span>
        <input type="hidden" name="board" value="'.$board.'">
      </form>';
} ?>
      </div>
</div>
  </main>

        <div class="container">
   <hr>
    <p align="center"><button type="button" class="btn btn-lg btn-light" data-toggle="collapse" href="#footer" role="button" aria-expanded="false" aria-controls="footer">
    (c) 2019 <?=$fnSiteName?></button>
    <br><div class="collapse" id="footer">
   <?=$fnSiteFooter?>
    </div></p>

</div>
</footer>
<script src='//code.jquery.com/jquery.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<?php
#멘션 처리하는 부분
$no = $_GET['no'];
if($no !== ''){
$sql = "UPDATE `_ment` SET `read` = '1' WHERE `_ment`.`no` = $no;";
$result = mysqli_query($conn, $sql);
}

#멘션 보여주는 부분

if($showAlerts == '1'){
      if(!empty($_SESSION['userid'])){
                  $userid = $_SESSION['userid'];
                  $sql = "SELECT * FROM `_ment` WHERE `to` LIKE '$userid' and `read` like 0";
                  $result = mysqli_query($conn, $sql);
            if(1 <= mysqli_num_rows($result)){
                  while($row = mysqli_fetch_array($result)){
                  $link = $row['link'];
                  $no = $row['no'];
                  $go = $row['go'];
                  echo "<script>
                        toastr.options = {
                        'positionClass': 'toast-bottom-right',
                        'closeButton': true,
                        'timeOut': 3000,
                        'onclick': function() {
                        location.replace('$link&no=$no#$go')
                        }
                  }
                        toastr.success('".$row['msg']."','<h4>전해드려요!</h4>')
            </script>";
                  }
            }
      }
}


?>
</body>
<!--
      이 사이트는 Fnbase.xyz 에서 배포하는
      FNBase Engine의 버전 1 미만 프로그램을 사용하여 구동되고 있습니다.

      This site is running on FNBE.
      FNBase Engine(FNBE) is maintananced by Fnbase.xyz.
      This sites engine is pre-relased version.
-->
</html>