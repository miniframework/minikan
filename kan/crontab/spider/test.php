<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';
// $url = "http://www.m1905.com/api/interface/sogouys-p-m-videolist-op-add.xml";
// // $data = curlByUrl($playurl);

	
// // if(preg_match_all("/(?:listData=\[)?{.*?iid:(\d+).*?cartoonType.*?}/ism", $data, $match))
// // {
// // 	print_r($match);
// // }

// // spiderApiMoviem1905($url);
// $url = "/vod/play/86084.shtml";
// preg_match("/\/vod\/play\/(\d+)\./",$url, $match);

// print_r($match);
// $ids = $match[1];
// echo $ids[0],$ids[1];
//$group = new groupService();
//$sql = "select title from videos limit 1000,1000";

//$db = mini_db_connection::getHandle();
//$rows = $db->findAll($sql);
/*
foreach($rows as $k => $v)
{
	print_r($group->formatTitle($v['title']));
}
*/

// $curl = new mini_tool_curl();
// $url = "https://www.douban.com/service/auth2/token";
// $post = "client_id=056ea9e1004bb1a5104bfda300e314ef&client_secret=93c50a826de4b9a2&grant_type=password&username=kaiwang@sogou-inc.com&password=sogoudh123";
// $data = $curl->post($url, $post);
// var_dump($data);

$douban = new doubanService();
$douban->Auth();
?>
