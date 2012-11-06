<?php

include_once dirname(__FILE__).'/spiderlib.php';
function spiderDownMovieKan2008($url)
{
	echo $url."\r\n";
	$domain = "http://www.kan2008.net/";
	$drow = array();
	$data = curlByUrl($url,30);
// 	echo $data;
// 	exit;
// 	$data = mb_convert_encoding($data, "UTF-8", 'gbk');
	if(empty($data))
		return ;
	$dom = new simple_html_dom();
	$dom->load($data);
	$stickthreads = $dom->find(".xst");
	if(!empty($stickthreads))
		foreach($stickthreads as $k => $v)
	{
		$title = $v->plaintext;
		$href = $v->href;
		if(preg_match('/《(.*?)》/ims', $title,$match))
		{
			$title_arr = explode("/",trim($match[1]));
			
			
			$urlchild = $domain.$href;
			$drow = coverDownMovieKan2008($urlchild);
			if(empty($drow)) continue;
			$drow['title'] =$title_arr[0];
			$vrow[] = $drow;
		}
		else {
			continue;
		}
	}
	$dom->clear();
	return $vrow;
}
function coverDownMovieKan2008($url)
{
	echo $url."<br/>";
	$vrow = array();
	$data = curlByUrl($url,30);
	if(empty($data))
		return ;
	$dom = new simple_html_dom();
	$dom->load($data);
	$zoom = $dom->find(".t_fsz",0);
	
	$zoom_content = $zoom->innertext;
	$images = $zoom->find("img");
	
	foreach($images as $k => $img)
	{
		$src = $img->src;
		$vrow['image'][] = $src;
	
	}
	if(preg_match('/◎译.*?名(.*?)\<br/ism', $zoom_content,$match))
	{
	
		$alias = str_replace('　','',$match[1]);
	
		$vrow['alias'] = implode("\t",explode("/",trim($alias)));
	
	}
	if(preg_match('/◎年.*?代(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['year'] = str_replace('　','',$match[1]);
	}
	if(preg_match('/◎国.*?家(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['country'] = str_replace('　','',$match[1]);
		$vrow['country'] = explode("/", $vrow['country']);
	}
	if(preg_match('/◎类.*?别(.*?)\<br/ism', $zoom_content,$match))
	{
		$cate = str_replace('　','',$match[1]);
		$cate_arr = explode("/",trim($cate));
		if(!empty($cate_arr))
			foreach($cate_arr as $k => $v)
			{
				$vrow['cate'][] = trim($v);
			}
	}
	if(preg_match('/◎语.*?言(.*?)\<br/ism', $zoom_content,$match))
	{
		$lang = str_replace('　','',$match[1]);
		$lang_arr = explode("/",trim($lang));
		if(!empty($lang_arr))
			foreach($lang_arr as $k => $v)
			{
				$vrow['lang'][] = trim($v);
			}
	
	}
	if(preg_match('/◎字.*?幕(.*?)\<br/ism', $zoom_content,$match))
	{
	
		$word = str_replace('　','',trim($match[1]));
		$word_arr = explode("/",trim($word));
		if(!empty($word_arr))
			foreach($word_arr as $k => $v)
			{
				$vrow['word'][] = trim($v);
			}
	}
	if(preg_match('/◎文件格式(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['fileformat'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎视频尺寸(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['videoscreen'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎文件大小(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['filesize'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎片.*?长(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['duration'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎导.*?演(.*?)\<br/ism', $zoom_content,$match))
	{
	
		$director = str_replace('　','',$match[1]);
		$director_arr = explode("/",trim($director));
		if(!empty($director_arr))
			foreach($director_arr as $k => $v)
			{
				$vrow['director'][] = trim($v);
			}
	}
	if(preg_match('/◎主.*?演(.*?)◎简.*?介/ism', $zoom_content,$match))
	{
	
		$star = str_replace('　','',$match[1]);
		$star_arr = explode("<br />",trim($star));
		if(!empty($star_arr))
			foreach($star_arr as $k => $v)
			{
				$vrow['star'][] = trim($v);
			}
	
	
	}
	$zoom_summary = preg_replace('/<a.*?<\/a>/ims', '', $zoom_content);
	if(preg_match('/◎简.*?介(.*?)\<img/ism', $zoom_summary,$match))
	{
	
		$vrow['summary'] = trim(strip_tags(str_replace("<br />", "\r\n", trim($match[1]))));
	}
	
	$seeds = $zoom->find("a");
	if(empty($seeds)) return array();
	foreach($seeds as $k => $seed)
	{
		
		
		$href = str_replace("thunder://", "", $seed->href);
		$thunder_href = base64_decode($href);
		if(preg_match("/^AA(.+)ZZ$/i", $thunder_href, $matche))
			$vrow['seed'][] = $matche[1];
	}
	if(empty($vrow['seed'])) return array();
	if(empty($vrow['star'])) return array();
 	$dom->clear();
	return $vrow;
	
}
function spiderDownMovieDytt8($url)
{
	$domain = "http://www.dytt8.net";
	$drow = array();
	$data = curlByUrl($url,30);
	$data = mb_convert_encoding($data, "UTF-8", 'gbk');
	if(empty($data)) 
		return ;
	$dom = new simple_html_dom();
	$dom->load($data);
	$content = $dom->find(".co_content8",0);
	$tables = $content->find("table");
	$vrow = array();
	$i = 0;
	foreach($tables as $k => $tr)
	{
		$i++;
		$ahref = $tr->find("a",1);
		if(!empty($ahref))
		{
			$href =  $ahref->href;
			
			$urlchild = $domain.$href;
			$drow =coverDownMovieDytt8($urlchild);
			if(!empty($drow))
			{
				$title = $ahref->plaintext;
				if(preg_match('/《(.*?)》/ims', $title,$match))
				{
					$title_arr = explode("/",trim($match[1]));
					$drow['title'] =$title_arr[0];
				}
				else {
					$drow['title'] = $title;
				}
				
				$vrow[] = $drow;
				
				
			}
			
		}
	}
	$dom->clear();
	return $vrow;
	
}
function coverDownMovieDytt8($url)
{
	echo $url."<br/>";
	$vrow = array();
	$data = curlByUrl($url,30);
	$data = mb_convert_encoding($data, "UTF-8", 'gbk');
	if(empty($data))
		return ;
	$dom = new simple_html_dom();
	$dom->load($data);
	$zoom = $dom->find("#Zoom",0);
	if(empty($zoom)) { echo "not find zoom"; return array(); exit;}
	$zoom_content = $zoom->innertext;
	$images = $zoom->find("img");
	
	foreach($images as $k => $img)
	{
		$src = $img->src;
		$vrow['image'][] = $src;
		
	}
	$zoom_content = html_entity_decode($zoom_content,ENT_NOQUOTES,'utf-8');
	if(preg_match('/◎译.*?名(.*?)\<br/ism', $zoom_content,$match))
	{
		
		$alias = str_replace('　','',$match[1]);
		
		$vrow['alias'] = implode("\t",explode("/",trim($alias)));
		
	}
	if(preg_match('/◎年.*?代(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['year'] = str_replace('　','',$match[1]);
	}
	if(preg_match('/◎国.*?家(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['country'] = str_replace('　','',$match[1]);
		$vrow['country'] = explode("/", $vrow['country']);
	}
	if(preg_match('/◎类.*?别(.*?)\<br/ism', $zoom_content,$match))
	{
		$cate = str_replace('　','',$match[1]);
		$cate_arr = explode("/",trim($cate));
		if(!empty($cate_arr))
		foreach($cate_arr as $k => $v)
		{
			$vrow['cate'][] = trim($v); 
		}
	}
	if(preg_match('/◎语.*?言(.*?)\<br/ism', $zoom_content,$match))
	{
		$lang = str_replace('　','',$match[1]);
		$lang_arr = explode("/",trim($lang));
		if(!empty($lang_arr))
			foreach($lang_arr as $k => $v)
			{
				$vrow['lang'][] = trim($v);
			}
		
	}
	if(preg_match('/◎字.*?幕(.*?)\<br/ism', $zoom_content,$match))
	{
		
		$word = str_replace('　','',trim($match[1]));
		$word_arr = explode("/",trim($word));
		if(!empty($word_arr))
			foreach($word_arr as $k => $v)
			{
				$vrow['word'][] = trim($v);
			}
	}
	if(preg_match('/◎文件格式(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['fileformat'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎视频尺寸(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['videoscreen'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎文件大小(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['filesize'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎片.*?长(.*?)\<br/ism', $zoom_content,$match))
	{
		$vrow['duration'] = str_replace('　','',trim($match[1]));
	}
	if(preg_match('/◎导.*?演(.*?)\<br/ism', $zoom_content,$match))
	{
		
		$director = str_replace('　','',$match[1]);
		$director_arr = explode("/",trim($director));
		if(!empty($director_arr))
			foreach($director_arr as $k => $v)
			{
				$vrow['director'][] = trim($v);
			}
	}
	if(preg_match('/◎主.*?演(.*?)◎简.*?介/ism', $zoom_content,$match))
	{
		
		$star = str_replace('　','',$match[1]);
		$star_arr = explode("<br />",trim($star));
		if(!empty($star_arr))
			foreach($star_arr as $k => $v)
			{
				$vrow['star'][] = trim($v);
			}
		
		
	}
	$zoom_summary = preg_replace('/<a.*?<\/a>/ims', '', $zoom_content);
	if(preg_match('/◎简.*?介(.*?)\<img/ism', $zoom_summary,$match))
	{
		
		$vrow['summary'] = trim(strip_tags(str_replace("<br />", "\r\n", trim($match[1]))));
	}
	$seeds = $zoom->find("table");
	foreach($seeds as $k => $seed)
	{
		$ahref = $seed->find("a",0);
		if(!empty($ahref))
		$vrow['seed'][] = $ahref->href;
	}
	$dom->clear();
	return $vrow;
}

