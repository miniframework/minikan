<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/spiderlib.php';


$url = "http://video.sina.com.cn/movie/category/movie/index.html";

print_r(spiderSina($url));

function coverSina($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	
	$star = $director = $area = $cate = $year = array();
	
	$coverinfo = $coverRoot->find(".box_introduction3", 0);
	$baseinfo = $coverRoot->find(".text1", 0);
	
	//playlink title segment
	$vrow['playlink'] = $url;
	$vrow['title'] = $baseinfo->find('.name',0)->plaintext;
	
	
	
	//info segment
	$actor = $baseinfo->find(".actor", 0);
	$starDoms = $actor->find("a");
	//star 
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
		
		
	//director
	$directord = $baseinfo->find(".director", 0);
	$directorDoms = $directord->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
	//area
	$aread = $baseinfo->find(".region", 0);
	$areaDoms = $aread->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}	
	//cate	
	$typed = $baseinfo->find(".type", 0);
	$cateDoms = $typed->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}	
	//year
	$year =	$baseinfo->find(".years", 0)->find("a",0)->plaintext;
	$summaryDom = $baseinfo->find(".drama",0);
	if(!empty($summaryDom))
	{
		$summary = str_replace("剧集介绍：","",$summaryDom->plaintext);
	}
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area;
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}

function spiderSina($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	$root = $dom->find('.list_video', 0)->children(0);
	$rows = $root->children();
	$i = 0;
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			
			$i++;
			if($i%2 == 0) continue;
			$lines = $row->find('.v_Info');
			foreach($lines as $kk => $line)
			{
				
				$coverlink = $line->find('.pic',0)->find('a',0)->href;
				$coverlink ="http://video.sina.com.cn".$coverlink;
				$cover = coverSina($coverlink);
				$imagelink = $line->find('.pic',0)->find('img',0)->src;
				
				if(!empty($cover))
				{
					$cover['imagelink'] = $imagelink;
					
					$vrow[] = $cover;
				}
			}
				print_r($vrow);
			
		}
		$root->clear();
		return $vrow;
}
?>
