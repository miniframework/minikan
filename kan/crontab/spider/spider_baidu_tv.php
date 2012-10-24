<?php
date_default_timezone_set('Asia/Shanghai');
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/simple_html_dom.php';

$curl = new mini_tool_curl();

$data = $curl->getData("http://top.baidu.com/rss_xml.php?p=tv");
$data = mb_convert_encoding($data, 'utf-8', 'gbk');

preg_match_all('/<tr>.*?<a.*?>(.*?)<\/a>/ism', $data, $matches);

$tv_order = array_reverse($matches[1]);
print_r($tv_order);
$db  = mini_db_connection::getHandle();
$hotword_arr = array();
if(!empty($tv_order))
	foreach($tv_order as $k => $word)
{
	$sql = "select id,hot from vgroups where title='$word' and vtype=2";
	$row = $db->find($sql);
	if(!empty($row))
	{
		echo $row['id']."\r\n";
		$hot = $row['hot'];
		$arr = array();
		
		$arr['firhot'] = substr($hot,1,2);
		$arr['midhot'] = $k;
		$arr['endhot'] = substr($hot,5);;
	
			
		$hotword_arr[$row['id']] = $arr;
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
