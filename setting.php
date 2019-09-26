<?php
# 이 파일은 FNBase.xyz에서 배포하는 게시글 관리 엔진의 설정 파일입니다.
# 자세한 설정에 관해서는 https://dev.fnbase.xyz/fnbe.html 을 참고하십시오.
$setVersion = "1.0.0"; #세팅 파일이 작성될 때의 버전입니다.

#데이터베이스 연결 설정입니다.
$fnSiteDB = "";
$fnSiteDBuser = "";
$fnSiteDBpw = "";
$fnSiteDBname = "";

$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");

/* 이 아래는 일반적인 경우 수정하지 않으시는게 좋습니다. */
$fnMultiNum = 1;
$fnMultiDB_Suffix = FALSE;
$query = "SELECT * from `_Setting` WHERE `num` = $fnMultiNum";
$query_result = mysqli_query($conn, $query);
if($query_result !== FALSE){
    while($setting = mysqli_fetch_array($query_result)){
        $fnVersion = $setting["Version"];
        $fnSite = $setting["Site"];
        $fnSiteName = $setting["SiteName"];
        $fnSiteColor = $setting["SiteColor"];
        $fnSiteSubColor = $setting["SiteSubColor"];
        $fnSiteMode = $setting["SiteMode"];
        $fnSiteTitle = $fnSiteName;
        $fnSiteDesc = $setting["SiteDesc"];
        $fnSiteFab = $setting["SiteFab"];
        $fnSiteEmail = $setting["SiteEmail"];
        $fnSiteBoardList = $setting["SiteBoardList"];
        $fnSiteFooter = $setting["SiteFooter"];
        $fnSiteBoardName = $setting["SiteBoardSuffix"];
        $fnSite_Homepage = $setting["SiteHomepage"];
        $fnSite_HomepageName = $setting["SiteHomepageName"];
        $fnSite_google = $setting["Site_google"];
        $fnSite_isp = $setting["Site_isp"];
        $fnSiteTimezone = $setting["SiteTimezone"];
        $fnSiteDefaultSkin = $setting["DefaultSkin"];
        date_default_timezone_set($fnSiteTimezone);
    }
}else{
/* 데이터베이스 연결에 실패할 경우 메시지 출력 */
    if($isInstall === TRUE){
        $showError = FALSE; #PHP 에러 표시 여부.
        require "./error/db_fail.php";
    }
}
?>
