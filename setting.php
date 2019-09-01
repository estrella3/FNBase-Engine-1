<?php
# 이 파일은 FNBase.xyz에서 배포하는 게시글 관리 엔진의 설정 파일입니다.
$fnVersion = '0.9.01';


#데이터베이스 연결 설정입니다.
$fnSiteDB = 'localhost';
$fnSiteDBuser = '';
$fnSiteDBpw = '';
$fnSiteDBname = '';
$conn = mysqli_connect("$fnSiteDB", "$fnSiteDBuser", "$fnSiteDBpw", "$fnSiteDBname");


/* 이 아래는 일반적인 경우 수정하지 마시길 바랍니다. */
$fnMultiNum = 1;
$fnMultiDB_Suffix = FALSE;
$query = "SELECT * from `_Setting` WHERE `num` = $fnMultiNum";
$query_result = mysqli_query($conn, $query);
while($setting = mysqli_fetch_array($query_result)){
    $fnSite = $setting['Site'];
    $fnSiteName = $setting['SiteName'];
    $fnSiteColor = $setting['SiteColor'];
    $fnSiteSubColor = $setting['SiteSubColor'];
    $fnSiteMode = $setting['SiteMode'];
    $fnSiteTitle = $fnSiteName;
    $fnSiteDesc = $setting['SiteDesc'];
    $fnSiteFab = $setting['SiteFab'];
    $fnSiteEmail = $setting['SiteEmail'];
    $fnSiteFooter = $setting['SiteFooter'];
    $fnSiteBoardName = $setting['SiteBoardSuffix'];
    $fnSite_Homepage = $setting['SiteHomepage'];
    $fnSite_HomepageName = $setting['SiteHomepageName'];
    $fnSite_google = $setting['Site_google'];
    $fnSite_isp = $setting['Site_isp'];
    $fnSiteTimezone = $setting['SiteTimezone'];
    date_default_timezone_set($fnSiteTimezone);
}
?>