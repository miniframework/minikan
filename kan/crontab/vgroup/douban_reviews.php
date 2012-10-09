<?php
include dirname(__FILE__).'/../init.php';
date_default_timezone_set('Asia/Shanghai');
$db = mini_db_connection::getHandle();
$sql = "select id,imdb from  vgroups where imdb != '' && imdb != '0'";
$rows = $db->findAll($sql);
// $doubanApi = new doubanService();
// foreach($rows as $k => $v)
// {
// 	$reviews = $doubanApi->reviewsImdb($v['imdb']);
// 	if(empty($reviews)) continue;
// 	foreach($reviews as $kk => $rev){
// 	$row = array();
// 	$row['groupid'] = $v['id'];  
// 	$row['reviewid'] = $rev['reviewid'];
// 	$row['author'] = $rev['author'];
// 	$row['title'] = $rev['title'];
// 	$row['summary'] = $rev['summary'];
// 	$row['rating'] = $rev['rating'];
// 	$row['vote'] = $rev['vote'];
// 	$row['comment'] = $rev['comment'];
// 	$row['useless'] = $rev['useless'];
// 	$row['published'] = strtotime($rev['published']);
// 	$row['updated'] = strtotime($rev['updated']);
// 	$row['ctime'] = time();
// 	$review = mini_db_model::model("reviews");
// 	$review->create($row);
	
// 	}
// 	mini_db_unitofwork::getHandle()->commit();
// }

$doubanApi = new doubanService();
foreach($rows as $k => $v)
{
	$reviews = $doubanApi->AuthreviewsImdb($v['imdb']);
	print_r($reviews);
}
?>
