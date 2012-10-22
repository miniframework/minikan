<?php
date_default_timezone_set('Asia/Shanghai');
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/simple_html_dom.php';



$pass = array(
		//letv
"乐视大牌党",
"乐视指数",
"乐视出品",
"乐视台",
"我为校花狂",
"乐视制造",
"午间道",
"影视风向榜",
"星宾乐",
"我记录",
"高清影院",
"会员中心",
"iPad",
"iPhone",
"Android",
"乐视网络电视",
"乐视影视",
"云视频超清机",
"看音乐",
"乐视网TV版",
"大咔",
"投资者关系",
"关于乐视",
"乐视招聘",
"版权声明",
"帮助中心",
"联系方式",
"意见反馈",
"京ICP备09045969",
"京ICP证060072号",
"网络视听许可证0105097号",
"不良信息举报中心",
"网络文化经营许可证 文网文[2009]221号",
"高清",		
"喜剧",
"动作",
"爱情",
"恐怖",
"奇幻",
"战争",
"犯罪",
"悬疑",
"冒险",
"伦理",
"家庭",
"剧情",
"中国大陆",
"中国香港",
"中国台湾",
"美国",
"韩国",
"甄子丹",
"林心如",
"赵 薇",
"范冰冰",
"舒 淇",
"张柏芝",
"谢霆锋",
"刘亦菲",
"全部",
"剧情",
"喜剧",
"动作",
"爱情",
"恐怖",
"动画",
"战争",
"惊悚",
"悬疑",
"奇幻",
"犯罪",
"冒险",
"科幻",
"警匪",
"武侠",
"灾难",
"伦理",
"歌舞",
"家庭",
"纪录",
"历史",
"短片",
"传记",
"体育",
"全部",
"中国大陆",
"中国香港",
"中国台湾",
"日本",
"韩国",
"美国",
"泰国",
"法国",
"马来西亚",
"新加波",
"西班牙",
"加拿大",
"俄罗斯",
"印度",
"英国",
"全部",
"2012",
"2011",
"2010",
"2009",
"2008",
"2007",
"2006",
"2005",
"2004",
"2003",
"2002",
"更早",
"筛选",
"预告片频道",
"进入专题",
"&nbsp;",
		//youku
"预告",
"新片预告",
"首页",
"电影",
"电视剧",
"动漫",
"娱乐",
"音乐",
"综艺",
"纪录片",	
"电影最新资讯",
"更多",
"预告片",
"最新电影",	
		);
$hotword = array();
$curl = new mini_tool_curl();

$data = $curl->getData("http://movie.tudou.com/");
$data = mb_convert_encoding($data, 'utf-8', 'gbk');
$dom = new simple_html_dom();
$dom->load($data);

$hrefs = $dom->find(".m",0)->find("a");
foreach($hrefs as $k => $v)
{
	$ahref =  trim($v->plaintext);

	if(preg_match('/&lt;(.*?)&gt;/', $ahref,$match))
	{
		$ahref = $match[1];
	}
	if(preg_match('/《(.*?)》/', $ahref,$match))
	{
		$ahref = $match[1];
	}
	if(!empty($ahref) && !in_array($ahref,$pass) && strlen($ahref) < 20)
		$hotword[] = $ahref;
}


// $curl = new mini_tool_curl();

$data = $curl->getData("http://movie.letv.com/");
$dom = new simple_html_dom();
$dom->load($data);
$hrefs = $dom->find("a");
foreach($hrefs as $k => $v)
{
	 $ahref =  trim($v->plaintext);
	 
	 	if(preg_match('/&lt;(.*?)&gt;/', $ahref,$match))
	 		{
	 			$ahref = $match[1];
	 		}
 		if(preg_match('/《(.*?)》/', $ahref,$match))
 			{
 				$ahref = $match[1];
 			}
	if(!empty($ahref) && !in_array($ahref,$pass) && strlen($ahref) < 20)
		$hotword[] = $ahref;
}
$db  = mini_db_connection::getHandle();
$basehot = date("y")%10*1000+date("z")+100;
$hotword_arr = array();
if(!empty($hotword))
	foreach($hotword as $k => $word)
{
	$sql = "select id,hot from vgroups where title='$word'";
	$row = $db->find($sql);
	if(!empty($row))
	{
		echo $row['id']."\r\n";
		$hot = $row['hot'];
		$arr = array();
		
		$arr['firhot'] = substr($hot,1,2);
		$arr['midhot'] = substr($hot,3,2);
		$arr['endhot'] = $basehot;
		if(array_key_exists($row['id'], $hotword_arr))
		{
			$hotword_arr[$row['id']]['endhot'] = $hotword_arr[$row['id']]['endhot']+1;
		}
		else
		{
			
			$hotword_arr[$row['id']] = $arr;
		}
	}
}
if(!empty($hotword_arr))
	foreach($hotword_arr as $k => $v)
{
	 $model = mini_db_model::model("vgroups");
	 $vgroup = $model->getByPk($k);
	 if(!empty($vgroup))
	 {
	 	$vgroup->combineHot($v['firhot'], $v['midhot'], $v['endhot']);
	 }
}
mini_db_unitofwork::getHandle()->commit();

// echo date("y")%10*1000+date("z")+100;
// $hot = '0';
// $arr = array();
// $arr['firhot'] = substr($hot,1,2);
// $arr['midhot'] = substr($hot,3,2);
// $arr['endhot'] = substr($hot,5);
// print_r($arr);



// $db  = mini_db_connection::getHandle();
// $sql = "select id,hot from vgroups where hot !=''";
// $rows = $db->findAll($sql);
// foreach($rows as $k => $row)
// {
// 	$rows[$k]['hot'] = $row['hot'] * 2;
// }
// print_r($rows);