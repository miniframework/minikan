<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';




/*
$tudou_url = "http://movie.tudou.com/albumtop/c22t-1v-1z-1a-1y-1h-1s1p{page}.html";

for($page = 1; $page<=30 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($tudou_url, $pagetr);
	
	$row['title'] = "tudou－最旺－电影－分页".$page;
	$row['siteid'] = 1;
	$row['spidercall'] = 1;
	$row['vtype'] = 1;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
mini_db_unitofwork::getHandle()->commit();




$qq_url="http://v.qq.com/list/1_-1_-1_-1_1_0_{page}_20_0_-1.html";


for($page = 1; $page<=30 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($qq_url, $pagetr);

	$row['title'] = "QQ－最热－电影－分页".$page;
	$row['siteid'] = 9;
	$row['spidercall'] = 1;
	$row['vtype'] = 1;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
mini_db_unitofwork::getHandle()->commit();

$youku_url = "http://www.youku.com/v_olist/c_96_a__s__g__r__lg__im__st__mt__tg__d_1_et_0_fv_0_fl__fc__fe__o_1_p_{page}.html";

for($page = 1; $page<=30 ;$page++)
	{
		$pagetr = array("{page}"=>$page);
		$targeturl = strtr($youku_url, $pagetr);

		$row['title'] = "youku－历史最多－电影分页".$page;
		$row['siteid'] = 2;
		$row['spidercall'] = 1;

		$row['vtype'] = 1;
		$row['targeturl'] = $targeturl;
		echo $targeturl."...over\r\n";
		$model = mini_db_model::model('vspiders');
		$model->create($row);
	}
	mini_db_unitofwork::getHandle()->commit();

$sohu_url="http://so.tv.sohu.com/list_p1100_p2_p3_p4-1_p5_p6_p71_p82_p9-1_p10{page}_p11.html";


for($page = 1; $page<=30 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($sohu_url, $pagetr);

	$row['title'] = "Sohu－总播放－电影－分页".$page;
	$row['siteid'] = 6;
	$row['spidercall'] = 1;
	$row['vtype'] = 1;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
mini_db_unitofwork::getHandle()->commit();



$letv_url="http://so.letv.com/list/c1_t-1_a-1_y-1_f-1_at1_o3_i-1_p{page}.html";

for($page = 1; $page<=30 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($letv_url, $pagetr);

	$row['title'] = "letv－最热－电影－分页".$page;
	$row['siteid'] = 8;
	$row['spidercall'] = 1;
	$row['vtype'] = 1;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
mini_db_unitofwork::getHandle()->commit();


	$pptv_url="http://list.pptv.com/sort_list/1------6---{page}.html";
	for($page = 1; $page<=30 ;$page++)
	{
		$pagetr = array("{page}"=>$page);
		$targeturl = strtr($pptv_url, $pagetr);

		$row['title'] = "pptv－最好人气－电影－分页".$page;
		$row['siteid'] = 7;
		$row['spidercall'] = 1;
		$row['vtype'] = 1;
		$row['targeturl'] = $targeturl;
		echo $targeturl."...over\r\n";
		$model = mini_db_model::model('vspiders');
		$model->create($row);
	}
	mini_db_unitofwork::getHandle()->commit();
*/
// 	$url="http://tv.tudou.com/albumtop/c30t-1v-1z-1a-1y-1h-1s1p{page}.html";
// 	for($page = 1; $page<=30 ;$page++)
// 		{
// 			$pagetr = array("{page}"=>$page);
// 			$targeturl = strtr($url, $pagetr);

// 			$row['title'] = "tudou-最旺-电视剧-分页".$page;
// 			$row['siteid'] = 1;
// 			$row['spidercall'] = 2;
// 			$row['vtype'] = 2;
// 			$row['targeturl'] = $targeturl;
// 			echo $targeturl."...over\r\n";
// 			$model = mini_db_model::model('vspiders');
// 			$model->create($row);
// 		}
// 		mini_db_unitofwork::getHandle()->commit();
// 	$url="http://www.youku.com/v_olist/c_97_a__s__g__r__lg__im__st__mt__tg__d_1_et_0_fv_0_fl__fc__fe__o_7_p_{page}.html";
// 	for($page = 1; $page<=30 ;$page++)
// 	{
// 		$pagetr = array("{page}"=>$page);
// 		$targeturl = strtr($url, $pagetr);
	
// 		$row['title'] = "youku-近日最多-电视剧分页".$page;
// 		$row['siteid'] = 2;
// 		$row['spidercall'] = 2;
// 		$row['vtype'] = 2;
// 		$row['targeturl'] = $targeturl;
// 				echo $targeturl."...over\r\n";
// 		$model = mini_db_model::model('vspiders');
// 		$model->create($row);
// 	}
// 	mini_db_unitofwork::getHandle()->commit();
	
// 	$url="http://so.letv.com/list/c2_t-1_a-1_y-1_f-1_at1_o1_i-1_p{page}.html";
// 	for($page = 1; $page<=30 ;$page++)
// 	{
// 	$pagetr = array("{page}"=>$page);
// 	$targeturl = strtr($url, $pagetr);
	
// 		$row['title'] = "letv-最近更新-电视剧-分页".$page;
// 		$row['siteid'] = 8;
// 		$row['spidercall'] = 2;
// 		$row['vtype'] = 2;
// 		$row['targeturl'] = $targeturl;
// 		echo $targeturl."...over\r\n";
// 		$model = mini_db_model::model('vspiders');
// 		$model->create($row);
// 	}
// 	mini_db_unitofwork::getHandle()->commit();
	
	
// 	$url="http://v.qq.com/list/2_-1_-1_-1_1_0_{page}_20_-1_-1.html";
// 	for($page = 1; $page<=30 ;$page++)
// 	{
// 		$pagetr = array("{page}"=>$page);
// 		$targeturl = strtr($url, $pagetr);
		
// 		$row['title'] = "qq－最热－电视剧－分页".$page;
// 		$row['siteid'] = 9;
// 		$row['spidercall'] = 2;
// 		$row['vtype'] = 2;
// 		$row['targeturl'] = $targeturl;
// 		echo $targeturl."...over\r\n";
// 		$model = mini_db_model::model('vspiders');
// 		$model->create($row);
// 	}
// 	mini_db_unitofwork::getHandle()->commit();


// 	$url="http://so.tv.sohu.com/list_p1101_p2_p3_u5185_u5730_p4-1_p5_p6_p70_p80_p9-1_p10{page}_p11.html";
// 	for($page = 1; $page<=20 ;$page++)
// 		{
// 			$pagetr = array("{page}"=>$page);
// 			$targeturl = strtr($url, $pagetr);

// 			$row['title'] = "sohu－相关程度-电视剧分页".$page;
// 			$row['siteid'] = 6;
// 			$row['spidercall'] = 2;
// 			$row['vtype'] = 2;
// 			$row['targeturl'] = $targeturl;
// 			echo $targeturl."...over\r\n";
// 			$model = mini_db_model::model('vspiders');
// 			$model->create($row);
// 		}
// 		mini_db_unitofwork::getHandle()->commit();
		
// 	$url="http://list.pptv.com/sort_list/2------6---{page}.html";
// 	for($page = 1; $page<=20 ;$page++)
// 	{
// 		$pagetr = array("{page}"=>$page);
// 		$targeturl = strtr($url, $pagetr);
	
// 		$row['title'] = "pptv-最高人气－电视剧－分页".$page;
// 		$row['siteid'] = 7;
// 		$row['spidercall'] = 2;
// 		$row['vtype'] = 2;
// 		$row['targeturl'] = $targeturl;
// 				echo $targeturl."...over\r\n";
// 		$model = mini_db_model::model('vspiders');
// 		$model->create($row);
// 	}
// 	mini_db_unitofwork::getHandle()->commit();
	

	
	$url="http://cartoon.tudou.com/albumtop/c9t-1v-1z-1a-1y-1h-1s1p{page}.html";
	for($page = 1; $page<=30 ;$page++)
	{
		$pagetr = array("{page}"=>$page);
		$targeturl = strtr($url, $pagetr);
		
		$row['title'] = "tudou-最热-动漫-分页".$page;
		$row['siteid'] = 1;
		$row['spidercall'] = 3;
		$row['vtype'] = 3;
		$row['targeturl'] = $targeturl;
		echo $targeturl."...over\r\n";
		$model = mini_db_model::model('vspiders');
		$model->create($row);
	}
	mini_db_unitofwork::getHandle()->commit();


$url="http://www.youku.com/v_olist/c_100_a_%E6%97%A5%E6%9C%AC_s__g__r__lg__im__st__mt__tg__d_1_et_0_fv_0_fl__fc__fe__o_7_p_{page}.html";
for($page = 1; $page<=30 ;$page++)
{
	$pagetr = array("{page}"=>$page);
	$targeturl = strtr($url, $pagetr);

	$row['title'] = "youku-最热-动漫-分页".$page;
	$row['siteid'] = 2;
	$row['spidercall'] = 3;
	$row['vtype'] = 3;
	$row['targeturl'] = $targeturl;
	echo $targeturl."...over\r\n";
	$model = mini_db_model::model('vspiders');
	$model->create($row);
}
	mini_db_unitofwork::getHandle()->commit();

	
	$url="http://so.letv.com/list/c3_t-1_a41_y-1_f-1_at-1_o3_i-1_p{page}.html";
	for($page = 1; $page<=30 ;$page++)
	{
		$pagetr = array("{page}"=>$page);
		$targeturl = strtr($url, $pagetr);
	
		$row['title'] = "letv-最热-动漫-分页".$page;
		$row['siteid'] = 8;
		$row['spidercall'] = 3;
		$row['vtype'] = 3;
		$row['targeturl'] = $targeturl;
		echo $targeturl."...over\r\n";
		$model = mini_db_model::model('vspiders');
		$model->create($row);
	}
	mini_db_unitofwork::getHandle()->commit();
	
// 	$url="http://list.pptv.com/sort_list/3--8-------{page}.html";
// 	for($page = 1; $page<=5 ;$page++)
// 	{
// 		$pagetr = array("{page}"=>$page);
// 		$targeturl = strtr($url, $pagetr);
		
// 		$row['title'] = "pptv动漫分页".$page;
// 		$row['siteid'] = 7;
// 		$row['spidercall'] = 3;
// 		$row['vtype'] = 3;
// 		$row['targeturl'] = $targeturl;
// 		echo $targeturl."...over\r\n";
// 		$model = mini_db_model::model('vspiders');
// 		$model->create($row);
// 	}
// 	mini_db_unitofwork::getHandle()->commit();
//$map = array(1=>"tudou",2=>"youku",3=>"163",4=>"sina",5=>"m1905",6=>"sohu",7=>"pptv",8=>'letv',9=>'qq');
