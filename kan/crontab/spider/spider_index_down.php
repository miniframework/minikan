<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';





$url = "http://www.dytt8.net/html/gndy/oumei/list_7_{page}.html";

for($page = 1; $page<=8 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($url, $pagetr);
	
	$row['title'] = "dytt8－欧美－分页".$page;
	$row['siteid'] = 10;
	$row['dtype'] = 1;
 	$row['spidercall'] = 6;
	$row['vtype'] = 1;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
mini_db_unitofwork::getHandle()->commit();

