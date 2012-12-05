<?php 
class vdownloads extends mini_db_model
{
    protected  $table = 'vdownloads';
    protected  $columns = array('id','version','vtype','title','imagelink','doubanimage','alias','year','cate','country','lang','director','star','summary','photo','doubanid','hot','ctime','mtime');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '视频下载';
    // NOTE: you should only define rules for those attributes that
    public function rules()
    {
        return array();
    }
    // NOTE:array relational rules            
    public function relations()
    {
        return array();
    }
    // NOTE:user defind select scopes            
    public function scopes()
    {
    	return array(
    			'getList'=>array(
    					'hasmany'=>true,
    			),
    			'getByTitle'=>array(
    					'hasmany'=>false,
    					'condition'=>'title=:title',
    			)
    	);
    }
    public function search4SearchRow($searchrow)
    {
    	$condition = new mini_db_condition();
    	if(!empty($searchrow["id"]))
    	{
    		$condition->compare("id","=",$searchrow["id"]);
    	}
    	if(!empty($searchrow["title"]))
    	{
    		$condition->addSearchCondition("title",$searchrow["title"]);
    	}
    	$condition->order = "ctime desc";
    	return $this->record->findAll($condition);
    }
    public function getTop10($vtype=1)
    {
    	$condition = new mini_db_condition();
    	$condition->compare("vtype","=", $vtype);
    	$condition->order = "hot desc";
    	$condition->limit = 10;
    	$condition->offset = 0;
    	return $this->record->findAll($condition);
    	 
    }
    public function getCateOne()
    {
    	$cate_arr = explode("\t", $this->cate);
    	if(!empty($cate_arr)) return $cate_arr[0];
    }
    public function search($row)
    {
    	$condition = new mini_db_condition();
    	if(!empty($row['vtype']))
    	{
    		$condition->compare("vtype","=", $row['vtype']);
    	}
    	if(!empty($row['cate']))
    	{
    		$condition->addSearchCondition("cate",  $row['cate']);
    	}
    	if(!empty($row['area']))
    	{
    		$condition->addSearchCondition("country",  $row['area']);
    	}
    	if(!empty($row['year']) )
    	{
    		$condition->compare("year","=", $row['year']);
    	}
    	$condition->order = 'hot desc ,ctime desc';
    	return $this->record->findAll($condition);
    }
    public function getTitle()
    {
    	 
    	return $this->title;
    
    }
    public function getStar()
    {
    	return explode("\t", $this->star);
    }
    public function getCutSummary($len=40)
    {
    	if(!empty($this->summary))
    		return str_replace("　",'',$this->adv_substr(trim($this->summary), $len, true));
    	else
    		return '暂无';
    }
    private function adv_substr($str, $len = 12, $dot = true, $encoding='utf-8')
    {
    	$strlen = mb_strlen($str, $encoding);
    	$substr = mb_substr($str, 0, $len, $encoding);
    	if($strlen > $len && $dot == true)
    	{
    		$substr .= "...";
    	}
    	return $substr;
    
    }
    public function getImageLink()
    {
		if(!empty($this->doubanimage))
		{
			
			return $this->doubanimage;
		}
		else if(!empty($this->imagelink))
    	{
    		return "http://img.store.sogou.com/net/a/04/link?appid=501&url=".$this->imagelink;
    		
    	}
    	 
    }
    public function getBigImageLink()
    {
    	if(!empty($this->doubanimage))
    	{
    		return str_replace('mpic', 'lpic', $this->doubanimage);
    	}
   		else if(!empty($this->imagelink))
    	{
    		return "http://img.store.sogou.com/net/a/04/link?appid=501&url=".$this->imagelink;
    		
    	}
    }
    public function getOneImageLink()
    {
    	if(!empty($this->imagelink))
    	{
    		return "http://img.store.sogou.com/net/a/04/link?appid=501&url=".$this->imagelink;
    	
    	}
    }
    public function cateMap($vtype = 1)
    {
    	if($vtype == 1)
    	{
    		$map = array(
    				1=>"喜剧",
    				2=>"爱情",
    				3=>"动作",
    				4=>"冒险",
    				5=>"战争",
    				6=>"科幻",
    				7=>"奇幻",
    				8=>"剧情",
    				9=>"古装",
    				10=>"历史",
    				11=>"犯罪",
    				12=>"恐怖",
    				13=>"惊悚",
    				14=>"悬疑",
    				15=>"动画",
    				16=>"伦理",
    				17=>"传记",
    				18=>"其他");
    	}
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function areaMap($vtype = 1, $key='')
    {
    	if($vtype==1)
    	{
    		$map = array(1=>"中国", 2=>"美国",3 =>"法国",4=>"日本",5=>'韩国',6=>"香港", 7=>"台湾",8=>"英国");
    	}
    	else if($vtype ==2)
    	{
    		$map = array(1=>"大陆", 2=>"美国",3 =>"韩国",6=>"日本",4=>"香港", 5=>"台湾", 8=>"亚洲",9=>"其他");
    	}
    	else if($vtype==3)
    	{
    		$map = array(1=>"日本",2=>"大陆",3=>"美国", 4=>"其他");
    	}
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function yearMap($key='')
    {
    	$map = array(1=>"2012",2=>"2011",3=>"2010",4=>"2009",5=>"2008",6=>"2007",7=>"2006");
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function createByXml($xml)
    {
    	$row = array();
    	$row['vtype'] = (string)$xml->vtype;
    	$row['title'] = (string)$xml->title;
    	$row['alias'] = (string)$xml->alias;
    	$row['cate'] = (string)$xml->cate;
    	$row['country'] = (string)$xml->country;
    	$row['lang'] = (string)$xml->lang;
    	
    	$row['year'] = (string)$xml->year;
    	$row['star'] = (string)$xml->star;
    	$row['director'] = (string)$xml->director;
    	$row['photo'] = (string)$xml->image;
    	
    	$row['summary'] = (string)$xml->summary;
    	$siteid = (string)$xml->siteid;
    	 
    	 
    	$row['ctime'] = $row['mtime']= time();
    	$photo = explode("\t",(string)$xml->image);
    	if(!empty($photo))
    		$row['imagelink'] = $photo[0];
    	 
    	$vdownloads = self::model('vdownloads');
    	$vdownload = $vdownloads->getByTitle(array(":title"=>$row['title']));
    	$seeds = explode("\t",(string)$xml->seed);
    	if(!empty($vdownload))
    	{
    		
    		foreach($seeds as $k =>$seed)
    		{
    			$vseeds = self::model('vseeds');
    			$vseed = $vseeds->getBySeed(array(":seed"=>$seed));
    			if(empty($vseed)) {
	    			$srow = array();
	    			$srow['downloadid'] = $vdownload->id;
	    			$srow['siteid'] = (string)$xml->siteid;
					$srow['word'] = (string)$xml->word;
					$srow['filesize'] = (string)$xml->filesize;
					$srow['fileformat'] = (string)$xml->fileformat;
					$srow['videoscreen'] = (string)$xml->videoscreen;
					$srow['duration'] = (string)$xml->duration;
					$srow['seed'] = $seed;
					$srow['ctime'] = $srow['mtime'] = time();
					$vseeds = self::model('vseeds');
					$vseeds->create($srow);
					
    			}
    		}
    	}
    	else
    	{
    		$this->create($row);
    		$params = array('cookiefile'=>dirname(__FILE__).'/../crontab/spider/douban_spider_cookie.txt');
    		$doubansearch = new doubanspiderService($params);
    		$drow = $doubansearch->search(array('title'=>$row['title'],'down'=>1));
    		if(!empty($drow))
    		$this->insertDouban($this, $drow);
    		
    		foreach($seeds as $k =>$seed)
    		{
    			$srow = array();
    			$srow['downloadid'] = $this->id;
    			$srow['siteid'] = (string)$xml->siteid;
    			$srow['word'] = (string)$xml->word;
    			$srow['filesize'] = (string)$xml->filesize;
    			$srow['fileformat'] = (string)$xml->fileformat;
    			$srow['videoscreen'] = (string)$xml->videoscreen;
    			$srow['duration'] = (string)$xml->duration;
    			$srow['seed'] = $seed;
    			$srow['ctime'] = $srow['mtime'] = time();
    			$vseeds = self::model('vseeds');
    			$vseeds->create($srow);
    		}
    	}
    	 
    
    }
    public function getSeeds()
    {
    	$vseed = self::model('vseeds');
    	$vseeds = $vseed->getByDownloadids(array(":downloadid"=>$this->id));
    	
    	return $vseeds;
    }
    public function getStarOne()
    {
    	$star_arr =  explode("\t", $this->star);
    	$star_arr_arr = array();
    	if(!empty($star_arr))
    	{
    		if(!empty($star_arr[0]))
    		{
    			$star_arr_arr = explode("....",$star_arr[0]);
    			return $star_arr_arr[0];
    		}
    	}
    }
    public function getSeed()
    {
    	$vseed = self::model('vseeds');
    	$vseed = $vseed->getByDownloadid(array(":downloadid"=>$this->id));
    	 
    	return $vseed;
    }
    public function getPhotos()
    {
    	 $photo_arr = explode("\t", $this->photo);
    	 unset($photo_arr[0]);
    	 return $photo_arr;
    }
    
    public function insertDouban($vdownload,$drow)
    {
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
    		$row['groupid'] = $vdownload->id;
    		$row['gtype'] = 1;
    		$douban->create($row);
    		if(!empty($drow['pic']))
    			$vdownload->doubanimage = $drow['pic'];
    		
    		$vdownload->doubanid = $row['doubanid'];
    
    
    		if(isset($drow['comment']) && !empty($drow['comment']))
    		{
    			foreach($drow['comment'] as $c => $vv)
    			{
    				$crow = array();
    				$reviews = mini_db_model::model("reviews");
    				$crow['title'] = $vv['title'];
    				$crow['groupid'] = $vdownload->id;
    				if(!isset($vv['comment'])) continue;
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
    		
    	} else {
    		
    	}
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
        'version'=>'Version',
		'vtype'=>'类型',
		'title'=>'标题',
		'imagelink'=>'头图',
		'doubanimage'=>'豆瓣图片',
		'alias'=>'别名',
		'year'=>'年',
		'cate'=>'分类',
		'country'=>'国家',
		'lang'=>'语言',
		'director'=>'导演',
		'star'=>'明星',
		'summary'=>'摘要',
		'photo'=>'剧照',
		'doubanid'=>'豆瓣id',
        'hot'=>'热度',
		'ctime'=>'Ctime',
		'mtime'=>'Mtime');
    }
}