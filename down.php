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
<?php
$userck = $_SESSION['userck'];
$sql = "SELECT * FROM `_ment` WHERE `to` LIKE '$userck' and `read` like 0";
$result = mysqli_query($conn, $sql);
if(1 <= mysqli_num_rows($result)){
while($row = mysqli_fetch_array($result)){
  $link = $row['link'];
  $no = $row['no'];
  echo "<script>
	toastr.options = {
        'positionClass': 'toast-bottom-right',
        'closeButton': true,
        'timeOut': 3000,
        'onclick': function() {
          location.replace('$link&no=$no')
        }
  }
		toastr.success('".$row['msg']."','<h4>전해드려요!</h4>')
</script>";
}
}
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script></body>
<script>$('.collapse').collapse('hide')</script>
</html>