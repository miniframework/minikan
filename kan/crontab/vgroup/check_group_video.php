<?php
include dirname(__FILE__).'/../init.php';

$db  = mini_db_connection::getHandle();

$rows = $db->findAll("select id, videoids from vgroups");
foreach($rows as $k => $v)
{
	$jsonvideo = json_decode($v['videoids'],true);
	foreach($jsonvideo as $kk =>$vv)
	{
		$videoid = $vv['videoid'];
//		echo $videoid."\r\n";
		$vrow = $db->find("select id from videos where id=$videoid");

		if(empty($vrow))
			echo $v['id']."----".$videoid."\r\n";
	}
}
