<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';

// $tudou_url = "http://movie.tudou.com/albumtop/c22t-1v-1z-1a-1y-1h-1s1p{page}.html";

// for($page = 1; $page<=30 ;$page++)
// {
// 	$pagetr = array("{page}"=>$page);
// 	$targeturl = strtr($tudou_url, $pagetr);
	
// 	$row['title'] = "土豆电影分页".$page;
// 	$row['spiderid'] = 1;
// 	$row['vtype'] = 1;
// 	$row['targeturl'] = $targeturl;
// 	echo $targeturl."...over\r\n";
// 	$model = mini_db_model::model('vspider');
// 	$model->create($row);
// }
/*
$youku_url = "http://www.youku.com/v_olist/c_96_a__s__g__r__lg__im__st__mt__tg__d_1_et_0_fv_0_fl__fc__fe__o_7_p_{page}.html";

for($page = 1; $page<=30 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($youku_url, $pagetr);

	$row['title'] = "优酷电影分页".$page;
	$row['siteid'] = 2;
	$row['spidercall'] = 1;
	
	$row['vtype'] = 1;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
mini_db_unitofwork::getHandle()->commit();
*/
$tudou_url = "http://movie.tudou.com/albumtop/c22t-1v-1z-1a-1y-1h-1s1p{page}.html";

for($page = 1; $page<=30 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($tudou_url, $pagetr);
	
	$row['title'] = "土豆电影分页".$page;
	$row['siteid'] = 1;
	$row['spidercall'] = 1;
	$row['vtype'] = 1;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
mini_db_unitofwork::getHandle()->commit();
