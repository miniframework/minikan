<?php

include dirname(__FILE__).'/../init.php';

$db = mini_db_connection::getHandle();
$sql = "select videoid,sum(1) from episodes  where (vtype =2 or vtype=3) and  epindex = 0  group by videoid  having sum(1)  > 1";
$rows = $db->findAll($sql);

$spider = new spiderService();

foreach($rows as $k => $row)
{
	$spider->updateEpisodeVideo("where  id ={$row['videoid']}");
}
