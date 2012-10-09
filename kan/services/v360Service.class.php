<?php
class v360Service
{
	public $searchApi = "http://so.v.360.cn/index.php?kw=";
	private $curl = null;
	public function domByCurl($url, $timeout = 10, $retry = 1)
	{
		include_once dirname(__FILE__).'/../libs/simple_html_dom.php';
		$data = $this->curlByUrl($url,$timeout,$retry);
		if(empty($this->curl->error))
		{
			
			$dom = new simple_html_dom();
			$dom->load($data);
			return $dom;
		}
		return ;
	}
	public function curlByUrl($url, $timeout = 10, $retry = 1)
	{
		$this->curl = new mini_tool_curl(array("timeout"=>$timeout));
	
		for($i = 0; $i<=$retry ; $i++)
		{
			$data = $this->curl->get($url);
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
			return ;
		}
		$content_type = $this->curl->infocode['content_type'];
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
	public function search($vgroup)
	{
		$title = $vgroup->title;
		$data = $this->domByCurl($this->searchApi.$title);
		if(!$data)
		{
			return ;
		}
		$searchlist = $data->find(".gsearchlist",0);
		if(empty($searchlist))
		{
			return ;
		}
		$details = $searchlist->find(".detail");
		$star = explode("\t", $vgroup->star);
		$year = $vgroup->year;
		$dom360 = null;
		foreach($details as $k => $detail)
		{
		
			$stardoms = $detail->find(".starring", 0)->find("a");
			$star360 = array();
			foreach($stardoms as $kk => $stardom)
			{
				$star360[]=  $stardom->plaintext;
			}
// 			$yeardom = $detail->find(".years", 0)->find("span",0);
// 			$year360 = $yeardom->innertext;
			//主演 >=1 year= year
			$titledom = $detail->find("h3",0)->find("a",0);
			if(!empty($titledom))
			{
				$title360 = $titledom->plaintext;
			}
			if($this->isRfStar($star360, $star) && $title360 == $title)
			{
				$dom360 = $detail;
				break;
			}
		}
		$row = array();
		if(!empty($dom360))
		{
			$href =  $dom360->find("h3",0)->find("a",0)->href;
			$row = $this->getinfo($href,$vgroup->vtype);
		}
		if(!empty($row))
		{
			return $row;
		}
		
	}
	public function getinfo($url, $vtype)
	{
		echo $url."\r\n";
		$data = $this->domByCurl($url);
		
		if(!$data)
		{
			return ;
		}
		$scoredom = $data->find(".grade-score",0);
		if(!empty($scoredom))
		{
			$row['score'] = $scoredom->plaintext;
		}
		if($vtype == 3)
		{
			$imagedom = $data->find("#poster",0);
		}
		else 
		{
			$imagedom = $data->find(".v-poster",0)->find("img",0);
		}
		if(!empty($imagedom))
		{
			$row['image'] = $imagedom->src;
		}
		return $row;
	}
	public function isRfStar($cast, $star)
	{
		$same = array_intersect($cast, $star);
		if(count($same) >=1)
		{
			return true;
		}
		return false;
	}
	public function isEquYear($pubdata, $year)
	{
		if($pubdata == $year)
		{
			return true;
		}
		return false;
	}
}
