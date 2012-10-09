<?php
include dirname(__FILE__).'/../init.php';
date_default_timezone_set('Asia/Shanghai');

$vgroups = mini_db_record::getAll("select * from vgroups where doubanid =0",array(), "vgroups");
$params = array('cookiefile'=>'../spider/douban_spider_cookie.txt');
$doubanspider = new doubanspiderService($params);
foreach($vgroups as $k=> $vgroup)
{
	$drow = $doubanspider->search($vgroup->getAttributes());
	if(!empty($drow))
	{
		$douban = mini_db_model::model("doubans");
		$row = array();
		foreach($drow as $d =>$v)
		{
			if($d == 'comment') continue;
			if($d == 'shortcomment') 
				$row['shortcomment'] = json_encode($v);
			else if($d == 'pic')
			{
				if(preg_match('/mpic\/(.*?)\.jpg/', $v, $match))
				{
					$row['pic'] = $match[1];
				}
			}
			else if(is_array($v)) 
				$row[$d] = implode("\t", $v);
			else 
				$row[$d] = $v;
		}
		$row['ctime'] = time();
		$row['groupid'] = $vgroup->id;
		
		$douban->create($row);
		if(!empty($drow['pic'])) 
			$vgroup->doubanimage = $drow['pic'];
		if(!empty($drow['rate']))
			$vgroup->rate = $row['rate'];
		
		$vgroup->doubanid = $row['doubanid'];
		
		
		if(!empty($drow['comment']))
		{
			foreach($drow['comment'] as $c => $vv)
			{
				$crow = array();
				$reviews = mini_db_model::model("reviews");
				$crow['title'] = $vv['title'];
				$crow['groupid'] = $vgroup->id;
				$crow['summary'] = $vv['comment'];
				$crow['doubanid'] = $row['doubanid'];
				if(preg_match('/review\/(\d+)\//', $vv['href'], $match))
				{
					$crow['reviewid'] = $match[1];
				}
				$reviews->create($crow);
			}
		}
		mini_db_unitofwork::getHandle()->commit();
		echo $vgroup->id." hit..\r\n";
	}
	else
	{
		echo $vgroup->id." no..\r\n";
	}
	
}