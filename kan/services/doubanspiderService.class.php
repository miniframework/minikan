<?php
class doubanspiderService
{
	private $curl = null;
	public $searchUrl = "http://movie.douban.com/subject_search?cat=1002&search_text={keyword}";
	public $reviewUrl = "http://movie.douban.com/j/review/{keyword}/fullinfo?show_works=False";
	public $movieUrl = "http://movie.douban.com/subject/{keyword}/";
	public function __construct($params=array())
	{
		$curl = new mini_tool_curl($params);
		$this->curl = $curl;
	}
	public function getData($url, $keyword)
	{
		$url = strtr($url, array('{keyword}'=>$keyword));
		$data = $this->curl->getData($url);
		return $data;
	}
	public function domByData($data)
	{
		if(!empty($data))
		{
			include_once dirname(__FILE__).'/../libs/simple_html_dom.php';
			$dom = new simple_html_dom();
			$dom->load($data);
			return $dom;
		}
		return ;
	}
	public function searchLikeApi($params)
	{
		$data = $this->search($params);
		if(empty($data) || empty($data['title'])) return array();
		$rdata = array();
		$rdata['subjectid'] = $data['doubanid'];
		$rdata['title'] = $data['title'];
		$rdata['image'] = $data['pic'];
		if(isset($data['director'][0]))
			$rdata['director'] = $data['director'][0];
		$rdata['star'] = $data['star'];
		$rdata['cate'] = $data['cate'];
		if(isset($data['area'][0]))
			$rdata['area'] = trim($data['area'][0]);
		$rdata['lang'] = $data['lang'];
		$rdata['summary'] = $data['summary'];
		if(isset($data['imdb'][0]))
			$rdata['imdb'] = $data['imdb'][0];
		$rdata['rate'] = $data['rate'];
		$rdata['tag'] = $data['tag'];
		if(isset($data['pubdate'][0]))
		{
			if(preg_match('/(\d{4})/', $data['pubdate'][0], $match))
			{
				$rdata['year'] = $match[1];
			}
		}
		$rdata['ext'] = $data;
		return $rdata;
	}
	public function subjectId($subjectID)
	{
		$targetUrl =  strtr($this->movieUrl, array('{keyword}'=>$subjectID));
		if(!empty($targetUrl)) {
			$info = $this->getInfo($targetUrl);
			if(preg_match('/\/subject\/(\d+)\//', $targetUrl,$match))
			{
				$info['doubanid'] = $match[1];
				return $info;
			}
				
		}
	}
	public function search($params)
	{
		$title = $params['title'];
		$dom = $this->domByData($this->getData($this->searchUrl,$title));
		if(!empty($dom))
		{
			
			$targetUrl = $this->getTargetUrl($dom, $params);
		
			$dom->clear();
 			if(!empty($targetUrl)) {
 				$info = $this->getInfo($targetUrl);
 				if(empty($info['title'])) return array();
 				if(preg_match('/\/subject\/(\d+)\//', $targetUrl,$match))
 				{
					$info['doubanid'] = $match[1];
					return $info;
 				}
 				
 			}
		}
		return array();
	}
	public function getInfo($url)
	{
		$targetdom = $this->domByData($this->curl->getData($url));
		if(!empty($targetdom)) {
			
			$data =  $this->getResult($targetdom);
			$targetdom->clear();
			return $data;
		}
	}
	public function getResult($dom)
	{
		$result = array();
		$result['title'] = $this->getTitle($dom);
		$result['pic'] = $this->getMainPic($dom);
		$result['director'] = $this->getDirector($dom);
		$result['writer'] = $this->getWriter($dom);
		$result['star'] = $this->getStar($dom);
		$result['cate'] = $this->getCate($dom);
		$result['area'] = $this->getArea($dom);
		$result['website'] = $this->getWebsite($dom);
		$result['lang'] = $this->getLang($dom);
 		$result['pubdate'] = $this->getPubdate($dom);
 		$result['runtime'] = $this->getRuntime($dom);
		$result['alias'] = $this->getAlias($dom);
		$result['imdb'] = $this->getImdb($dom);
		$result['rate'] = $this->getRate($dom);
		$result['summary'] = $this->getSummary($dom);
		$result['tag'] = $this->getTag($dom);
		$result['shortcomment'] = $this->getShortComment($dom);
		$result['comment'] = $this->getComment($dom);
		
		return $result;
	}
	public function getTitle($dom)
	{
		$infodom = $dom->find("h1",0);
		if(!empty($infodom))
		{
			$titledom = $infodom->find("span[property=v:itemreviewed]",0);
			if(!empty($titledom))
			{
				$titlestr = $titledom->plaintext;
				$titlearr = explode(" ",$titlestr);
				$i = $s = 0;
				foreach($titlearr as $titlek)
				{
					if(preg_match('/^[\w:]+$/',$titlek,$match))
					{
						if($s ==0)
						{
							$i++;
							$title[$i] = $titlek." ";
							$s = 1;
						}
						else
						{
							$title[$i] .= $titlek." ";
						}
					}
					else
					{
						$s = 0;
						$i++;
						$title[$i] = $titlek;
					}
						
				}
				
				return $title[1];
			}
		
		}
		return array();
	}
	public function getTag($dom)
	{
		$infodom = $dom->find("#db-tags-section",0);
		if(!empty($infodom))
		{
			$indent = $infodom->find(".indent",0);
			if(!empty($indent))
			{
				$tagas = $indent->find("a");
				if(!empty($tagas))
					foreach($tagas as $taga)
				{
					$tag[] = $taga->plaintext;
				}
				return $tag;
			}
		
		}
		return array();
	}
	public function getComment($dom)
	{
		
		$replace = array('/<div.*/ism','/<a.*?>/ism','/<\/a>/');
		$comment = array();
		$infodom = $dom->find("#wt_0",0);
		if(!empty($infodom))
		{
			$ctshdoms = $infodom->find("div.ctsh");
			$i = 0;
			if(!empty($ctshdoms))
			{
				foreach($ctshdoms as $ctshdom)
				{
					$nlstdoma = $ctshdom->find("li.nlst",0)->find("a",0);
					if(!empty($nlstdoma))
					{
						$comment[$i]['title'] = $nlstdoma->title;
						$comment[$i]['href'] = $nlstdoma->href;
						if(preg_match('/\/review\/(\d+)\//', $comment[$i]['href'],$matchr))
						{
							$reviewid = $matchr[1];
							$json_review = $this->getData($this->reviewUrl, $reviewid);
							$review_html = json_decode($json_review, true);
							if(isset($review_html['html']) && !empty($review_html)) {
								
								
								$comment[$i]['comment'] =   preg_replace($replace,array(),$review_html['html']);
								
							}
						
						}
						
					}
					$i++;
				}
				return $comment;
			}
		}
		return array();
	}
	public function getShortComment($dom)
	{
		$infodom = $dom->find(".simple_list",0);
		if(!empty($infodom))
		{
			$shortdoms = $infodom->find("p.w550");
			if(!empty($shortdoms))
			{
				foreach($shortdoms as $shortdom)
				{
					$shortcomment[] = $shortdom->plaintext;
				}
			}
			return $shortcomment;
		}
		return array();
	}
	public function getSummary($dom)
	{
		$infodom = $dom->find("#link-report",0);
		if(!empty($infodom))
		{
			$summary = $infodom->find("span[property=v:summary]",0)->plaintext;
			$search= array('&nbsp;','&copy;', '豆瓣');
			$replace = array();
			$summary = str_replace($search, $replace, $summary);
			return $summary;
		}
		return '';
	}
	public function getRate($dom)
	{
		$infodom = $dom->find("#interest_sectl",0);
		if(!empty($infodom))
		{
			$rate = $infodom->find("strong[property=v:average]",0)->plaintext;
			return $rate;
		}
		return '';
	}
	public function getImdb($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			if(preg_match('/<span.*?>IMDb链接:<\/span>.*?<a.*?>(.*?)<\/a>/', $infoHTML,$match))
			{
				$imdb = $match[1];
				if(!empty($imdb))
				{
					return array($imdb);
				}
	
			}
	
		}
		return array();
	}
	public function getWebsite($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			if(preg_match('/<span.*?>官方网站:<\/span>.*?<a.*?href="(.*?)".*?<br\/>/', $infoHTML,$match))
			{
				$website = $match[1];
				if(!empty($website))
				{
					return array($website);
				}
	
			}
	
		}
		return array();
	}
	public function getRuntime($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$runtimedoms = $infodom->find("span[property=v:runtime]");
			if(!empty($runtimedoms))
			{
				foreach($runtimedoms as $runtimedom)
				{
					$runtime[]=$runtimedom->plaintext;
				}
				return $runtime;
			}
	
		}
		return array();
	
	}
	public function getPubdate($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$pubdatedoms = $infodom->find("span[property=v:initialReleaseDate]");
			if(!empty($pubdatedoms))
			{
				foreach($pubdatedoms as $pubdatedom)
				{
					$pubdate[]=$pubdatedom->plaintext;
				}
				return $pubdate;
			}
				
		}
		return array();
	
	}
	public function getAlias($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			if(preg_match('/<span.*?>又名:<\/span>(.*?)<br\/>/', $infoHTML,$match))
			{
				$aliasa = $match[1];
				if(!empty($aliasa))
				{
					$alias = explode("/",$aliasa);
					return $alias;
				}
	
			}
	
		}
		return array();
	}
	public function getLang($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			if(preg_match('/<span.*?>语言:<\/span>(.*?)<br\/>/', $infoHTML,$match))
			{
				$langa = $match[1];
				if(!empty($langa))
				{
					$lang = explode("/", $langa);
					return $lang;
				}
					
			}
	
		}
		return array();
	}
	public function getArea($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			if(preg_match('/<span.*?>制片国家\/地区:<\/span>(.*?)<br\/>/', $infoHTML,$match))
			{
				$areaa = $match[1];
				if(!empty($areaa))
				{
// 					$areaa = str_replace(' ', '', $areaa);
					$area = explode("/",$areaa);
					return $area;
				}
				
			}
		
		}
		return array();
	}
	public function getCate($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$catedoms = $infodom->find("span[property=v:genre]");
			if(!empty($catedoms))
			{
				foreach($catedoms as $catedom)
				{
					$cate[]=$catedom->plaintext; 
				}
				return $cate;
			}
			
		}
		return array();
		
	}
	public function getStar($dom)
	{
	
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			if(preg_match('/<span.*?>主演<\/span>(.*?)<\/span>/', $infoHTML,$match))
			{
				$stara = $match[1];
				if(!empty($stara))
					if(preg_match_all('/<a.*?>(.*?)<\/a>/', $stara, $matcha))
					{
						return $matcha[1];
					}
			}
	
		}
		return array();
	}
	public function getWriter($dom)
	{
		
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			if(preg_match('/<span.*?>编剧<\/span>(.*?)<\/span>/', $infoHTML,$match))
			{
				$writera = $match[1];
				if(!empty($writera))
					if(preg_match_all('/<a.*?>(.*?)<\/a>/', $writera, $matcha))
					{
						return $matcha[1];
					}
			}
				
		}
		return array();
	}
	public function getDirector($dom)
	{
		$infodom = $dom->find("#info",0);
		if(!empty($infodom))
		{
			$infoHTML = $infodom->innertext;
			
			if(preg_match('/<span><span.*?>导演<\/span>(.*?)<\/span>/', $infoHTML,$match))
			{
				$directora = $match[1];
				if(!empty($directora))
					if(preg_match_all('/<a.*?>(.*?)<\/a>/', $directora, $matcha))
					{
						return $matcha[1];
					}
			}
			
		}
		return array();
	}
	public function getMainPic($dom)
	{
		$mainpic = $dom->find("#mainpic",0);
		if(!empty($mainpic))
		{
			return $mainpic->find("img",0)->src;
		}
		return '';
	}
	public function getTargetUrl($dom, $params)
	{
		$indexdata = $this->getIndexData($dom);
		if(!empty($indexdata))
		{
			$data = $this->getIndexRule($indexdata, $params);
			if(!empty($data))
			{
				return $data['href'];
			}
		}
		return ;
	}
	public function getIndexRule($indexdata, $params)
	{
		$tmp = array();
		$tmporder = array();
		$i = 1;
		foreach($indexdata as $k => $data)
		{
			if($this->isinYear($params['year'], $data['text'])
					&& $this->isequTitle($params['title'], $data['title'])
					&& $this->isinStar($params['star'], $data['text'])
					&& $this->isinDirector($params['director'], $data['text'])
			)
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
				break;
			}
			if($this->isinYear($params['year'], $data['text']) 
					&& $this->isequTitle($params['title'], $data['title']) 
					&& $this->isinDirector($params['director'], $data['text'])
					)
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
				break;
			}
			if($this->isinYear($params['year'], $data['text'])
					&& $this->isinStar($params['star'], $data['text'])
					&& $this->isinDirector($params['director'], $data['text'])
			)
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
				break;
			}
			if($this->isinYear($params['year'], $data['text'])
					&& $this->isinStar($params['star'], $data['text'])
					&& $this->isequTitle($params['title'], $data['title'])
			)
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
				break;
			}
			if($this->isequTitle($params['title'], $data['title'])
					&& $this->isinStar($params['star'], $data['text'])
					&& $this->isinDirector($params['director'], $data['text'])
			)
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
				break;
			}
			
			
			if($this->isinYear($params['year'], $data['text'])
				&& $this->isequTitle($params['title'], $data['title']))
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
			}
			
			
			if($this->isinYear($params['year'], $data['text'])
					&& $this->isinDirector($params['director'], $data['text']))
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
			}
			
			
			if($this->isequTitle($params['title'], $data['title'])
					&& $this->isinDirector($params['director'], $data['text']))
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
			}
			
			
			if($this->isequTitle($params['title'], $data['title'])
					&& $this->isinStar($params['star'], $data['text']))
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
			}
			if($this->isinYear($params['year'], $data['text'])
					&& $this->isinStar($params['star'], $data['text']))
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
					$tmporder[$k]++;
				}
				$tmp[$k] = $data;
			}
			if($this->isinDirector($params['director'], $data['text'])
					&& $this->isinStar($params['star'], $data['text']))
			{
				if(!isset($tmporder[$k])) $tmporder[$k] = 1;
				else{
				$tmporder[$k]++;
				}
				$tmp[$k] = $data;
			}
			
		}
		if(!empty($tmporder))
		{
			arsort($tmporder);
			foreach($tmporder as $k => $v)
			{
				return $tmp[$k];
				break;
			}
		}
		return array();
	}
	public function isinStar($star, $text)
	{
		if(empty($star)) return false;
		$star_arr = explode("\t", $star);
		foreach($star_arr as $k => $v)
		{
			if(stristr($text, $v)) return true;
			return false;
		}
	}
	public function isinDirector($director, $text)
	{
		if(empty($director)) return false;
		$director_arr = explode("\t", $director);
		foreach($director_arr as $k => $v)
		{
			if(stristr($text, $v)) return true;
			return false;
		}
	}
	public function isinYear($year, $text)
	{
		if(empty($year)) return false;
		if(stristr($text, $year)) return true;
		return false;
	}
	public function isequTitle($title, $title2)
	{
		if($title == $title2)
			return true;
		else
			return false;
	}
	public function formatTitle($title)
	{
		return $title;
	}
	public function getIndexData($dom)
	{
		$indexdata = array();
		$articledom = $dom->find(".article",0);
		if(!empty($articledom))
		{
			$tabledoms = $articledom->find("table");
			if(!empty($tabledoms))
			{
				foreach($tabledoms as $k => $tabledom)
				{
					$itemdom = $tabledom->find(".item",0);
					if(!empty($itemdom))
					{
						$title =  $itemdom->children(0)->find("a",0)->title;
						$href =  $itemdom->children(0)->find("a",0)->href;
 						$titleall =  $itemdom->children(1)->find(".pl2",0)->find("a",0)->plaintext;
 						$text =  $itemdom->children(1)->find(".pl",0)->plaintext;
 						
 						$indexdata[] = array('title'=>$title, 'href'=>$href, 'titleall'=>$titleall, 'text'=>$text);
					}
				}
			}
		}
		return $indexdata;
	}
	
}
