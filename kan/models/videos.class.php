<?php 
class videos extends mini_db_model
{
    protected  $table = 'videos';
    protected  $columns = array('id','version','siteid','vtype','vgroupid','status','title','playlink','imagelink','infolink','cate','area','quality','duration','year','star','director','score','summary','epsign','nowepisodes','allepisodes','mtime','ctime');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '视频信息';
    // NOTE: you should only define rules for those attributes that
    public function rules()
    {
        return array(
        		array("siteid,title,playlink","required","on"=>"update"),
        		array("siteid,title","required","on"=>"create")
        		);
    }
    // NOTE:array relational rules            
    public function relations()
    {
        return array(
        			"getByGroupid"=>array(
                	        'hasbelong',
	                        'vgroups',
                	         array('*'),
	                        'vgroupid')
        		);
    }
    // NOTE:user defind select scopes            
    public function scopes()
    {
    	return array(
    			'getList'=>array(
    					'hasmany'=>true,
    			),
    			'getByPlayLink'=>array(
    					'hasmany'=>false,
    					'condition'=>'playlink=:playlink',
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
    	if(!empty($searchrow["vtype"]))
    	{
    		$condition->compare("vtype","=",$searchrow["vtype"]);
    	}
    	if(!empty($searchrow["status"]))
    	{
    		// status-1 for view 1,2,3-> 0, 1, 2  
    		$condition->compare("status","=",$searchrow["status"]-1);
    	}
    	return $this->record->findAll($condition);
    }
    public function siteidMap($key ='')
    {
    	$map = array(1=>"tudou",2=>"youku",3=>"163",4=>"sina",5=>"m1905",6=>"sohu",7=>"pptv",8=>'letv',9=>'qq',10=>'dytt8');
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function getSiteZh($key='')
    {
    	$map = array(1=>"土豆",2=>"优库",3=>"网易",4=>"新浪",5=>"电影网",6=>"搜狐",7=>"PPTV",8=>'乐视',9=>'腾讯',10=>'电影天堂');
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function getIcon($key='')
    {
    	$map = array(1=>"tudou",2=>"youku",3=>"pptv",4=>"sina",5=>"m1905",6=>"sohu",7=>"pptv",8=>"letv",9=>"qq");
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key].".gif";
    	}
    }
    public function vtypeMapFun($key='')
    {
    	$map = array(1=>"Movie",2=>"Teleplay",3=>"Cartoon",4=>"Variety",5=>"Video");
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function vtypeMap($key ='')
    {
    	$map = array(1=>"电影",2=>"电视剧",3=>"动漫",4=>"综艺",5=>"视频");
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function updateByRow($video, $row, $episodes)
    {
    	
    	if($video->epsign == 2)
    	{
    		//如果视频已更新完 则不需要更新
    		return ;
    	}
    	else
    	{
    		//如果原来剧集比新的多 则不更新
    		if($video->nowepisodes >= $row['nowepisodes']) return;
    		
    		//更新视频信息
    		$video->epsign = $row['epsign'];
    		$video->allepisodes = $row['allepisodes'];
    		$video->nowepisodes = $row['nowepisodes'];
    		$video->mtime = time();
    		//如果视频的聚合id不空，则更新聚合的信息
    		if(!empty($video->vgroupid))
    		{
    			$vgroup = $video->getByGroupid();
    			if(!empty($vgroup) && $vgroup->nowepisodes < $row['nowepisodes'])
    			{
    				$vgroup->epsign = $row['epsign'];
    				$vgroup->allepisodes = $row['allepisodes'];
    				$vgroup->nowepisodes = $row['nowepisodes'];
    				$vgroup->mtime = time();
    			}
    		}
    		
    		//删除剧集列表
    		$delid = $video->id;
    		if(!empty($delid))
    		{
    			$delsql = "delete from episodes where videoid='$delid'";
    			$db = $this->record->getConnection();
    			$db->query($delsql);
    			
    			
    		}
    		//增加新的剧集列表
    		if(!empty($episodes))
    		{
    			foreach($episodes->link as $k => $link)
    			{
    				$erow['playlink'] = (string)$link->playlink;
    				$erow['imagelink'] = (string)$link->imagelink;
    				$erow['vtype'] = (string)$video->vtype;
    				$erow['videoid'] = $video->id;
    				$erow['epindex'] = $k;
    				$erow['flv'] = (string)$link->flv;
    				$episode = self::model('episodes');
    				$episode->create($erow);
    			}
    		}
    	}
    }
    public function createByXml($xml)
    {
    	$row = array();
    	$row['siteid'] = (string)$xml->siteid;
    	$row['vtype'] = (string)$xml->vtype;
    	$row['title'] = (string)$xml->title;
    	$row['playlink'] = (string)$xml->playlink;
    	$row['imagelink'] = (string)$xml->imagelink;
    	$row['infolink'] = (string)$xml->infolink;
    	$row['cate'] = (string)$xml->cate;
    	$row['area'] = (string)$xml->area;
    	$row['quality'] = (string)$xml->quality;
    	$row['year'] = (string)$xml->year;
    	$row['star'] = (string)$xml->star;
    	$row['director'] = (string)$xml->director;
    	$row['score'] = (string)$xml->score;
    	$row['duration'] = (string)$xml->duration;
    	$row['summary'] = (string)$xml->summary;
    	$row['ctime'] = $row['mtime']= time();
    	
    	if(empty($row['title']) || empty($row['playlink']) || empty($row['imagelink'])) return ;
    	
    	if((string)$xml->vtype == 1 || (string)$xml->vtype == 2 )  {
    	
    		//if(empty($row['area']) || empty($row['cate']) || empty($row['area']) || empty($row['area'])) return ;
    	}
    	//查看playlink相同的视频是否存在
		$videos = self::model('videos');
		$video = $videos->getByPlayLink(array(":playlink"=>$row['playlink']));
		
		
		
    	if((string)$xml->vtype == 1)
    	{
    		if(!empty($video))
    		{
    			//电影存在则什么也不用干
    			return ;
    		}
    		else 
    		{	
	    		$this->create($row);
	    		$erow['playlink'] = (string)$xml->playlink;
	    		$erow['imagelink'] = (string)$xml->imagelink;
	    		$erow['vtype'] = (string)$xml->vtype;
	    		$erow['epindex'] = 0;
	    		$erow['videoid'] = $this->id;
	    		$erow['flv'] = (string)$xml->flv;
	    		$episode = self::model('episodes');
	    		$episode->create($erow);
    		}
    	}
    	else if((string)$xml->vtype == 2 || (string)$xml->vtype == 3)
    	{
    		$row['allepisodes'] = (string)$xml->allepisodes;
    		$row['nowepisodes'] = (string)$xml->nowepisodes;
    		$row['epsign'] = (string)$xml->epsign;
    		
    		$episodes = $xml->episodes;
    		if(!empty($video))
    		{
    			//电视剧或者动漫则更新
    			 $this->updateByRow($video, $row, $episodes);
    		}
    		else
    		{
	    		$this->create($row);
	    		if(!empty($episodes))
	    		{
	    			foreach($episodes->link as $k => $link)
	    			{
	    				$erow['playlink'] = (string)$link->playlink;
	    				$erow['imagelink'] = (string)$link->imagelink;
	    				$erow['vtype'] = (string)$xml->vtype;
	    				$erow['epindex'] = $k;
	    				$erow['videoid'] = $this->id;
	    				$erow['flv'] = (string)$link->flv;
	    				$episode = self::model('episodes');
	    				$episode->create($erow);
	    			}
	    		}
    		}
    		
    	}
    }
    public function changeExplode($column)
    {
    	return str_replace("\t", ";", $this->$column);
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'siteid'=>'视频来源',
		'vtype'=>'类型',
		'vgroupid'=>'聚合id',
		'status'=>'状态',
		'title'=>'标题',
		'playlink'=>'播放地址',
		'imagelink'=>'图片地址',
		'infolink'=>'封面地址',
		'cate'=>'分类',
		'area'=>'地区',
		'quality'=>'品质',
		'duration'=>'时长',
		'year'=>'年',
		'star'=>'明星',
		'director'=>'导演',
        'score'=>'评分',
		'summary'=>'概要',
        'epsign'=>'级数状态',
		'nowepisodes'=>'更新级数',
		'allepisodes'=>'全部级数',
        'mtime'=>'Mtime',
        'ctime'=>'Ctime');
    }
}
