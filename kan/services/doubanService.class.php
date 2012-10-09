<?php
class doubanService
{
	public $searchApi = "http://api.douban.com/movie/subjects?";
	public $reviewsApi = "http://api.douban.com/movie/subject/{subjectID}/reviews";
	public $reviewsImdbApi = "http://api.douban.com/movie/subject/imdb/{imdbID}/reviews";
	public $photosApi = "http://movie.douban.com/subject/{subjectID}/photos?type=S";
	public $movieApi = "http://api.douban.com/movie/subject/{subjectID}";
	public $access_token = "";
	public $numi = 0;
	private $curl = null;
	public function __construct()
	{
		$curl = new mini_tool_curl();
		$this->curl = $curl;
	}
	private function getData($url,$header = array())
	{
		$retry = 3;
		for($i = 0; $i<=$retry ; $i++)
		{
			$data = $this->curl->get($url, $header);
			if($this->curl->errorno == 28 || $this->curl->error || $this->curl->infocode['http_code'] != '200')
			{
				echo "<----retry $i.>\r\n";
			}
			else
			{
				break;
			}
		}

		if($this->curl->error || $this->curl->infocode['http_code'] != '200')
		{
			echo "curl get url:$url content error.".$this->curl->error."\r\n";
		}
		return $data;
	}
	private function buildApi($perurl, $params = array())
	{
		return $perurl.http_build_query($params);
	}
	public function search($q='', $tag='', $start=0, $max=10)
	{
		$params = array('tag'=>$tag, 'start-index'=>$start, 'max-results'=>$max,'q'=>$q);
		$api = $this->buildApi($this->searchApi, $params);
		echo $api."\r\n";
		$data = $this->getData($api);
		$searchIterator = new doubanSearchIterator($data);
		return $searchIterator;
	}
	public function findPhotos($subjectId)
	{
		$api = strtr($this->photosApi, array('{subjectID}'=>$subjectId));
		include dirname(__FILE__).'/../libs/spiderlib.php';
		$dom = domByCurl($api);
		if(empty($dom)) return array();
		
		$photosdom = $dom->find(".article",0)->children(1)->find("img");
		$photos = array();
		$i = 0;
		if(!empty($photosdom))
			foreach($photosdom as $k => $photodom)
		{
			if($i == 20) break;
			$i++;
			if(preg_match('/p(\d+)/', $photodom->src, $match))
			{
				$photos[] = $match[1];
			}
			
		}
		return $photos;
	}
	public function reviewsImdb($imdbID)
	{
		if(!empty($imdbID))
		{
			$api = strtr($this->reviewsImdbApi, array('{imdbID}'=>$imdbID));
			echo $api;
			$data = $this->getData($api);
			$reviewsIterator = new doubanReviewsIterator($data);
			$reviews = $reviewsIterator->findReviews();
			return $reviews;
		}
		return null;
	}
	public function reviews($subjectID)
	{
		if(!empty($subjectID))
		{
			$api = strtr($this->reviewsApi, array('{subjectID}'=>$subjectID));
			echo $api;
			$data = $this->getData($api);
			$reviewsIterator = new doubanReviewsIterator($data);
			$reviews = $reviewsIterator->findReviews();
			return $reviews;
		}
		return null;
	}
	public function Auth()
	{
		$authurl = "https://www.douban.com/service/auth2/token";
		$access_token_url =	"no..";
		$curl = new mini_tool_curl();
		$douban_data = $curl->post($authurl, $access_token_url);
		$douban_json_data = json_decode($douban_data, true);
		print_r($douban_json_data);
		if(!empty($douban_json_data['access_token']))
		$this->access_token = $douban_json_data['access_token'];
		$this->numi =  0;
	}
	public function AuthreviewsImdb($imdbID)
	{
		
		if(empty($this->access_token) || $this->numi > 500)
		{
			$this->Auth();
		}
		$this->numi++;
		if(!empty($imdbID) && !empty($this->access_token))
		{
			$reviewsApi = "https://api.douban.com/movie/subject/imdb/{imdbID}/reviews";
			$api = strtr($reviewsApi, array('{imdbID}'=>$imdbID));
			
			$header = array("Authorization: Bearer {$this->access_token}");
			print_r($header);
			$data = $this->getData($api,$header);
			$reviewsIterator = new doubanReviewsIterator($data);
			$reviews = $reviewsIterator->findReviews();
			return $reviews;
		}
		return null;
	}
	public function Authreviews($subjectID)
	{
		
		if(empty($this->access_token))
		{
			$this->Auth();
		}
		
		if(!empty($subjectID))
		{
			$reviewsApi = "https://api.douban.com/movie/subject/imdb/{imdbID}/reviews";
			$api = strtr($reviewsApi, array('{subjectID}'=>$subjectID));
			
			$header = array("Authorization: Bearer {$this->access_token}");
			$data = $this->getData($api,$header);
			$reviewsIterator = new doubanReviewsIterator($data);
			$reviews = $reviewsIterator->findReviews();
			return $reviews;
		}
		return null;
	}
	public function subjectId($subjectID)
	{
		if(!empty($subjectID))
		{
			$api = strtr($this->movieApi, array('{subjectID}'=>$subjectID));
			$data = $this->getData($api);
			$searchIterator = new doubanSearchIterator($data);
			$moviedata = $searchIterator->findMovie();
			return $moviedata;
		}
		return null;
	}
	public function movie($params, $q='', $tag='', $start=0, $max=10)
	{
		
		$searchIterator = $this->search($q,$tag, $start, $max);
		$api = $searchIterator->findMovieApi($params);
		if(!empty($api))
		{
			$data = $this->getData($api);
			$searchIterator = new doubanSearchIterator($data);
			$moviedata = $searchIterator->findMovie();
			return $moviedata;
		}
		return null;
	}
}
class doubanIterator  
{
	public $error = '';
	public function loadxml($xml)
	{
		libxml_use_internal_errors(true);
		$xmlobj = simplexml_load_string($xml);
		
		if(empty($xmlobj))
		{
			$error = libxml_get_errors();
			libxml_clear_errors();
			$message = "";
			foreach($error as $k => $v) {
				$message .= "line:" . $v->line . "\tcolumn" . $v->column . "\tmessage:" . $v->message;
			}
			$this->error = $message;
			return null;
		}
		$xmlobj->registerXPathNamespace('db', 'http://www.douban.com/xmlns/');
		$xmlobj->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
		return $xmlobj;
               
	}
}
class doubanReviewsIterator extends doubanIterator
{
	public $data = null;
	public function __construct($data)
	{
		$this->data = $this->loadxml($data);
	}
	public function findReviews()
	{
		if(empty($this->data)) return array();
		$entry = $this->data->entry;
		$reviews = array();
		foreach($entry as $key => $value)
		{
			
			$comments = $value->xpath("db:comments");
			$useless = $value->xpath("db:useless");
			$votes = $value->xpath("db:votes");
			$rating = $value->xpath("gd:rating");
			$rate = $comment = $less = $vote = 0;
			if(!empty($rating))
			{
				$rate = (int)$rating[0]->attributes()->value;
			}
			if(!empty($comments))
			{
				$comment = (int)$comments[0]->attributes()->value;
			}
			if(!empty($useless))
			{
				$less = (int)$useless[0]->attributes()->value;
			}
			if(!empty($votes))
			{
				$vote = (int)$votes[0]->attributes()->value;
			}
			
			if(preg_match('/http:\/\/api\.douban\.com\/review\/(\d+)/', (string)$value->id, $match))
			{
				$reviewid = $match[1];
			}
			$reviews[] = array( 'reviewid'=>$reviewid, 
								'author'=>(string)$value->author->name,
								'title'=>(string)$value->title,
								'published'=>(string)$value->published,
								'updated'=>(string)$value->updated,
								'summary'=>(string)$value->summary,
								'rating'=>$rate,
								'vote'=>$vote,
								'comment'=>$comment,
								'useless'=>$less,
					);

		}
		return $reviews;
	}
}
class doubanSearchIterator extends doubanIterator
{
	public $data = null;
	public function __construct($data)
	{
		$this->data = $this->loadxml($data);
	}
	public function findMovie()
	{
		if(empty($this->data)  || !empty($this->error))
		{
			echo $this->error;
			return null;
		}
		
		
		if($this->data->link->attributes()->rel =='self')
		{
			$selfApi = (string)$this->data->link->attributes()->href;
			if(preg_match('/(\d+)/', $selfApi, $match))
			{
				$data['subjectid'] = $match[1];
			}
		}
		$attribute = $this->data->xpath("db:attribute");
		$tags = $this->data->xpath("db:tag");
		
		foreach($attribute as $k => $attr)
		{
			if($attr->attributes()->lang =='zh_CN')
			{
				$data['title'] = (string)$attr;
			}
			if($attr->attributes()->name =='title')
			{
				$data['title2'] = (string)$attr;
			}
			if($attr->attributes()->name =='year')
			{
				$data['year'] = (string)$attr;
			}
			if($attr->attributes()->name =='country')
			{
				$data['area'] = (string)$attr;
			}
			if($attr->attributes()->name =='director')
			{
				$data['director'] = (string)$attr;
			}
			if($attr->attributes()->name =='movie_type')
			{
				$data['cate'][] = (string)$attr;
			}
			if($attr->attributes()->name =='cast')
			{
				$data['star'][] = (string)$attr;
			}
			if($attr->attributes()->name =='language')
			{
				$data['lang'][] = (string)$attr;
			}
			if($attr->attributes()->name =='imdb')
			{
				if(preg_match('/title\/(.*?)\/$/', (string)$attr, $match))
					$data['imdb'] = $match[1];
			}
			
		}
		if(!isset($data['title']))
		{
			$data['title'] = $data['title2'];
		}
		if(!isset($data['title']))
		{
			$data['title'] = $this->data->title;
		}
		if(!isset($data['director']))
		{
			$data['director'] = (string)$this->data->author->name;
		}
		$links = $this->data->link;
		foreach($links as $l => $link)
		{
			if($link->attributes()->rel == 'image')
			{
				$data['image'] =  (string)$link->attributes()->href;
			}
		}
		$data['summary'] = (string)$this->data->summary;
		foreach($tags as $k => $tag)
		{
			$data['tag'][] = (string)$tag->attributes()->name;
		}
		$rateing = $this->data->xpath("gd:rating");
		if(!empty($rateing))
		$data['rate'] = (string)$rateing[0]->attributes()->average;
		return $data;
	}
	public function findMovieApi($params)
	{
		if(empty($this->data)  || !empty($this->error))
		{
			echo $this->error;
			return null;
		}
		$sametitle = array();
		foreach($this->data as $k => $item)
		{
			if((string)$item->getName() == 'title') continue;
			
			
			if($this->conditionTitleAndStarEq($item, $params) )
			{
				$apihref = $this->getApiHref($item);
				if($apihref) return $apihref;
			}
		}
		foreach($this->data as $k => $item)
		{
			if((string)$item->getName() == 'title') continue;
				
				
			if($this->conditionDirectorEqAndStarEq($item, $params) )
			{
				$apihref = $this->getApiHref($item);
				if($apihref) return $apihref;
			}
		}
		foreach($this->data as $k => $item)
		{
			if((string)$item->getName() == 'title') continue;
		
		
			if($this->conditionTitle($item, $params) )
			{
				$apihref = $this->getApiHref($item);
				if($apihref) return $apihref;
			}
		}
		
		return false;
	}
	public function getApiHref($item)
	{
		$links = $item->link;
		foreach($links as $l => $link)
		{
			if($link->attributes()->rel == 'self')
			{
				return $link->attributes()->href;
			}
		}
		return false;
	}
	/*
	//导演相同 && 明星>=1 && year+2 >= year <= year-2
	public function conditionDirectoreqAndStarRf1AndYearLf2($item, $params)
	{
		$attribute = $item->xpath("db:attribute");
		$xmlattr = array();
		foreach($attribute as $k => $attr)
		{
			if((string)$attr->attributes()->name == 'cast')
			{
				$xmlattr['cast'][] = (string)$attr;
				
			}
			if((string)$attr->attributes()->name == 'director')
			{
				$xmlattr['director'] = (string)$attr;
			}
			if((string)$attr->attributes()->name == 'pubdate')
			{
				if(preg_match('/(\d{4})/', (string)$attr, $match))
				{
					$xmlattr['pubdate'] = $match[1];
				}
			}
		}
		if(!isset($xmlattr['director']))
		{
			$xmlattr['director'] = (string)$item->author->name;
		}
		$director = isset($xmlattr['director'])?$xmlattr['director']:'';
		$cast = isset($xmlattr['cast'])?$xmlattr['cast']:array();
		$pubdate = isset($xmlattr['pubdate'])?$xmlattr['pubdate']:'';
		if(
				$this->isequDirector($xmlattr['director'], $params['director']) &&
			 	$this->isequStar($cast, $params['star'])
			&&  $this->isLf2Year($pubdate , $params['year'])
		)
		{
			return true;
		}
	
		return false;
	}
	*/
	public function conditionDirectorEqAndStarEq($item, $params)
	{
		$attribute = $item->xpath("db:attribute");
		$xmlattr['director'] = $xmlattr['title'] = $xmlattr['cast'] = array();
		foreach($attribute as $k => $attr)
		{
			if((string)$attr->attributes()->name == 'director')
			{
				$xmlattr['director'][] = (string)$this->formateDirector($attr);
			}
			if((string)$attr->attributes()->lang == 'zh_CN')
			{
				$xmlattr['title'] = (string)$attr;
			}
			if((string)$attr->attributes()->name == 'cast')
			{
				$stararr = explode('/', (string)$attr);
				if(!empty($stararr))
				{
					foreach($stararr as $star)
					{
						$xmlattr['cast'][] = trim($star);
					}
				}
	
					
			}
		}
		if(!isset($xmlattr['title']) || empty($xmlattr['title']))
		{
			$xmlattr['title'] = (string)$item->title;
		}
		if(!isset($xmlattr['director']) || empty($xmlattr['director']))
		{
			$authors = $item->author;
			if(!empty($authors))
				foreach($authors as $k => $author)
				{
					$xmlattr['director'][] = $author->name;
				}
		}
		$director = explode("\t", $params['director']);
		$star = explode("\t",$params['star']);
		if($this->isequDirector($xmlattr['director'],$director) &&
				$this->isequStar($xmlattr['cast'], $star))
		{
			return true;
		}
	
		return false;
	}
	public function conditionTitle($item, $params)
	{
		$attribute = $item->xpath("db:attribute");
		$xmlattr['director'] = $xmlattr['title'] = $xmlattr['cast'] = array();
		foreach($attribute as $k => $attr)
		{
			if((string)$attr->attributes()->lang == 'zh_CN')
			{
				$xmlattr['title'] = (string)$attr;
			}
		}
		if(!isset($xmlattr['title']) || empty($xmlattr['title']))
		{
			$xmlattr['title'] = (string)$item->title;
		}
		if($this->isequTitle($xmlattr['title'],$params['title']))
		{
			return true;
		}
		
		return false;
		
	}
	public function conditionTitleAndStarEq($item, $params)
	{
		$attribute = $item->xpath("db:attribute");
		$xmlattr['director'] = $xmlattr['title'] = $xmlattr['cast'] = array();
		foreach($attribute as $k => $attr)
		{
			if((string)$attr->attributes()->name == 'director')
			{
				$xmlattr['director'][] = (string)$this->formateDirector($attr);
			}
			if((string)$attr->attributes()->lang == 'zh_CN')
			{
				$xmlattr['title'] = (string)$attr;
			}
			if((string)$attr->attributes()->name == 'cast')
			{
				$stararr = explode('/', (string)$attr);
				if(!empty($stararr))
				{
					foreach($stararr as $star)
					{
						$xmlattr['cast'][] = trim($star);
					}
				}
				
			
			}
		}
		if(!isset($xmlattr['title']) || empty($xmlattr['title']))
		{
			$xmlattr['title'] = (string)$item->title;
		}
		if(!isset($xmlattr['director']) || empty($xmlattr['director']))
		{
			$authors = $item->author;
			if(!empty($authors))
			foreach($authors as $k => $author)
			{
				$xmlattr['director'][] = $author->name;
			}
		}
		$director = explode("\t", $params['director']);
		$star = explode("\t",$params['star']);
		if(
				$this->isequStar($xmlattr['cast'], $star)&& 
				$this->isequTitle($xmlattr['title'],$params['title']))
		{
			return true;
		}
		
		return false;
	}
	
	/*
	//title相同 ，并且导演相同
	public function conditionTitleAndDirectorEqu($item, $params)
	{
		$attribute = $item->xpath("db:attribute");
		
		foreach($attribute as $k => $attr)
		{
			if((string)$attr->attributes()->name == 'director')
			{
				$xmlattr['director'] = (string)$attr;
			}
			if((string)$attr->attributes()->lang == 'zh_CN')
			{
				$xmlattr['title'] = (string)$attr;
			}
		}
		if(!isset($xmlattr['title']))
		{
			$xmlattr['title'] = (string)$item->title;
		}
		if(!isset($xmlattr['director']))
		{
			$xmlattr['director'] = (string)$item->author->name;
		}
		if($this->isequDirector($xmlattr['director'],$params['director'])
			&& $this->isequTitle($xmlattr['title'],$params['title']))
		{
			return true;
		}
		
		return false;
		
	}
	*/
	public function formateDirector($director)
	{
		if(preg_match('/(.[^\(]*)/', $director, $match))
		{
			return $match[1];
		}
		return $director;
	}
	public function isLf2Year($pubdata, $year)
	{
		if($pubdata == $year || ($pubdata-2 <= $year &&  $year<= $pubdata+2) )
		{
			return true;
		}
		return false;
	}
	public function isequStar($cast, $star)
	{
		$same = array_intersect($cast, $star);
		if(count($same) >=1)
		{
			return true;
		}
		return false;
	}
	public function isequDirector($director, $director2)
	{
		$same = array_intersect($director, $director2);
		if(count($same) >=1)
		{
			return true;
		}
		return false;
	}
	public function isequTitle($title, $title2)
	{
		if($title == $title2)
			return true;
		else 
			return false;
	}
	
}