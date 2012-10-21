<?php
//score ＋，tudou no+
//youku no+ tv cartoon
//api   no+ tv cartoon
//sina  no+ cartoon

ini_set ('memory_limit', '512M');
include dirname(__FILE__).'/simple_html_dom.php';

function curlByUrlSOHUAPI($url, $timeout = 10, $retry = 1,$fucksb_sohu=0)
{
	$curl = new mini_tool_curl(array("timeout"=>$timeout));

	for($i = 0; $i<=$retry ; $i++)
	{
		$data = $curl->get($url);
		if($curl->errorno == 28 || $curl->error || $curl->infocode['http_code'] != '200')
		{
			echo "<----retry $i.>\r\n";
		}
		else
		{
			break;
		}
	}

	if($curl->error || $curl->infocode['http_code'] != '200')
	{
		echo "curl get url:$url content error.".$curl->error."\r\n";
		return;
	}
	$content_type = $curl->infocode['content_type'];
	preg_match('/charset=(.*)?/', $content_type, $match);
	
	if(!empty($match[1]) && $match[1] != "UTF-8")
	{
		//$data = mb_convert_encoding($data, "UTF-8", $match[1]);
	}
	if($fucksb_sohu == 1)
	{
		$data = mb_convert_encoding($data, "UTF-8", 'gbk');
	}
	if(empty($data))
	{
		echo "curl get url:$url content is empty.\r\n";
		return ;
	}

	return $data;
}
function curlByUrl($url, $timeout = 10, $retry = 1)
{
	$curl = new mini_tool_curl(array("timeout"=>$timeout));

	for($i = 0; $i<=$retry ; $i++)
	{
		$data = $curl->get($url);
		if($curl->errorno == 28 || $curl->error || $curl->infocode['http_code'] != '200')
		{
			echo "<----retry $i.>\r\n";
		}
		else
		{
			break;
		}
	}

	if($curl->error || $curl->infocode['http_code'] != '200')
	{
		echo "curl get url:$url content error.".$curl->error."\r\n";
		return;
	}
	$content_type = $curl->infocode['content_type'];
	preg_match('/charset=(.*)?/', $content_type, $match);
	
	if(!empty($match[1]) && $match[1] != "UTF-8")
	{
		$data = mb_convert_encoding($data, "UTF-8", $match[1]);
	}
	if(empty($data))
	{
		echo "curl get url:$url content is empty.\r\n";
		return ;
	}
	
	return $data;
}
function domByCurl($url, $timeout = 10, $retry = 1)
{
	$data = curlByUrl($url,$timeout,$retry);
	if(empty($data)) 
		return ;
	$dom = new simple_html_dom();
	$dom->load($data);
	return $dom;
}
function mapTudou($key)
{
	$map['vhd360'] = '清晰';
	$map['vhd720'] = '高清';
	$map['vpbg'] = '高清';
	$map['vhd480'] = '高清';
	
	return $map[$key];
}
function mapYouku($key)
{
	$map['ico__HD'] = '清晰';
	$map['ico__SD'] = '高清';

	return $map[$key];
}

//youku.com
function coverMovieYouku($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	$isbanner = $coverRoot->find('.showbanner',0);
	if(!empty($isbanner))
		return array();
	$star = $director = $area = $cate = $year = array();

	$coverinfo = $coverRoot->find("#showInfo", 0);
	$baseinfo = $coverRoot->find(".baseinfo", 0);
	//playlink title segment
	$link = $baseinfo->find(".link", 0);
	if(empty($link)) return array();
	$vrow['playlink'] = $link->find('a',0)->href;
	$vrow['title'] = $link->find('a',0)->title;
	if(preg_match("/.*?id_([^\.]*)/im", $vrow['playlink'], $match))
	{
		$flv = json_encode(array("sid"=>$match[1]));
		$vrow['flv'] = $flv;
	}
	//pic
	$link = $baseinfo->find(".thumb", 0);
	$vrow['imagelink'] = $link->find('img',0)->src;
	
	
	//hd
	$ishddom = $baseinfo->find('.ishd',0);
	if(!empty($ishddom))
		$vdhdom = $ishddom->find('span',0);
	if(!empty($vdhdom))
	{
		$vdh = $vdhdom->class;
		$quality = mapYouku($vdh);
		$vrow['quality'] = $quality;
	}


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
	if(!empty($directord)) {
		$directorDoms = $directord->find("a");
		if(!empty($directorDoms))
			foreach($directorDoms as $k => $directorDom)
			{
				$director[] = $directorDom->plaintext;
			}
	}
	//area
	$aread = $baseinfo->find(".area", 0);
	$areaDoms = $aread->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}

	$ratingstar = $baseinfo->find(".ratingstar",0);
	
	if(!empty($ratingstar))
	{
		$scorenum = $ratingstar->find(".num",0);
		if(!empty($scorenum))
			$vrow['score'] = $scorenum->plaintext;
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
		$summary = '';
	$year =	$coverRoot->find("#title",0)->find(".base",0)->find(".title",0)->find(".pub",0)->plaintext;
	$summaryDom = $coverRoot->find("#overview",0)->find(".long",0);
	if(!empty($summaryDom))
		$overview = $coverRoot->find("#overview",0);
	if(!empty($overview))
		$short = $overview->find(".short",0);
	if(!empty($short))
		$summary = $short->plaintext;
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	if(isset($area[0]))
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	
	$coverRoot->clear();
	return $vrow;
}
function spiderMovieYouku($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	$root = $dom->find('.collgrid5w', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$items  = $root->find('.items',0);
	$rows = $items->find('ul');
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			//不要付费
			$p_ischarge = $row->find('.p_ischarge',0);
					
			if(!empty($p_ischarge)) continue;
			$coverlink = $row->find('.p_link',0)->find('a',0)->href;
			$cover = coverMovieYouku($coverlink);
			if(!empty($cover))
				$vrow[] = $cover;
		}
	$root->clear();
	return $vrow;
}
function coverTeleplayYouku($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	$isbanner = $coverRoot->find('.showbanner',0);
	if(!empty($isbanner))
		return array();
	$star = $director = $area = $cate = $year = array();
	
	$coverinfo = $coverRoot->find("#showInfo", 0);
	$baseinfo = $coverRoot->find(".baseinfo", 0);
	//playlink title segment
	$link = $baseinfo->find(".link", 0);
	if(empty($link)) return array();
	$vrow['playlink'] = $link->find('a',0)->href;
	$vrow['title'] = $link->find('a',0)->title;

	
	$followdom = $coverRoot->find('#uFollow',0);
	if(!empty($followdom))
	{
		$followuls = $followdom->find("ul");
		if(!empty($followuls))
			foreach($followuls as $k => $userdom)
			{
				$portray = $userdom->find(".portray",0);
				if(empty($portray)) continue;
				if($portray->title == '导演')
				{
					$director[] = $userdom->find(".username",0)->find("a",0)->title;
				}
			}
	}
	//pic
	$link = $baseinfo->find(".thumb", 0);
	$vrow['imagelink'] = $link->find('img',0)->src;
	
	
	
	//eplisode 
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $coverinfo->find(".basenotice", 0);
	$eplisodetxt = $eplisodeinfo->plaintext;
	if(preg_match('/共.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至.*?(\d+)/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	
	if(!empty($nowepisodes))
	{
		$http_episodes_num = $nowepisodes;
	}
	else 
	{
		$http_episodes_num = $allepisodes;
	}
	for($i = $j= 1; $i <= $http_episodes_num; $i=$i+40,$j=$j+40)
	{
		$episode_url = str_replace("show_page", "show_episode", $url).
		"?dt=json&divid=reload_$j&__rt=1&__ro=reload_$j";
		$episodeShowDom = domByCurl($episode_url);
		if(!empty($episodeShowDom))
			$episodeDoms = $episodeShowDom->find("a");
		foreach($episodeDoms as $k => $edom)
		{
			$eplisodehref = $edom->href;
			
			if(preg_match("/.*?id_([^\.]*)/im", $eplisodehref, $match))
			{
				$flv = json_encode(array("sid"=>$match[1]));
			}
			$vrow['episodes'][] = array('playlink'=>$eplisodehref,'flv'=>$flv);
		}
		$episodeShowDom->clear();
	}
	
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) && 
			!empty($vrow['allepisodes']) && 
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else 
	{
		$vrow['epsign'] = 1;
	}
	
	//hd
	$ishddom = $baseinfo->find('.ishd',0);
	if(!empty($ishddom))
		$vdhdom = $ishddom->find('span',0);
	if(!empty($vdhdom))
	{
		$vdh = $vdhdom->class;
		$quality = mapYouku($vdh);
		$vrow['quality'] = $quality;
	}
	//info segment
	$actor = $baseinfo->find(".actor", 0);
	$starDoms = $actor->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
	//area
	$aread = $baseinfo->find(".area", 0);
	$areaDoms = $aread->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}

	$ratingstar = $baseinfo->find(".ratingstar",0);

	if(!empty($ratingstar))
	{
		$scorenum = $ratingstar->find(".num",0);
		if(!empty($scorenum))
			$vrow['score'] = $scorenum->plaintext;
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
	$year =	$coverRoot->find("#title",0)->find(".base",0)->find(".title",0)->find(".pub",0)->plaintext;
	$summaryDom = $coverRoot->find("#show_info_short",0)->children(1);
	if(empty($summaryDom))
		$summaryDom = $coverRoot->find("#show_info_short",0);
	$summary = '';
	if(!empty($summaryDom))
		$summary = $summaryDom->plaintext;
	$vrow['star'] = $star;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['director'] = $director;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function spiderTeleplayYouku($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	if(empty($dom)) return array();
	$root = $dom->find('.collgrid5w', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$items  = $root->find('.items',0);
	$rows = $items->find('ul');
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			$coverlink = $row->find('.p_link',0)->find('a',0)->href;
			$cover = coverTeleplayYouku($coverlink);
			if(!empty($cover))
				$vrow[] = $cover;
		}
		$root->clear();
		return $vrow;
}
function coverCartoonYouku($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	$isbanner = $coverRoot->find('.showbanner',0);
	if(!empty($isbanner))
		return array();
	$star = $director = $area = $cate = $year = array();

	$coverinfo = $coverRoot->find("#showInfo", 0);
	$baseinfo = $coverRoot->find(".baseinfo", 0);
	//playlink title segment
	$link = $baseinfo->find(".link", 0);
	if(empty($link)) return array();
	$vrow['playlink'] = $link->find('a',0)->href;
	$vrow['title'] = $link->find('a',0)->title;


	//pic
	$link = $baseinfo->find(".thumb", 0);
	$vrow['imagelink'] = $link->find('img',0)->src;



	//eplisode
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $coverinfo->find(".basenotice", 0);
	$eplisodetxt = $eplisodeinfo->plaintext;
	if(preg_match('/共.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至.*?(\d+)/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;

	if(!empty($nowepisodes))
	{
		$http_episodes_num = $nowepisodes;
	}
	else
	{
		$http_episodes_num = $allepisodes;
	}
	for($i = $j= 1; $i <= $http_episodes_num; $i=$i+20,$j=$j+20)
	{
		$episode_url = str_replace("show_page", "show_episode", $url).
		"?dt=json&divid=reload_$j&__rt=1&__ro=reload_$j";
		$episodeShowDom = domByCurl($episode_url);
		if(!empty($episodeShowDom))
			$episodeDoms = $episodeShowDom->find("a");
		foreach($episodeDoms as $k => $edom)
		{
			$eplisodehref = $edom->href;
				
			if(preg_match("/.*?id_([^\.]*)/im", $eplisodehref, $match))
			{
				$flv = json_encode(array("sid"=>$match[1]));
			}
			$vrow['episodes'][] = array('playlink'=>$eplisodehref,'flv'=>$flv);
		}
		$episodeShowDom->clear();
	}
	if(!isset($vrow['episodes']) || empty($vrow['episodes'])) return array();
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	//hd
	$ishddom = $baseinfo->find('.ishd',0);
	if(!empty($ishddom))
		$vdhdom = $ishddom->find('span',0);
	if(!empty($vdhdom))
	{
		$vdh = $vdhdom->class;
		$quality = mapYouku($vdh);
		$vrow['quality'] = $quality;
	}
	//area
	$aread = $baseinfo->find(".area", 0);
	$areaDoms = $aread->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}

	$ratingstar = $baseinfo->find(".ratingstar",0);

	if(!empty($ratingstar))
	{
		$scorenum = $ratingstar->find(".num",0);
		if(!empty($scorenum))
			$vrow['score'] = $scorenum->plaintext;
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
	$year =	$coverRoot->find("#title",0)->find(".base",0)->find(".title",0)->find(".pub",0)->plaintext;
	$summaryDom = $coverRoot->find("#show_info_short",0)->children(1);
	if(empty($summaryDom))
		$summaryDom = $coverRoot->find("#show_info_short",0);
	$summary = '';
	if(!empty($summaryDom))
		$summary = $summaryDom->plaintext;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function spiderCartoonYouku($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	if(empty($dom)) return array();
	$root = $dom->find('.collgrid5w', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$items  = $root->find('.items',0);
	$rows = $items->find('ul');
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			$coverlink = $row->find('.p_link',0)->find('a',0)->href;
			$cover = coverCartoonYouku($coverlink);
			if(!empty($cover))
				$vrow[] = $cover;
		}
		$root->clear();
		return $vrow;
}

//tudou.com
function coverMovieTudou($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	//$coverRoot = domByCurl($url);
	
	
	$data = curlByUrl($url);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);
	$flv = array();
	if(preg_match("/\<script\>.*?iid:\s*\'(\d+)',/ism", $data, $match))
	{
		$flv["iid"]=$match[1];
	}
	if(preg_match('/http:\/\/.*?\/albumcover\/(.*?)\.html/is', $url, $match))
	{
		$flv["sid"]=$match[1];
	}
	if(!empty($flv))
	$vrow['flv'] = json_encode($flv);
	
	
	if(empty($coverRoot)) return array();
	$star = $director = $area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".album-info", 0);
	$coverPic = $coverinfo->find(".pic", 0);
	$vrow['playlink'] = $coverPic->find('a',0)->href;
	$vrow['title'] = $coverPic->find('a',0)->title;
	$vrow['imagelink'] = $coverPic->find('img',0)->src;
	$vdhdom = $coverPic->find('span',0);
	if(!empty($vdhdom))
	{
		$vdh = $vdhdom->class;
		$quality = mapTudou($vdh);
		$vrow['quality'] = $quality;
	}


	//info segment
	$coverTxt = $coverinfo->find(".album-txt", 0);
	$coverTxtlist = $coverTxt->find(".album-txt-list", 0);
	$starDoms = $coverTxtlist->children(0)->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->title;
		}

	//director
	$directorDoms = $coverTxtlist->children(1)->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->title;
		}

	//area
	$areaDoms = $coverTxtlist->children(2)->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
	//cate
	$cateDoms = $coverTxtlist->children(3)->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
	//year
	$yearDom = $coverTxtlist->children(4)->find("a",0);
	$year = $yearDom->plaintext;

	$summaryDoms = $coverTxtlist->children(5)->find("span#albumIntroStr", 0);
	$summary = $summaryDoms->plaintext;
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function coverTeleplayTudou($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	//get iid 
	if(preg_match('/http:\/\/.*?\/albumcover\/(.*?)\.html/is', $url, $match))
	{
		$sid = $match[1];
	}
	$playurl = str_replace('albumcover', 'albumplay', $url);
	

	//eplisode 
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $coverRoot->find(".album-msg", 0);
	$eplisodetxt = $eplisodeinfo->plaintext;
	if(preg_match('/共.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至第.*?(\d+)集/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	$eplisodelist = $coverRoot->find("#playItems", 0);
	if(!empty($eplisodelist))
	{
		$eplisoderow = $eplisodelist->find(".row");
		if(!empty($eplisoderow))
			foreach($eplisoderow as $k => $row)
			{
				$line = $row->children();
				if(!empty($line))
				foreach($line as $l => $block)
				{
					
					$pic = $block->find(".pic",0);
					$eplisodehref = $pic->find("a",0)->href;
					$eplisodehref = trim($eplisodehref);
					$eplisodesrc = $pic->find("img",0)->src;
					$eplisodedata = curlByUrl($eplisodehref);
					$iid= $flv = '';
					if(preg_match("/itemData={.*?iid:.*?(\d+).*?};/ism", $eplisodedata, $match))
					{
						$iid = $match[1];
						$flv = json_encode(array("sid"=>$sid, "iid"=>$iid));
					}
					$vrow['episodes'][] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodesrc,'flv'=>$flv);
				}
			}
	}

	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	$star = $director = $area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".album-info", 0);
	$coverPic = $coverinfo->find(".pic", 0);
	$vrow['playlink'] = $coverPic->find('a',0)->href;
	$vrow['title'] = $coverPic->find('a',0)->title;
	$vrow['imagelink'] = $coverPic->find('img',0)->src;
	//info segment
	$coverTxt = $coverinfo->find(".album-txt", 0);
	$coverTxtlist = $coverTxt->find(".album-txt-list", 0);
	$starDoms = $coverTxtlist->children(0)->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->title;
		}
	//director
	$directorDoms = $coverTxtlist->children(1)->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->title;
		}
	//area
	$areaDoms = $coverTxtlist->children(2)->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
	//cate
	$cateDoms = $coverTxtlist->children(3)->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
	//year
	$yearDom = $coverTxtlist->children(4)->find("a",0);
	$year = $yearDom->plaintext;
	$summaryDoms = $coverTxtlist->children(5)->find("span#albumIntroStr", 0);
	$summary = $summaryDoms->plaintext;
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function coverCartoonTudou($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);

	
	//get iid
	if(preg_match('/http:\/\/.*?\/albumcover\/(.*?)\.html/is', $url, $match))
	{
		$sid = $match[1];
	}
	$playurl = str_replace('albumcover', 'albumplay', $url);
	
	
	
	//eplisode
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $coverRoot->find(".album-msg", 0);
	$eplisodetxt = $eplisodeinfo->plaintext;
	if(preg_match('/共.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至第.*?(\d+)集/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	$eplisodelist = $coverRoot->find("#playItems", 0);
	if(!empty($eplisodelist))
	{
		
		$eplisoderow = $eplisodelist->find(".row");
		if(!empty($eplisoderow))
			foreach($eplisoderow as $k => $row)
			{
				$line = $row->children();
				if(!empty($line))
					foreach($line as $l => $block)
					{
						$pic = $block->find(".pic",0);
						$eplisodehref = $pic->find("a",0)->href;
						$eplisodehref = trim($eplisodehref);
						$eplisodesrc = $pic->find("img",0)->src;
						$eplisodedata = curlByUrl($eplisodehref);
						$iid= $flv = '';
						if(preg_match("/itemData={.*?iid:.*?(\d+).*?};/ism", $eplisodedata, $match))
						{
							$iid = $match[1];
							$flv = json_encode(array("sid"=>$sid, "iid"=>$iid));
						}
						$vrow['episodes'][] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodesrc,'flv'=>$flv);
					}
			}
	}
	//土豆动漫特殊
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 1;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	$star = $director = $area = $cate = $year = array();
	$coverinfo = $coverRoot->find(".album-info", 0);
	$coverPic = $coverinfo->find(".pic", 0);
	$vrow['playlink'] = $coverPic->find('a',0)->href;
	$vrow['title'] = $coverPic->find('a',0)->title;
	$vrow['imagelink'] = $coverPic->find('img',0)->src;
	
	//info segment
	$coverTxt = $coverinfo->find(".album-txt", 0);
	$coverTxtlist = $coverTxt->find(".album-txt-list", 0);
	$starDoms = $coverTxtlist->children(0)->find("a");
			//area
			$areaDoms = $coverTxtlist->children(0)->find("a");
			if(!empty($areaDoms))
				foreach($areaDoms as $k => $areaDom)
				{
					$area[] = $areaDom->plaintext;
				}
				//cate
				$cateDoms = $coverTxtlist->children(1)->find("a");
				if(!empty($cateDoms))
					foreach($cateDoms as $k => $cateDom)
					{
						$cate[] = $cateDom->plaintext;
					}
					
					//year
					$yearDom = $coverTxtlist->children(2)->find("a",0);
					$year = $yearDom->plaintext;
					$summaryDoms = $coverTxtlist->children(3)->find("span#albumIntroStr", 0);
					$summary = $summaryDoms->plaintext;
					$vrow['area'] = $area[0];
					$vrow['cate'] = $cate;
					$vrow['year'] = $year;
					$vrow['summary'] = $summary;
					$coverRoot->clear();
					return $vrow;
}
function spiderMovieTudou($url)
{
	$vrow = array();
	$dom = domByCurl($url);

	$root = $dom->find('.showcase', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->children();
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			$line = $row->children();

			if(!empty($line))
				foreach($line as $l => $block)
				{
					$picblock =  $block->children(0);
					$coverlink= $picblock->find('a',0)->href;
					$cover = coverMovieTudou($coverlink);
					if(!empty($cover))
					{
						$vrow[] = $cover;
					}
				}
		}
	$root->clear();
	return $vrow;
}
function spiderCartoonTudou($url)
{
	$vrow = array();
	$dom = domByCurl($url);

	$root = $dom->find('.showcase', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->children();
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			$line = $row->children();

			if(!empty($line))
				foreach($line as $l => $block)
				{
					$picblock =  $block->children(0);
					$coverlink= $picblock->find('a',0)->href;
						
					$cover = coverCartoonTudou($coverlink);
					if(!empty($cover))
					{
						$vrow[] = $cover;
					}
				}
		}
		$root->clear();
		return $vrow;
}
function spiderTeleplayTudou($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	
	$root = $dom->find('.showcase', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->children();
	if(!empty($rows))
	foreach($rows as $k => $row)
	{
		$line = $row->children();
	
		if(!empty($line))
		foreach($line as $l => $block)
		{
			$picblock =  $block->children(0);
			$coverlink= $picblock->find('a',0)->href;
			
			$cover = coverTeleplayTudou($coverlink);
			if(!empty($cover))
			{
				$vrow[] = $cover;
			}
			unset($cover);
		}
	}
	$root->clear();
	return $vrow;	
}


//sina.com
function spiderMovieSina($url)
{
	$jsondata = curlByUrl($url);
	if(empty($jsondata)) return array();
	$data = json_decode($jsondata,true);
	$domain = "http://video.sina.com.cn";
	if($data['result'] =='A00006')
		foreach($data['data'] as $k => $row)
		{

			$vrow['playlink'] = $domain.$row['url'];
			//查找vid
			
			$playlinkdata = curlByUrl($vrow['playlink']);
			
			if(preg_match("/mInfo:.*?vid:\'(.*?)\',/ism", $playlinkdata, $match))
			{
				$vid = $match[1];
				$vid_arr = explode("|", $vid);
				$flv = json_encode(array("vid"=>trim($vid_arr[0])));
				$vrow['flv'] = $flv;
			}
			
			
			$vrow['infolink'] = $domain.$row['url'];
			$vrow['imagelink'] = $row['thumb'];
			$vrow['title'] = $row['name'];
			$pattern = "/<a[^>]*?>(.*?)<\/a>/i";
			preg_match_all($pattern, $row['artist'], $star);
			preg_match_all($pattern, $row['type'], $cate);
			preg_match_all($pattern, $row['director'], $director);
			preg_match_all($pattern, $row['area'], $area);
			preg_match_all($pattern, $row['pyear'], $pyear);
			if(!empty($pyear))
				$year = str_replace("年", "", $pyear[1][0]);
			$vrow['star'] = $star[1];
			$vrow['director'] = $director[1];
			$vrow['area'] = $area[1][0];
			$vrow['cate'] = $cate[1];
			$vrow['year'] = $year;
			$vrow['score'] = $row['score'];
			$vrow['summary'] = $row['desc'];
			$vrow['duration'] = $row['video_length'];
			$vvrow[] = $vrow;
		}
	return $vvrow;
}
function coverTeleplaySina($url)
{
	$vrow = array();
	$domain = "http://video.sina.com.cn";
	$coverRoot = domByCurl($url,30);
	if(empty($coverRoot)) return array();
	$eplisodelist = $coverRoot->find(".list_demand", 0);
	if(!empty($eplisodelist))
	{
		$eplisoderow = $eplisodelist->find("li");
		if(!empty($eplisoderow))
			foreach($eplisoderow as $k => $block)
			{
					$pic = $block->find(".pic",0);
					$href = $pic->find("a",0);
					if(!empty($href))
					{
						$eplisodehref = $domain.$href->href;
						$eplisodesrc = $pic->find("img",0)->src;
						
						$playlinkdata = curlByUrl($eplisodehref);
							
						if(preg_match("/mInfo:.*?vid:\'(.*?)\',/ism", $playlinkdata, $match))
						{
							$vid = $match[1];
							$vid_arr = explode("|", $vid);
							$flv = json_encode(array("vid"=>trim($vid_arr[0])));
						}
						$vrow[] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodesrc,'flv'=>$flv);
					}
				
					
			}
	}
	return $vrow;
}
function spiderTeleplaySina($url)
{
	$jsondata = curlByUrl($url,20);
	if(empty($jsondata)) return array();
	$data = json_decode($jsondata,true);
	$domain = "http://video.sina.com.cn";
	if($data['result'] =='A00006')
		foreach($data['data'] as $k => $row)
		{
			

			$vrow['playlink'] = $domain.$row['url'];
			$vrow['infolink'] = $domain.$row['detail'];
			$vrow['imagelink'] = str_replace("12090","120160",$row['thumb_12090']);
			$vrow['title'] = $row['name'];
			$pattern = "/<a[^>]*?>(.*?)<\/a>/i";
			preg_match_all($pattern, $row['artist'], $star);
			preg_match_all($pattern, $row['type'], $cate);
			preg_match_all($pattern, $row['director'], $director);
			preg_match_all($pattern, $row['area'], $area);
			preg_match_all($pattern, $row['pyear'], $pyear);
			if(!empty($pyear))
				$year = str_replace("年", "", $pyear[1][0]);
			$vrow['star'] = $star[1];
			$vrow['director'] = $director[1];
			$vrow['area'] = $area[1][0];
			$vrow['cate'] = $cate[1];
			$vrow['year'] = $year;
			$vrow['score'] = $row['score'];
			$vrow['summary'] = strip_tags($row['desc']);
			
			
			if(preg_match('/(\d+)集全/', $row['video_count'],$match))
			{
				$nowepisodes = $match[1];
			}
			if(preg_match('/更新至.*?(\d+)集/', $row['video_count'],$match))
			{
				$nowepisodes = $match[1];
			}
			$vrow['allepisodes'] =  $row['total_video'];
			$vrow['nowepisodes'] = $nowepisodes;
			$crow = coverTeleplaySina($vrow['infolink']);
			if(!empty($crow))
			{
				$vrow['episodes'] = $crow;
				
				if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
						!empty($vrow['allepisodes']) &&
						!empty($vrow['nowepisodes']))
				{
					$vrow['epsign'] = 2;
				}
				else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
				{
					$vrow['nowepisodes'] = count($vrow['episodes']);
					$vrow['epsign'] = 2;
				}
				else if(empty($vrow['nowepisodes']))
				{
					$vrow['nowepisodes'] = count($vrow['episodes']);
					$vrow['epsign'] = 1;
				}
				else
				{
					$vrow['epsign'] = 1;
				}
				
				
			}
			$vvrow[] = $vrow;
		}
		return $vvrow;
}

//letv.com
function coverMovieLetv($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	//$coverRoot = domByCurl($url);
	
	
	$data = curlByUrl($url);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);
	$flv = array();
	if(preg_match("/__INFO__.*?video.*?{.*?vid:(\d+),/ism", $data, $match))
	{
		$flv["vid"]=$match[1];
	}
	if(!empty($flv))
		$vrow['flv'] = json_encode($flv);
	
	
	if(empty($coverRoot)) return array();
	$star = $director = $area = $cate = $year = array();
	
	//pic segment
	$h1 = $coverRoot->find('h1',0);
	if(empty($h1)) return array();
	$title = $h1->plaintext;
	$titlearr = explode(" ", $title);
	$vrow['title'] = $titlearr[0];
	
	$coverinfo = $coverRoot->find(".T-Info", 0);
	$vrow['playlink'] = $url;
	
	//info segment
	$coverTxt = $coverinfo->find(".text", 0);
	
	//验证是否页面正确
	$startdom = $coverRoot->find("#actor_info",0);
	if(empty($startdom)) return array();
	
	$starDoms = $coverRoot->find("#actor_info",0)->find('a');
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->title;
		}
	
		//director
	$directorDoms = $coverRoot->find("#director_info",0)->find('a');
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->title;
		}
	
	//area
	//检测页面
	$li04 = $coverTxt->find(".Li04",0);
	if(empty($li04)) return array();
	$p2 = $li04->find('.p2',0);
	if(empty($p2)) return array();
		
		
	$areaDoms = $p2->find('.s2',0)->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
	//cate
	$cateDoms =  $coverRoot->find("#cate_info",0)->find('a');
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
	//year
	$yearDom = $coverTxt->find(".Li04",0)->find('.p1',0)->find('.s2',0)->find("a",0);
	if(!empty($yearDom))
	{
		$year = $yearDom->plaintext;
	}
// 	$scoreDom = $coverRoot->find(".number",0)->find("em",0);
// 	if(!empty($scoreDom)) 
// 		$vrow['score'] = $scoreDom->plaintext;
	
	
	$summaryDoms = $coverTxt->find("#j-descript",0);
	$summary = $summaryDoms->plaintext;
					
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function spiderMovieLetv($url)
{
	$vrow = array();
	$dom = domByCurl($url);

	$root = $dom->find('#tabx_c_1', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find("dl.m_dl");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
			
			$picblock =  $block->children(0);
			$coverlink= $picblock->find('a',0)->href;
			$cover = coverMovieLetv($coverlink);
			if(!empty($cover))
			{
				$cover['imagelink'] = $picblock->find('img',0)->src;
				$vrow[] = $cover;
			}
		}
	$root->clear();
	return $vrow;
}
function coverTeleplayLetv($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	
	//eplisode
	$allepisodes = $nowepisodes = 0;
	$info = $coverRoot->find(".info", 0);
	if(empty($info)) return array();
	
	$eplisodeinfo = $info->find(".i-t",0);
	$eplisodetxt = trim($eplisodeinfo->plaintext);
	if(preg_match('/共.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至(\d+)集/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	

	$eplisodelist = $coverRoot->find(".listTab", 0);
	if(empty($eplisodelist))
		return array();
	
	$eplisodelist = $eplisodelist->find(".listPic",0);
	if(!empty($eplisodelist))
	{
		$eplisoderow = $eplisodelist->find(".w120");
	
		if(!empty($eplisoderow))
			foreach($eplisoderow as $k => $block)
			{
				
				
					$pic = $block->find("dt",0);
					$eplisodehref = $pic->find("a",0)->href;
					$eplisodesrc = $pic->find("img",0)->src;
					$data = curlByUrl($eplisodehref);
					$flv = '';
					if(!empty($data))
					{
						if(preg_match("/__INFO__.*?video.*?{.*?vid:(\d+),/ism", $data, $match))
						{
							$flv_arr["vid"]=$match[1];
							$flv = json_encode($flv_arr);
						}
					}
					
					$vrow['episodes'][] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodesrc,'flv'=>$flv);
			}
	}
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	
	
	$star = $director = $area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".intro", 0);
	
	$vrow['playlink'] = $url;
	$vrow['title'] = $coverinfo->find('.textInfo',0)->find("dt",0)->plaintext;
	//info segment
	$coverTxt = $coverinfo->find('.textInfo',0)->find("dd",0);
	$starDoms = $coverTxt->find('.p2',0)->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
	//director
	$directorDoms = $coverTxt->find('.p1',0)->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
	//area
	$areaDoms =  $coverTxt->find('.p3',0)->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
	//cate
	$cateDoms = $coverTxt->find('.p5',0)->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
	//year
	$yearDom = $coverTxt->find('.p4',0)->find("a",0);
	$yearDom = $coverTxt->find('.p4',0)->find("a",0);
	if(preg_match('/(\d{4})/',  $yearDom->plaintext,$match))
	{
		$year = $match[1];
	}
	$summaryDoms =  $coverTxt->find('.p7',0);
	$summary = $summaryDoms->plaintext;
	//score
	
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function spiderTeleplayLetv($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	if(empty($dom)) return array();
	$root = $dom->find('#tabx_c_1', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find("dl.m_dl");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
				
			$picblock =  $block->children(0);
			$coverlink= $picblock->find('a',0)->href;
			$cover = coverTeleplayLetv($coverlink);
			if(!empty($cover))
			{
				$cover['imagelink'] = $picblock->find('img',0)->src;
				$scoreDom = $block->find(".sorce",0);
				if(!empty($scoreDom))
					$vrow['score'] = $scoreDom->plaintext;
				$vrow[] = $cover;
			}
		}
		$root->clear();
		return $vrow;
}
function coverCartoonLetv($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	//eplisode
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $coverRoot->find(".info", 0)->find(".i-t",0);
	$eplisodetxt = trim($eplisodeinfo->plaintext);
	if(preg_match('/共.*?(\d+).*?集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至.*?(\d+).*?集/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	$eplisodelist = $coverRoot->find(".listTab", 0);
	if(empty($eplisodelist))
		return array();
	
	$eplisodelist = $eplisodelist->find(".listPic",0);
	if(!empty($eplisodelist))
	{
		$eplisoderow = $eplisodelist->find(".w120");
		if(!empty($eplisoderow))
			foreach($eplisoderow as $k => $block)
			{
				$pic = $block->find("dt",0);
				$eplisodehref = $pic->find("a",0)->href;
				$eplisodesrc = $pic->find("img",0)->src;
				$data = curlByUrl($eplisodehref);
				$flv = '';
				if(!empty($data))
				{
					if(preg_match("/__INFO__.*?video.*?{.*?vid:(\d+),/ism", $data, $match))
					{
						$flv_arr["vid"]=$match[1];
						$flv = json_encode($flv_arr);
					}
				}
					
				$vrow['episodes'][] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodesrc,'flv'=>$flv);
			}
	}
	
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	
	$area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".intro", 0);

	$vrow['playlink'] = $url;
	$vrow['title'] = $coverinfo->find('.textInfo',0)->find("dt",0)->plaintext;
	//info segment
	$coverTxt = $coverinfo->find('.textInfo',0)->find("dd",0);
	//area
	$areaDoms =  $coverTxt->find('.p3',0)->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
		//cate
	$cateDoms = $coverTxt->find('.p5',0)->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
			//year
	$yearDom = $coverTxt->find('.p4',0)->find("a",0);
	if(preg_match('/(\d{4})/',  $yearDom->plaintext,$match))
	{
		$year = $match[1];
	}
	$summaryDoms =  $coverTxt->find('.p7',0);
	$summary = $summaryDoms->plaintext;
	//score

	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function spiderCartoonLetv($url)
{
	$vrow = array();
	$dom = domByCurl($url);

	$root = $dom->find('#tabx_c_1', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find("dl.m_dl");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{

			$picblock =  $block->children(0);
			$coverlink= $picblock->find('a',0)->href;
			$cover = coverCartoonLetv($coverlink);
			if(!empty($cover))
			{
				$cover['imagelink'] = $picblock->find('img',0)->src;
				$scoreDom = $block->find(".sorce",0);
				if(!empty($scoreDom))
					$vrow['score'] = $scoreDom->plaintext;
				$vrow[] = $cover;
			}
		}
		$root->clear();
		return $vrow;
}

//sohu.com
function coverMovieSohu($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	//$coverRoot = domByCurl($url);
	
	
	$data = curlByUrlSOHUAPI($url,30,1,1);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);
	
	
	
	if(empty($coverRoot)) return array();
	$star = $director = $area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".yzp", 0);
	if(empty($coverinfo)) return array();
	$coverPic = $coverinfo->find(".pic", 0);
	$vrow['playlink'] = $coverPic->href;
	$vrow['title'] = $coverPic->find('img',0)->alt;
	$vrow['imagelink'] = trim($coverPic->find('img',0)->src);

	
	
	$dataplay = curlByUrlSOHUAPI($vrow['playlink'],30,1,1);
	if(empty($dataplay))
		return array();
	$playcoverRoot = new simple_html_dom();
	$playcoverRoot->load($dataplay);
	
	if(preg_match('/var\s*?vid="(\d+)";/', $dataplay, $match))
	{
		$flv = json_encode(array("vid"=>$match[1]));
		$vrow['flv'] = $flv;
	}
	
	//info segment
	$coverTxt = $playcoverRoot->find(".vInfo", 0);
	if(preg_match('/var\s*?VRS_DIRECTOR="(.*?)";/ims', $dataplay, $match))
	{
		$director_arr = explode(':', $match[1]);
		if(!empty($director_arr))
			foreach($director_arr as $k => $v)
		{
			if(!empty($v)) $director[] = $v;
		}
	}
	if(preg_match('/var\s*?VRS_ACTOR="(.*?)";/ims', $dataplay, $match))
	{
		$star_arr = explode(':', $match[1]);
		if(!empty($star_arr))
			foreach($star_arr as $k => $v)
			{
				if(!empty($v)) $star[] = $v;
			}
	}
	if(preg_match('/var\s*?VRS_CATEGORY="(.*?)";/ims', $dataplay, $match))
	{
		$cate_arr = explode(':', $match[1]);
		if(!empty($cate_arr))
			foreach($cate_arr as $k => $v)
			{
				if(!empty($v)) $cate[] = str_replace('片','',(string)$v);
			}
	}
	if(preg_match('/var\s*?VRS_AREA="(.*?)";/ims', $dataplay, $match))
	{
		$area_arr = explode(':', $match[1]);
		if(!empty($area_arr))
			foreach($area_arr as $k => $v)
			{
				if(!empty($v)) $area[] = $v;
			}
	}
	if(preg_match('/var\s*?VRS_PLAY_YEAR="(.*?)";/ims', $dataplay, $match))
	{
		$year = $match[1];
	}
	$summaryDoms = $playcoverRoot->find("#introID", 0);
	$summary = '';
	if(!empty($summaryDoms))
		$summary = str_replace('展开全部', '', (string)$summaryDoms->plaintext);
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$playcoverRoot->clear();
	$coverRoot->clear();
	return $vrow;
}
function spiderMovieSohu($url)
{
	$vrow = array();
	$data = curlByUrlSOHUAPI($url,30,1,1);
	if(empty($data))
		return array();
	$dom = new simple_html_dom();
	$dom->load($data);
	$root = $dom->find('#videoData', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find(".vData");
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			$line = $row->find(".vInfo");

			if(!empty($line))
				foreach($line as $l => $block)
				{
					$picblock =  $block->find(".vPic",0);
					$coverlink= $picblock->find('a',0)->href;
					$cover = coverMovieSohu($coverlink);
					if(empty($cover)) continue;
					$popLay = $picblock->find(".popLay",0);
					if(!empty($popLay))
					{
						$score = $popLay->find("strong",0)->plaintext;
						$cover['score'] = str_replace("分", '', $score);
					}
					if(!empty($cover))
					{
						$vrow[] = $cover;
					}
				}
		}
		$root->clear();
		return $vrow;
}
function coverTeleplaySohu($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$vrow['playlink'] = $url;
	$data = curlByUrlSOHUAPI($url,30,1,1);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);
	
	if(empty($coverRoot)) return array();
	$star = $director = $area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".blockRA", 0);
	if(empty($coverinfo)) return array();
	$vrow['title'] = $coverinfo->find('h2',0)->find('span',0)->plaintext;
	
	
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $coverRoot->find(".blockLA", 0);
	$allist =  $eplisodeinfo->find("#allist",0);
	if(empty($allist)) return array();
	$tab1cont  = $allist->find(".d1",0)->children(0);
	$eplisodetxt = $tab1cont->plaintext;
	if(preg_match('/共.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至.*?(\d+)/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	
	$list_asc = $eplisodeinfo->find("#list_asc",0);
	if(empty($list_asc)) return array();
	$eplisoderow = $list_asc->find(".similarLists",0)->find("li");
	
	if(!empty($eplisoderow))
		foreach($eplisoderow as $k => $block)
		{
			$eplisodehref = $block->find("a",0)->href;
			$eplisodesrc = $block->find("img",0)->src;
			$flv = '';
			$vid = $block->find("em",0)->rel;
			if(!empty($vid))
			{
				$flv_arr["vid"]= $vid;
				$flv = json_encode($flv_arr);
			}
				
			$vrow['episodes'][] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodesrc,'flv'=>$flv);
		}
		
		
		
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
		
	$star = $director = $area = $cate = $year = array();
	$coverTxt = $coverinfo->find(".cont",0);
	if(empty($coverTxt)) return array();
	
	$starDoms = $coverTxt->children(1)->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
	//director
	$directorDoms = $coverTxt->children(0)->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
			
	//cate
	$cateDoms = $coverTxt->children(6)->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = str_replace('剧','',$cateDom->plaintext);
		}
	//year
	$yearDom = $coverTxt->children(5)->find("a",0);
	if(preg_match('/(\d{4})/',  $yearDom->plaintext,$match))
	{
		$year = $match[1];
	}
	$summaryDoms =  $coverTxt->children(3);
	if(!empty($summaryDoms)) 
	{
		$summary = str_replace("全部剧情&gt;&gt;","",$summaryDoms->plaintext);
	}
	
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
	
}
//sohu 电视剧要以	地区抓取
function spiderTeleplaySohu($url)
{
	$vrow = array();
	
	$sohuAREA = array('u5185'=> '大陆',
			'u6e2f'=> '香港',
			'u53f0'=> '台湾',
			'u97e9'=> '韩国',
			'u7f8e'=> '美国',
			'u82f1'=> '欧洲',
			'u6cf0'=> '亚洲',
			'u65e5'=> '日本',
			'u5176'=> '其他');
	foreach($sohuAREA as $k => $v)
	{
		if(substr_count($url, $k) >=1)
		{
			$sohu_area = $v;
			break;
		}
	}
	
	$data = curlByUrlSOHUAPI($url,30,1,1);
	if(empty($data))
		return array();
	$dom = new simple_html_dom();
	$dom->load($data);
	$root = $dom->find('#videoData', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find(".vData");
	if(!empty($rows))
		foreach($rows as $k => $row)
		{
			$line = $row->find(".vInfo");
			if(!empty($line))
			foreach($line as $l => $block)
			{
				$picblock =  $block->find(".vPic",0);
				$coverlink= $picblock->find('a',0)->href;
				$cover = coverTeleplaySohu($coverlink);
				if(empty($cover)) continue;
				$cover['imagelink'] = $picblock->find("img",0)->src;
				
				$popLay = $picblock->find(".popLay",0);
				if(!empty($popLay))
				{
					$score = $popLay->find("strong",0)->plaintext;
					$cover['score'] = str_replace("分", '', $score);
				}
				if(!empty($sohu_area))
				{
					$cover['area'] = $sohu_area;
				}
				if(!empty($cover))
				{
					$vrow[] = $cover;
				}
			}
		}
		$root->clear();
		return $vrow;
}


//qq.com
function coverTeleplayQq($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	//$coverRoot = domByCurl($url);
	
	$data = curlByUrl($url);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);

	
	
	if(empty($coverRoot)) return array();
	$star = $director = $area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".mod_info", 0);
	if(empty($coverinfo)) return array();
	$coverinfo = $coverinfo->find(".mod_cont",0);
	$vrow['playlink'] = $url;
	$vrow['title'] = $coverinfo->find('#em_cover_title',0)->plaintext;
	
	$scoredom = $coverinfo->find("#detail_list_score",0);
	
	if(!empty($scoredom))
	{
		$vrow['score'] = $scoredom->plaintext;
	}
	//info segment
	$coverTxt = $coverinfo->find(".details_list", 0);
	
	
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $coverTxt->children(5);
	if(empty($eplisodeinfo)) return array(); //@@@@
	$eplisodetxt = $eplisodeinfo->plaintext;
	if(preg_match('/全.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至.*?(\d+)/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	
	$videolist = $coverRoot->find("#mod_videolist",0)->find("li");
	
	$qqdomain = "http://v.qq.com";
	if(!empty($videolist))
		foreach($videolist as $k => $ep)
		{
			 $eplisode = $ep->find("a",0);
			 $eplisodehref = $qqdomain.$eplisode->href;
			 $vid = $eplisode->id;
			 $flv = '';
			 if(!empty($vid))
			 {
		 		 $flv_arr["vid"]=$vid;
		 		 $flv = json_encode($flv_arr);
			 }
			 	
			 $vrow['episodes'][] = array('playlink'=>$eplisodehref,'flv'=>$flv);
		}
	
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	
	$onedoms = $coverTxt->children(0);
	$plaintext = $onedoms->plaintext;
	if(substr_count($plaintext, '导演') < 1)
	{
		return array();
	}
	
	//director
	$directorDoms = $coverTxt->children(0)->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
	
	$starDoms = $coverTxt->children(1)->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
	
	
	
	
	//area
	$areaDoms = $coverTxt->children(2)->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
	//cate
	$cateDoms = $coverTxt->children(4)->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
	//year
	$yearDom = $coverTxt->children(3)->find("a",0);
	if(!empty($yearDom))
		$year = $yearDom->plaintext;
	if($year =='其他') 
	{
		$year = '';
	}
	$mod_desc = $coverinfo->find('#mod_desc',0);
	$summary = ''; 
	if(!empty($mod_desc))
	{
		$summaryDoms = $mod_desc->find("p.mod_cont", 0);
		$summary = $summaryDoms->plaintext;
	}
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function spiderTeleplayQq($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	
	$root = $dom->find('#content', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find(".mod_list_pic_130",0)->find("li");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
		
			$picblock =  $block->find('a',0);
			$coverlink= $picblock->href;
			$cover = coverTeleplayQq($coverlink);
			if(empty($cover)) continue;
			$imagedom = $picblock->find("img",0);
			if(!empty($imagedom))
			{
				$cover['imagelink'] = $imagedom->src;
			}
			if(!empty($cover))
			{
				$vrow[] = $cover;
			}
		}
	$root->clear();
	return $vrow;
}
function coverMovieQq($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	//$coverRoot = domByCurl($url);
	
	
	$data = curlByUrl($url);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);
	$flv = array();
	if(preg_match('/VIDEO_INFO={.*?vid:"(.*?)",/ism', $data, $match))
	{
		$flv["vid"]=$match[1];
	}
	if(!empty($flv))
		$vrow['flv'] = json_encode($flv);
	
	
	if(empty($coverRoot)) return array();
	$star = $director = $area = $cate = $year = array();
	//pic segment
	$coverinfo = $coverRoot->find(".mod_info", 0)->find(".mod_cont",0);
	if(empty($coverinfo)) return array();
	$vrow['playlink'] = $url;
	$vrow['title'] = $coverinfo->find('#em_cover_title',0)->plaintext;
	
	$scoredom = $coverinfo->find("#detail_list_score",0);
	
	if(!empty($scoredom))
	{
		$vrow['score'] = $scoredom->plaintext;
	}
	//info segment
	$coverTxt = $coverinfo->find(".details_list", 0);
	
	$onedoms = $coverTxt->children(0);
	$plaintext = $onedoms->plaintext;
	if(substr_count($plaintext, '导演') < 1)
	{
		return array();
	}
	
	//director
	$directorDoms = $coverTxt->children(0)->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
	
	$starDoms = $coverTxt->children(1)->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
	

	
	//area
	$areaDoms = $coverTxt->children(2)->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
	//cate
	$cateDoms = $coverTxt->children(4)->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
	//year
	$yearDom = $coverTxt->children(3)->find("a",0);
	if(!empty($yearDom))
	$year = $yearDom->plaintext;
	if($year == '其他')
	{
		$year = '';
	}
	
	$summary = '';
	$desc = $coverinfo->find('#mod_desc',0);
	if(!empty($desc))
	{
		$summaryDoms = $desc->find("p.mod_cont", 0);
		$summary = $summaryDoms->plaintext;
	}
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
function spiderMovieQq($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	if(empty($dom)) return array();
	$root = $dom->find('#content', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find(".mod_list_pic_130",0)->find("li");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
			
				$picblock =  $block->find('a',0);
				$coverlink= $picblock->href;
				$cover = coverMovieQq($coverlink);
				if(empty($cover)) continue;
				$imagedom = $picblock->find("img",0);
				if(!empty($imagedom))
				{
					$cover['imagelink'] = $imagedom->src;
				}
				if(!empty($cover))
				{
					$vrow[] = $cover;
				}
		}
	$root->clear();
	return $vrow;
}

//pptv.com
function coverMoviePptv($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	$showinfo = $coverRoot->find('.showinfo',0);
	
	if(empty($showinfo))
		return array();
	$star = $director = $area = $cate = $year = array();
	$coverinfo = $coverRoot->find(".showinfo", 0);
	if(empty($coverinfo)) return array();
	$bd =  $coverinfo->find(".bd", 0);
	if(empty($bd)) return array();
	$baseinfo = $bd->find("ul", 0);
	//playlink title segment
	if(empty($baseinfo)) return array();
	$vrow['playlink'] = $url;
	
	$child = $baseinfo->children(0);
	if(empty($child)) return array();
	$titlea = $coverRoot->find("h1",0);
	$vrow['title'] = $titlea->plaintext;
	
	//year
	$yeardom = $baseinfo->children(0);
	if(!empty($yeardom))
	{
		$yeartext = $yeardom->plaintext;
		if(preg_match('/\((\d*?)\)/ims', $yeartext, $match))
		{
			$year = $match[1];
		}
	}
	
	if(preg_match("/\/show\/(.*?)\.html/im", $vrow['playlink'], $match))
	{
		$flv = json_encode(array("sid"=>$match[1]));
		$vrow['flv'] = $flv;
	}

	
	
	//director
	
	
	$directord = $baseinfo->children(4);
	if(empty($directord)) {return array();}
	$plaintext = $directord->plaintext;
	if(substr_count($plaintext, '导演') < 1)
	{
		return array();
	}
	$directorDoms = $directord->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
	
	//info segment
	$actor = $baseinfo->children(5);
	if(empty($actor)) return array();
	$starDoms = $actor->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
	
		
	//score	
	$scoredom = $baseinfo->find(".showrating",0);
	
	if(!empty($scoredom))
	{
		$scorenum = $scoredom->find("em",0);
		if(!empty($scorenum))
			$vrow['score'] = $scorenum->plaintext;
	}
	$areacatedom = $baseinfo->children(3);
	if(!empty($areacatedom))
	{
		$areacateHtml = $areacatedom->innertext;
		if(preg_match('/地区(.*?)类型/ims',$areacateHtml,$match))
		{
			$areahtml = $match[1];
			if(preg_match_all('/<a.*?>(.*?)<\/a>/ims',$areahtml,$matcha))
			{
				$area = $matcha[1];
			}
		}
		if(preg_match('/类型(.*)/ims',$areacateHtml,$match))
		{
			$cateHtml = $match[1];
			if(preg_match_all('/<a.*?>(.*?)<\/a>/ims',$cateHtml,$matchc))
			{
				$cate = $matchc[1];
			}
		}
	}
	$summary = '';
	$summaryDom = $baseinfo->children(7);
	if(!empty($summaryDom))
	{
		$summary = str_replace("更多","",$summaryDom->plaintext);
	
	}
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	if(isset($area[0]))
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;	
}
function spiderMoviePptv($url)
{
	
	$vrow = array();
	$dom = domByCurl($url);
	
	$root = $dom->find('.list_120x160', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find("li");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
			$picdom = $block->find(".pic",0);
			$picblock =  $picdom->find('a',0);
			$coverlink= $picblock->href;
			$cover = coverMoviePptv($coverlink);
			if(empty($cover)) continue;
			$imagedom = $picdom->find("img",0);
			if(!empty($imagedom))
			{
				$cover['imagelink'] = $imagedom->src;
			}
			if(!empty($cover))
			{
				$vrow[] = $cover;
			}
		}
	$root->clear();
	return $vrow;
}
function coverTeleplayPptv($url)
{
	$vrow['playlink'] = $url;
	$vrow['infolink'] = $url;
	
	$data = curlByUrl($url);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);
	
	if(empty($coverRoot)) return array();
	$coverinfo = $coverRoot->find(".showinfo", 0);
	if(empty($coverinfo)) return array();
	$baseinfo = $coverinfo->find("ul", 0);
	
	
	$allepisodes = $nowepisodes = 0;
	$eplisodetxt = $coverRoot->find(".upto", 0);
	if(preg_match('/(\d+)集全/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至第(\d+)集/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;

	if(preg_match("/\"channel_id\":(\d+),/ims", $data, $match))
	{
		$channel_id = $match[1];
	}

	if(empty($channel_id)) return array();
	$coverRoot->clear();
	
	if(!empty($nowepisodes))
	{
		$http_episodes_num = $nowepisodes;
	}
	else
	{
		$http_episodes_num = $allepisodes;
	}
	for($i = $j= 1; $i <= $http_episodes_num; $i=$i+20,$j=$j+1)
	{
		$episode_url = "http://api2.v.pptv.com/api/page/episodes.js?page=$j&channel_id=$channel_id";
		$episode_data = curlByUrl($episode_url);
		if(empty($episode_data))
			return array();
		if(preg_match('/^\(({.*?})\);$/ims', $episode_data,$match))
		{
			$episode_data_json = json_decode($match[1], true);
			$episode_data = $episode_data_json['html'];
		}
		else 
		{
			return array();
		}
		$episode_coverRoot = new simple_html_dom();
		$episode_coverRoot->load($episode_data);
		
		$picdoms = $episode_coverRoot->find(".pic");
		foreach($picdoms as $k => $picdom)
		{
			$hrefdom = $picdom->find("a",0);
			$eplisodehref = $hrefdom->href;
			$eplisodepic = $hrefdom->find("img",0)->src;
			$flv='';
			if(preg_match("/\/show\/(.*?)\.html/im", $eplisodehref, $match))
			{
				$flv = json_encode(array("sid"=>$match[1]));
			}
			$vrow['episodes'][] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodepic,'flv'=>$flv);
		}
		unset($episode_data);
		$episode_coverRoot->clear();
	}
	if(!isset($vrow['episodes'])) return array();
	
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	
	
	
	$first_url = $vrow['episodes'][0]['playlink'];
	$coverRoot = domByCurl($first_url);
	if(empty($coverRoot)) return array();
	$showinfo = $coverRoot->find('.showinfo',0);
	
	if(empty($showinfo))
		return array();
	$star = $director = $area = $cate = $year = array();
	$coverinfo = $coverRoot->find(".showinfo", 0);
	$baseinfo = $coverinfo->find(".bd", 0)->find("ul", 0);
	//playlink title segment
	if(empty($baseinfo)) return array();
	
	$vrow['title'] = $baseinfo->children(0)->find("a",0)->plaintext;
	
	//year
	$yeardom = $baseinfo->children(0);
	if(!empty($yeardom))
	{
		$yeartext = $yeardom->plaintext;
		if(preg_match('/\((\d*?)\)/ims', $yeartext, $match))
		{
			$year = $match[1];
		}
	}
	
	
	
	//director
	$directord = $baseinfo->children(4);
	if(empty($directord)) return array();
	$plaintext = $directord->plaintext;
	if(substr_count($plaintext, '导演') < 1)
	{
		return array();
	}
	
	
	$directorDoms = $directord->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
	
	//info segment
	$actor = $baseinfo->children(5);
	$starDoms = $actor->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}
	
		
	//score	
	$scoredom = $baseinfo->find(".showrating",0);
	
	if(!empty($scoredom))
	{
		$scorenum = $scoredom->find("em",0);
		if(!empty($scorenum))
			$vrow['score'] = $scorenum->plaintext;
	}
	$areacatedom = $baseinfo->children(3);
	if(!empty($areacatedom))
	{
		$areacateHtml = $areacatedom->innertext;
		if(preg_match('/地区(.*?)类型/ims',$areacateHtml,$match))
		{
			$areahtml = $match[1];
			
			if(preg_match_all('/<a.*?>(.*?)<\/a>/ims',$areahtml,$matcha))
			{
				$area = $matcha[1];
			}
		}
		if(preg_match('/类型(.*)/ims',$areacateHtml,$match))
		{
			$cateHtml = $match[1];
			if(preg_match_all('/<a.*?>(.*?)<\/a>/ims',$cateHtml,$matchc))
			{
				$cate = $matchc[1];
			}
		}
	}
	$summary = '';
	$summaryDom = $baseinfo->children(7);
	if(!empty($summaryDom))
	{
		$summary = str_replace("更多","",$summaryDom->plaintext);
	
	}
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	
	$coverRoot->clear();
	return $vrow;	
}
function spiderTeleplayPptv($url)
{
	$vrow = array();
	$dom = domByCurl($url);
	if(empty($dom)) return ;
	$root = $dom->find('.list_120x160', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find("li");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
			$picdom = $block->find(".pic",0);
			$picblock =  $picdom->find('a',0);
			$coverlink= $picblock->href;
			$cover = coverTeleplayPptv($coverlink);
			if(empty($cover)) continue;
			$imagedom = $picdom->find("img",0);
			if(!empty($imagedom))
			{
				$cover['imagelink'] = $imagedom->src;
			}
			if(!empty($cover))
			{
				$vrow[] = $cover;
			}
	}
	$root->clear();
	return $vrow;
}
function coverCartoonPptv($url)
{
	$vrow['playlink'] = $url;
	$vrow['infolink'] = $url;
	$data = curlByUrl($url);
	if(empty($data))
		return array();
	$coverRoot = new simple_html_dom();
	$coverRoot->load($data);
	
	if(empty($coverRoot)) return array();
	$coverinfo = $coverRoot->find(".showinfo", 0);
	if(empty($coverinfo)) return array();
	$baseinfo = $coverinfo->find("ul", 0);
	
	
	$allepisodes = $nowepisodes = 0;
	$eplisodeinfo = $baseinfo->children(5);
	$eplisodetxt = trim($eplisodeinfo->plaintext);
	if(preg_match('/集数.*?(\d+)集/', $eplisodetxt,$match))
	{
		$allepisodes = $match[1];
	}
	if(preg_match('/更新至第(\d+)集/', $eplisodetxt,$match))
	{
		$nowepisodes = $match[1];
	}
	$vrow['allepisodes'] = $allepisodes;
	$vrow['nowepisodes'] = $nowepisodes;
	
	if(preg_match("/\"channel_id\":(\d+),/ims", $data, $match))
	{
		$channel_id = $match[1];
	}
	$star = $director = $area = $cate = $year = array();
	$catedoms = $baseinfo->children(3)->find("a");
	if(!empty($catedoms))
	{
		foreach($catedoms as $k => $catedom)
		{
			$cate[] = $catedom->plaintext;
		}
	}
	if(empty($channel_id)) return array();
	$coverRoot->clear();
	$http_episodes_num = 0;
	if(!empty($nowepisodes))
	{
		$http_episodes_num = $nowepisodes;
	}
	else
	{
		$http_episodes_num = $allepisodes;
	}
	if(empty($http_episodes_num))
	{
		$http_episodes_num = 20;
	}
	for($i = $j= 1; $i <= $http_episodes_num; $i=$i+20,$j=$j+20)
	{
		$episode_url = "http://api2.v.pptv.com/api/page/episodes.js?page=$i&channel_id=$channel_id";
		$episode_data = curlByUrl($episode_url);
		if(empty($episode_data))
			return array();
		if(preg_match('/^\(({.*?})\);$/ims', $episode_data,$match))
		{
			$episode_data_json = json_decode($match[1], true);
			$episode_data = $episode_data_json['html'];
		}
		else
		{
			return array();
		}
		$episode_coverRoot = new simple_html_dom();
		$episode_coverRoot->load($episode_data);
	
		$picdoms = $episode_coverRoot->find(".pic");
		foreach($picdoms as $k => $picdom)
		{
			$hrefdom = $picdom->find("a",0);
			$eplisodehref = $hrefdom->href;
			$eplisodepic = $hrefdom->find("img",0)->src;
			$flv='';
			if(preg_match("/\/show\/(.*?)\.html/im", $eplisodehref, $match))
			{
				$flv = json_encode(array("sid"=>$match[1]));
			}
			$vrow['episodes'][] = array('playlink'=>$eplisodehref,'imagelink'=>$eplisodepic,'flv'=>$flv);
		}
		unset($episode_data);
		$episode_coverRoot->clear();
	}
	
	
	
	
	if(!isset($vrow['episodes'])) return array();
	
	
	
	if(($vrow['allepisodes'] == $vrow['nowepisodes']) &&
			!empty($vrow['allepisodes']) &&
			!empty($vrow['nowepisodes']))
	{
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']) && count($vrow['episodes']) == $vrow['allepisodes'])
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 2;
	}
	else if(empty($vrow['nowepisodes']))
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
		$vrow['epsign'] = 1;
	}
	else
	{
		$vrow['epsign'] = 1;
	}
	
	if(isset($vrow['episodes']) && !empty($vrow['episodes']) && $nowepisodes==0 && $allepisodes ==0)
	{
		$vrow['nowepisodes'] = count($vrow['episodes']);
	}
	
	$first_url = $vrow['episodes'][0]['playlink'];
	
	$coverRoot = domByCurl($first_url);
	if(empty($coverRoot)) return array();
	$showinfo = $coverRoot->find('.showinfo',0);
	
	if(empty($showinfo))
		return array();
	$coverinfo = $coverRoot->find(".showinfo", 0);
	$baseinfo = $coverinfo->find(".bd", 0)->find("ul", 0);
	//playlink title segment
	if(empty($baseinfo)) return array();
	
	$vrow['title'] = $baseinfo->children(0)->find("a",0)->plaintext;
	
	//year
	$yeardom = $baseinfo->children(0);
	if(!empty($yeardom))
	{
		$yeartext = $yeardom->plaintext;
		if(preg_match('/\((\d*?)\)/ims', $yeartext, $match))
		{
			$year = $match[1];
		}
	}

	//score
	$scoredom = $baseinfo->find(".showrating",0);

	if(!empty($scoredom))
	{
		$scorenum = $scoredom->find("em",0);
		if(!empty($scorenum))
			$vrow['score'] = $scorenum->plaintext;
	}
	$areadoms = $baseinfo->children(2)->find("a");
	if(!empty($areadoms))
	{
		foreach($areadoms as $k => $areadom)
		{
			$area[] = $areadom->plaintext;
		}
		
	}
	$summary = '';
	$summaryDom = $baseinfo->children(6);
	if(!empty($summaryDom))
	{
		$summary = str_replace("更多","",$summaryDom->plaintext);

	}
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
//pptv动漫失效
function spiderCartoonPptv($url)
{
	$vrow = array();
	$dom = domByCurl($url);

	$root = $dom->find('.list_120x160', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->find("li");
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
			$picdom = $block->find(".pic",0);
			$picblock =  $picdom->find('a',0);
			$coverlink= $picblock->href;
			$cover = coverCartoonPptv($coverlink);
			if(empty($cover)) continue;
			$imagedom = $picdom->find("img",0);
			if(!empty($imagedom))
			{
				$cover['imagelink'] = $imagedom->src;
			}
			if(!empty($cover))
			{
				$vrow[] = $cover;
			}
		}
		$root->clear();
		return $vrow;
}
//废弃
function coverMovie163($url)
{
	$vrow = array();
	$vrow['infolink'] = $url;
	$coverRoot = domByCurl($url);
	if(empty($coverRoot)) return array();
	$star = $director = $area = $cate = $year = array();

	$coverinfo = $coverRoot->find(".category-panels", 0);
	$baseinfo = $coverinfo->children(0)->find(".left",0);

	//playlink title segment
	$link = $baseinfo->find('h3',0);
	$vrow['playlink'] = $link->find('a',0)->href;
	$vrow['title'] = $link->find('a',0)->title;
	//pic
	// 	$link = $baseinfo->find(".thumb", 0);
	// 	$vrow['imagelink'] = $link->find('img',0)->src;

	//hd
	// 	$vdhdom = $baseinfo->find('.ishd',0)->find('span',0);
	// 	if(!empty($vdhdom))
		// 	{
		// 		$vdh = $vdhdom->class;
		// 		$quality = mapYouku($vdh);
		// 		$vrow['quality'] = $quality;
		// 	}


	//info segment
	$actor = $baseinfo->find('ul',0)->find(".last", 0);
	$starDoms = $actor->find("a");
	//star
	if(!empty($starDoms))
		foreach($starDoms as $k => $starDom)
		{
			$star[] = $starDom->plaintext;
		}


	//director
	$directord = $baseinfo->find('ul',0)->children(2);
	$directorDoms = $directord->find("a");
	if(!empty($directorDoms))
		foreach($directorDoms as $k => $directorDom)
		{
			$director[] = $directorDom->plaintext;
		}
	//area
	$aread = $baseinfo->find('ul',0)->children(3);
	$areaDoms = $aread->find("a");
	if(!empty($areaDoms))
		foreach($areaDoms as $k => $areaDom)
		{
			$area[] = $areaDom->plaintext;
		}
	//cate
	$cated = $baseinfo->find('ul',0)->children(0);
	$cateDoms = $cated->find("a");
	if(!empty($cateDoms))
		foreach($cateDoms as $k => $cateDom)
		{
			$cate[] = $cateDom->plaintext;
		}
	//year
	$year =	$cated = $baseinfo->find('ul',0)->children(1)->find("a",0)->plaintext;
	$year = str_replace("年", "", $year);
	$summaryDom = $baseinfo->find(".m-info",0);
	$summary = $summaryDom->plaintext;
	$vrow['star'] = $star;
	$vrow['director'] = $director;
	$vrow['area'] = $area[0];
	$vrow['cate'] = $cate;
	$vrow['year'] = $year;
	$vrow['summary'] = $summary;
	$coverRoot->clear();
	return $vrow;
}
//废弃
function spiderMovie163($url)
{
	$vrow = array();
	$dom = domByCurl($url);

	$root = $dom->find('.list-panel', 0);
	if(empty($root))
	{
		echo "spider not known this page,$url.\r\n";
		return ;
	}
	$rows = $root->children(0)->children();
	if(!empty($rows))
		foreach($rows as $k => $block)
		{
				
			$blocklink =  $block->find('.subscribe-list',0);
			$coverlink= $blocklink->find('a',0)->href;
			$imagelinkDom = $block->find('.ui-video-info',0)->find(".left",0)->find("img",0);
			$imagelink = $imagelinkDom->src;
			$cover = coverMovie163($coverlink);
			if(!empty($cover))
			{
				$cover['imagelink'] = $imagelink;
				$vrow[] = $cover;
			}
				
		}
	$root->clear();
	return $vrow;
}



function loadStringToXml($xmlstring)
{
	libxml_use_internal_errors(true);
	$xmlobj = simplexml_load_string($xmlstring,'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
	if(empty($xmlobj))
	{
	
		$error = libxml_get_errors();
		libxml_clear_errors();
// 		foreach($error as $k => $v) {
// 			$message .= "xml string:" . $v->line . "\tcolumn" . $v->column . "\tmessage:" . $v->message;
// 		}
// 		mini::e("config not load xml string message:{message}" ,array('{message}'=>$message));
		return null;
	}
	return $xmlobj;
}



//status = 2 预告片
function spiderApiMovieM1905($url)
{
	$xmldata = curlByUrl($url,60);
	if(empty($xmldata)) return array();
	$xmlobj = loadStringToXml($xmldata);
	if(empty($xmlobj)) return array();
	$vrow = array();
	foreach($xmlobj->movie as $k => $obj)
	{
		$row = array();
		if((int)$obj->del != 0) continue;
		$row['title'] = (string)$obj->title;
		$row['infolink'] = (string)$obj->playLink;
		$row['playlink'] = (string)$obj->playLink;
		$row['imagelink'] = (string)$obj->imageLink;
		$row['cate'] = explode('\t',(string)$obj->cate);
		$row['quality'] = (string)$obj->quality;
		$row['area'] = explode('\t',(string)$obj->area);
		$row['year'] = (string)$obj->years;
		$row['director'] = explode('\t',(string)$obj->director);
		$row['star'] = explode('\t',(string)$obj->star);
		$row['summary'] = (string)$obj->summary;
		if(preg_match("/\/vod\/play\/(\d+)\./",$row['playlink'], $match))
		{
			$ids = $match[1];
			$pid = "/".$ids[0]."/".$ids[1]."/".$ids."_1.xml";
			$flv = json_encode(array("pid"=>$pid));
			$row['flv'] = $flv;
		}
	
	
		//$row['flv'] = configUrl=http://static.m1905.com/profile/vod/8/6/86084_1.xml"
		$row['duration'] = (string)$obj->duration;
		$row['score'] = round((string)$obj->score,1);
		if(
				empty($row['title']) || 
				empty($row['infolink'])  || 
				empty($row['imagelink'])  || 
				empty($row['cate'])  || 
				empty($row['director']) 
		) continue;
		
		$vrow[] = $row;
	}
	return $vrow;
}
//status = 2 预告片
function spiderApiMovieTudou($url)
{
	$xmldata = curlByUrl($url,60);
	if(empty($xmldata)) return array();
	$xmlobj = loadStringToXml($xmldata);
	if(empty($xmlobj)) return array();
	$vrow = array();
	foreach($xmlobj->movie as $k => $obj)
	{
		$row = array();
		if((int)$obj->del != 0 || (int)$obj->status != 1) continue;
		$row['title'] = (string)$obj->title;
		$playurl = (string)$obj->playLink;
		
		if(preg_match('/albumplay\/(.*?)\/(.*?)\.html/',$playurl, $match))
		{
			$perid = $match[1];
			$fixid = $match[2];
			$playlink = "http://www.tudou.com/albumplay/".$perid."/".$fixid.".html";
			$infolink = "http://www.tudou.com/albumcover/".$perid.".html";
		}
		if(!isset($infolink)) continue;
		$data = curlByUrl($infolink);
		if(!empty($data))
		{
			$flv = array();
			if(preg_match("/\<script\>.*?iid:\s*\'(\d+)',/ism", $data, $match))
			{
				$flv["iid"]=$match[1];
				$flv["sid"]=$perid;
				$row['flv'] = json_encode($flv);
			}
		
		}
		
		$row['infolink'] = $infolink;
		$row['playlink'] = $playlink;
		$row['imagelink'] = (string)$obj->imageLink;
		$row['cate'] = explode('\t',(string)$obj->cate);
		$row['quality'] = (string)$obj->quality;
		$row['area'] = explode('\t',(string)$obj->area);
		$row['year'] = (string)$obj->years;
		$row['director'] = explode('\t',(string)$obj->director);
		$row['star'] = explode('\t',(string)$obj->star);
		$row['summary'] = (string)$obj->summary;
		
	
		$row['duration'] = (string)$obj->duration;
		$row['score'] = round((string)$obj->score,1);
		if(
				empty($row['title']) ||
				empty($row['infolink'])  ||
				empty($row['imagelink'])  ||
				empty($row['cate'])  ||
				empty($row['director'])
		) continue;
	
		$vrow[] = $row;
	}
	return $vrow;
}
//http://share.vrs.sohu.com/759562/v.swf&autoplay=false
function spiderApiMovieSohu($url)
{
	$xmldata = curlByUrlSOHUAPI($url,60);
	if(empty($xmldata)) return array();
	$xmlobj = loadStringToXml($xmldata);
	if(empty($xmlobj)) return array();
	$vrow = array();

	foreach($xmlobj->movie as $k => $obj)
	{
		$row = array();
		if((int)$obj->del != 0 || (int)$obj->status != 1) continue;
		
		$row['title'] = (string)$obj->title;
		$row['infolink'] = (string)$obj->playLink;
		$row['playlink'] = (string)$obj->playLink;
		$row['imagelink'] = (string)$obj->imageLink;
		$row['cate'] = explode('\t',(string)$obj->cate);
		$row['quality'] = (string)$obj->quality;
		$row['area'] = explode('\t',(string)$obj->area);
		$row['year'] = (string)$obj->years;
		$row['director'] = explode('\t',(string)$obj->director);
		$row['star'] = explode('\t',(string)$obj->star);
		$row['summary'] = (string)$obj->summary;
	
		$data = curlByUrl($row['playlink']);
		if(preg_match('/var\s*?vid="(\d+)";/', $data, $match))
		{
			$row['flv'] = json_encode(array("vid"=>$match[1]));
		}
		$row['duration'] = (string)$obj->duration;
		$row['score'] = round((string)$obj->score,1);
		if(
				empty($row['title']) ||
				empty($row['infolink'])  ||
				empty($row['imagelink'])  ||
				empty($row['cate'])  ||
				empty($row['director'])
		) continue;

		$vrow[] = $row;
	}
	return $vrow;
}
function spiderApiMoviepptv($url)
{
	$xmldata = curlByUrl($url,60);
	if(empty($xmldata)) return array();
	$xmlobj = loadStringToXml($xmldata);
	if(empty($xmlobj)) return array();
	$vrow = array();

	foreach($xmlobj->movie as $k => $obj)
	{
		$row = array();
		if((int)$obj->del != 0 || (int)$obj->status != 1) continue;

		$row['title'] = (string)$obj->title;
		$row['infolink'] = (string)$obj->playLink;
		$row['playlink'] = (string)$obj->playLink;
		$row['imagelink'] = (string)$obj->imageLink;
		$row['cate'] = explode('\t',(string)$obj->cate);
		$row['quality'] = (string)$obj->quality;
		$row['area'] = explode('\t',(string)$obj->area);
		$row['year'] = (string)$obj->years;
		$row['director'] = explode('\t',(string)$obj->director);
		$row['star'] = explode('\t',(string)$obj->star);
		$row['summary'] = (string)$obj->summary;
		
		if(preg_match('/show\/(.*?)\.html/', $row['infolink'], $match))
		{
			$row['flv'] = json_encode(array("sid"=>$match[1]));
		}
		$row['duration'] = (string)$obj->duration;
		$row['score'] = round((string)$obj->score,1);
		if(
				empty($row['title']) ||
				empty($row['infolink'])  ||
				empty($row['imagelink'])  ||
				empty($row['cate'])  ||
				empty($row['director'])
		) continue;

		$vrow[] = $row;
	}
	return $vrow;
}
function spiderApiMovieYouku($url)
{
	$xmldata = curlByUrl($url,60);
	if(empty($xmldata)) return array();
	$xmlobj = loadStringToXml($xmldata);
	if(empty($xmlobj)) return array();
	$vrow = array();

	foreach($xmlobj->movie as $k => $obj)
	{
		$row = array();
		if((int)$obj->del != 0 || (int)$obj->status != 1) continue;

		$row['title'] = (string)$obj->title;
		$playurl = (string)$obj->playLink;
		if(preg_match('/url=(.*)/', $playurl,$match))
		{
			$realurl = urldecode($match[1]);
			echo $realurl;
			if(preg_match('/id_(.*?)\.html/', $realurl,$rmatch))
			{
				$sid =$rmatch[1];
				$playlink = "http://v.youku.com/v_show/id_".$sid.".html";
				$coverRoot = domByCurl($playlink);
				if(empty($coverRoot)) return array();
				$infolink = $coverRoot->find('.show_intro',0)->find('.title',0)->find('a',0)->href;
				
				$row['flv'] = json_encode(array("sid"=>$sid));
			}
			
		}
		if(empty($infolink)) continue;
		$row['infolink'] = $infolink;
		$row['playlink'] = $playlink;
		$row['imagelink'] = (string)$obj->imageLink;
		$row['cate'] = explode('\t',(string)$obj->cate);
		$row['quality'] = (string)$obj->quality;
		$row['area'] = explode('\t',(string)$obj->area);
		$row['year'] = (string)$obj->years;
		$row['director'] = explode('\t',(string)$obj->director);
		$row['star'] = explode('\t',(string)$obj->star);
		$row['summary'] = (string)$obj->summary;
		$row['duration'] = (string)$obj->duration;
		$row['score'] = round((string)$obj->score,1);
		if(
				empty($row['title']) ||
				empty($row['infolink'])  ||
				empty($row['imagelink'])  ||
				empty($row['cate'])  ||
				empty($row['director'])
		) continue;

		$vrow[] = $row;
	}
	return $vrow;
}
