<?php
include dirname(__FILE__).'/../init.php';

$params = array('cookiefile'=>'./douban_spider_cookie.txt');


$doubanspider = new doubanspiderService($params);
// $douban = new doubanService();
$sql = "select * from videos limit 0,10";

$db = mini_db_connection::getHandle();
$rows = $db->findAll($sql);

foreach($rows as $k => $v)
{
	$data = $doubanspider->searchLikeApi($v);
// 	$data = $douban->movie($v,$v['title']);
	print_r($data);
}


