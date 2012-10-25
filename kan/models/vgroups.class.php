<?php 
class vgroups extends mini_db_model
{
    protected  $table = 'vgroups';
    protected  $columns = array('id','version','vtype','videoids','title','shorttitle','showtitle','seriesid','imagelink','doubanimage','webimage','showimage','cate','catesum','area','areashow','year','star','director','summary','epsign','nowepisodes','allepisodes','tag','rate','score','hot','imdb','doubanid','mtime','ctime');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '视频聚合';
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
    			'getByVtype'=>array(
    					'hasmany'=>true,
    					'condition'=>'vtype=:vtype',
    					),
    			'getBySeriesid'=>array(
    					'hasmany'=>true,
    					'join'=>"left join groupseries b on b.groupid=t.id where b.seriesid=:seriesid"
    					)
    	);
    }
    public function getSeriesGroup()
    {
    	if(!empty($this->seriesid))
    	{
    		$vgroups = $this->getBySeriesid(array(":seriesid"=>$this->seriesid));
    		return $vgroups;
    	}
    	return array();
    }
    public function getByRecommendByTypeid($typeid)
    {
    	$sql = "select onum,vgroups.* 
    					from 
    						recommends 
    					left join 
    						vgroups 
    					on 
    						recommends.groupid = vgroups.id 
    					where 
    						recommends.typeid=$typeid 
    					order by recommends.onum desc limit 10";
    	return mini_db_record::getAll($sql, array(),'vgroups');
    }
    public function getByShorttitle($shorttitle, $vtype, $groupid)
    {
    	$condition = new mini_db_condition();
    	$condition->compare("shorttitle","=", $shorttitle);
    	$condition->compare("vtype","=", $vtype);
    	$condition->compare("id","!=", $groupid);
    	return $this->record->find($condition);
    }
    public function searchByKeyword($keyword)
    {
    	$condition = new mini_db_condition();
    	$condition->addSearchCondition('title',$keyword, true);
    	return $this->record->findAll($condition);
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
    	return $this->record->findAll($condition);
    }
    public function search($row)
    {
    	$condition = new mini_db_condition();
    	if(!empty($row['cate']) && array_key_exists($row['cate'], $this->cateMap()))
    	{
    		$catelog = pow(2,$row['cate']-1);
    		$condition->condition = "catesum&$catelog =$catelog";
    	}
    	if(!empty($row['area']) && array_key_exists($row['area'], $this->areaMap($row['vtype'])))
    	{
    		$condition->compare("areashow","=", $this->areaMap($row['vtype'], $row['area']));
    	}
    	if(!empty($row['year']) && array_key_exists($row['year'], $this->yearMap()))
    	{
    		$condition->compare("year","=", $this->yearMap($row['year']));
    	}
    	$video = mini_db_model::model("videos");
    	if(!empty($row['vtype']) && array_key_exists($row['vtype'], $video->vtypeMap()))
    	{
    		$condition->compare("vtype","=", $row['vtype']);
    	}
    	if(!empty($row['star']))
    	{
    		$condition->join =  "left join grouppeople 
    								on grouppeople.groupid = t.id 
				    				left join vpeoples 
			    					on  vpeoples.id = grouppeople.`peopleid`";
    		$condition->compare("name","=", $row['star']);
    	}
    	if(empty($row['order']) || $row['order'] == 1)
    	{
    		$condition->order = "hot desc";
    	}
    	else if ($row['order'] == 2)
    	{
    		$condition->order = "rate desc";
    	}
    	else if ($row['order'] == 3)
    	{
    		if($row['vtype'] == 1)
    			$condition->order = "year desc";
    		else 
    			$condition->order = "mtime desc, year desc";	
    	}
    	return $this->record->findAll($condition);
    }
    public function getTop10($vtype=1, $cateid = 0)
    {
    	$condition = new mini_db_condition();
    	if(!empty($cateid))
    	{
    		$catelog = pow(2, $cateid - 1);
    		$condition->condition = "catesum&$catelog =$catelog";
    	}
    	$condition->compare("vtype","=", $vtype);
    	$condition->order = "hot desc";
    	$condition->limit = 10;
    	$condition->offset = 0;
    	return $this->record->findAll($condition);
    	
    }
    public function combineHot($firhot,$midhot,$endhot)
    {
    	
    		$hotnum = "1";
    		
    		if(empty($firhot))
    		{
    			$hotnum .= "00";
    		}
    		else if(strlen($firhot) <=1 ) {
    			$hotnum .= "0".$firhot;
    		}
    		else if(strlen($firhot) <=2 ) {
    			$hotnum .= $firhot;
    		}
    		else {
    			$hotnum .= '99';
    		}
    		if(empty($midhot))
    		{
    			$hotnum .= "00";
    		}
    		else if(strlen($midhot) <=1 ) {
    			$hotnum .= "0".$midhot;
    		}
    		else if(strlen($midhot) <=2 ) {
    			$hotnum .= $midhot;
    		}
    		else {
    			$hotnum .= '99';
    		}
    			
    		if(empty($endhot))
    		{
    			$hotnum .= "0000";
    		}
    		elseif(strlen($endhot) ==4 ) {
    			$hotnum .= $endhot;
    		}
    		else if(strlen($endhot) <4 ) {
    	
    			for($i =0 ;$i<4 - strlen($endhot) ;$i++)
    			{
    			$hotnum .="0";
    				
    			}
    	
    			$hotnum .= $endhot;
    		}
    		else {
    			$hotnum .= '9999';
    		}
    	$this->hot = $hotnum;
    	return $hotnum;
    }
    public function getVideoids()
    {
    	$videoids = json_decode($this->videoids, true);
    	return $videoids;
    }
    public function getPlayer()
    {
    	$videoids = json_decode($this->videoids, true);
    	$player = array();
    	foreach($videoids as $siteid => $video)
    	{
    		
    		if(!isset($video['videoid'])) continue;
    		$videoid = $video['videoid'];
    		$episodes = mini_db_model::model('episodes');
    		if($siteid == 8)
    		{
    			$$episodes = $episodes->getsByVideoidLetv(array(':videoid'=>$videoid));
    		}
    		else {
    			$episodes = $episodes->getsByVideoid(array(':videoid'=>$videoid));
    		}
    		$flv = array();
    		if(!empty($episodes))
    		foreach($episodes as $k => $episode)
    		{
    			$flv[$k]['flv'] = json_decode($episode->flv, true);
    			$flv[$k]['playlink'] = $episode->playlink;
    			$flv[$k]['imagelink'] = $episode->imagelink;
    		}
    		
    		$player[] = array(		 'siteid'=>$siteid,
    								 'videoid'=>$videoid,
    								 'playlink'=>$flv[0]['playlink'],
    							     'flv'=>$flv);
    	}
    	return $player;
    }
    public function isExistsPlayer($siteid)
    {
    	$videoids = json_decode($this->videoids, true);
    	if(isset($videoids[$siteid]) && isset($videoids[$siteid]['videoid']))
    	{
    		$videoid = $videoids[$siteid]['videoid'];
    		$episodes = mini_db_model::model('episodes');
    		$episode = $episodes->getByVideoid(array(':videoid'=>$videoid));
    		if(!empty($episode))
    		{
    			if(!empty($episode->flv)) return true;
    		}
    	}
    	return false;
    }
    public function getDefaultPlaylink()
    {
    	
    	$videoids = json_decode($this->videoids, true);
    	foreach($videoids as $siteid => $video)
    	{
    		if(isset($video['videoid']))
    		{
    			$videoid = $video['videoid'];
    			$episodes = mini_db_model::model('episodes');
    			$episode = $episodes->getByVideoid(array(':videoid'=>$videoid));
    			if(!empty($episode))
    			return $episode->playlink;
    		}
    		
    	}
    	return '';
    	
    }
    public function getDefaultSiteid()
    {
    	$videoids = json_decode($this->videoids, true);
    	foreach($videoids as $siteid => $videoid)
    	{
    		return $siteid;
    		break;
    	}
    }
    public function getImageLink()
    {
    	if(!empty($this->showimage))
    	{
    		return $this->showimage;
    	}
    	else if(!empty($this->doubanimage))
    	{
    		return $this->doubanimage;
    	}
    	else if(!empty($this->webimage))
    	{
    		return $this->webimage;
    	}
    	else if(!empty($this->imagelink))
    	{
    		return $this->imagelink;
    	}
    	
    }
    public function getBigImageLink()
    {
    	if(!empty($this->showimage))
    	{
    		return $this->showimage;
    	}
    	else if(!empty($this->doubanimage))
    	{
    		return str_replace('mpic', 'lpic', $this->doubanimage);
    	}
    	else if(!empty($this->webimage))
    	{
    		return $this->webimage;
    	}
    	else if(!empty($this->imagelink))
    	{
    		return $this->imagelink;
    	}
    }
    public function getShowTitle()
    {
    	if(!empty($this->showtitle))
    	{
    		return $this->showtitle;
    	}
    	
    	return $this->title;
    		
    }
    public function changeExplode($column)
    {
    	return str_replace("\t", ";", $this->$column);
    }
    public function isMovie()
    {
    	if($this->vtype == 1)
    		return true;
    }
    public function isTv()
    {
    	if($this->vtype == 2)
    		return true;
    }
    public function isCartoon()
    {
    	if($this->vtype == 3)
    		return true;
    }
    public function isNewEp()
    {
    	if(date("Y-m-d",$this->mtime) == date("Y-m-d",time()) ||
    	   date("Y-m-d",$this->mtime+24*60*60) >= date("Y-m-d",time()) )
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    public function getEpSignforStr()
    {
    	if($this->epsign == 2)
    	{
    		return "全".$this->nowepisodes."集";
    	}
    	else if($this->epsign == 1)
    	{
    		return "更新到".$this->nowepisodes."集";
    	}
    	else 
    	{
    		return '';
    	}
    }
    public function getEpSignforShortStr()
    {
    	if($this->epsign == 2)
    	{
    		return $this->nowepisodes."集全";
    	}
    	else if($this->epsign == 1)
    	{
    		return "至".$this->nowepisodes."集";
    	}
    	else
    	{
    		return '';
    	}
    }
    public function getShortcomment()
    {
    	if(!empty($this->info))
    	{
    		$info = json_decode($this->info,true);
    		if(!empty($info['shortcomment']))
    		{
    			return $info['shortcomment'];
    		}
    	}
    	else
    	{
    		return '';
    	}
    }
    public function getYourLike()
    {
    	$cate = $this->cate;
    	$year = $this->year;
    	$area = $this->areashow;
    	$vtype = $this->vtype;
    	$cate_arr = explode("\t", $this->cate);
    	$catelist_flip = array_flip($this->getCateList($vtype));
    	
    	$first_num = $two_num = $three_num = 0;
    	$first_groups = $two_groups = $three_groups = array();
    	if(!isset($catelist_flip[$cate_arr[0]]))
    	{
    		$cateid = 1;
    	} else {
    		$cateid = $catelist_flip[$cate_arr[0]];
    	}
    	$catelog = pow(2,$cateid-1);
    	$insql = "where catesum&$catelog =$catelog and id !={$this->id} and vtype='$vtype' limit 5";
    	$first_groups = $this->record->findAllBySql($insql);
    	$first_num = count($first_groups);
    	
		$insql = "where   id !={$this->id} and areashow='$area' and vtype='$vtype' limit 5";
		$two_groups = $this->record->findAllBySql($insql);
		$two_num = count($two_groups);
	
		$insql = "where   id !={$this->id} and  year='$year' and vtype='$vtype' limit 5";
		$three_groups = $this->record->findAllBySql($insql);
		$three_num = count($three_groups);

		if(($first_num+$two_num+$three_num) < 0 && isset($cate_arr[1]))
		{
			$cateid1 = $catelist_flip[$cate_arr[1]];
			$catelog = pow(2,$cateid1-1);
			$insql = "where catesum&$catelog =$catelog and id !={$this->id} and vtype='$vtype' limit 10";
			$first_groups = $this->record->findAllBySql($insql);
			$first_num = count($first_groups);
			 
		}
		else if(($first_num+$two_num+$three_num) < 0)
		{
			$insql = "where   id !={$this->id} and vtype='$vtype' order by rate desc limit 10";
			$first_groups = $this->record->findAllBySql($insql);
			$first_num = count($first_groups);
			
		}
		$yourlikegroups = array();
    	if(!empty($first_num) || !empty($two_num) || !empty($three_num))
    		$yourlikegroups = array_merge($first_groups, $two_groups, $three_groups);
    	if(empty($yourlikegroups)) return array();
    	if(count($yourlikegroups) < 4)
    	{
    		$rands = array_rand($yourlikegroups,count($yourlikegroups));
    	}
    	else
    	{
    		
    		$rands = array_rand($yourlikegroups,4);
    	}
    	foreach($rands as $k => $rand )
    	{
    		$yourlikerandgroups[] = $yourlikegroups[$rand];
    	}
    	return $yourlikerandgroups;
    }
	public function getSameStar()
	{
		$db = mini_db_connection::getHandle();
		$star_arr = explode("\t", $this->star);
		$star_in_str = '';
		if(!empty($star_arr))
		{
			foreach($star_arr as $k => $v)
			{
				$star_in_arr[] = "'".addslashes($v)."'";	
			}
			$star_in_str = implode(',', $star_in_arr);
		}
		$sql = "select distinct g.groupid from  
							grouppeople as g 
						left join 
							vpeoples as p  
						on 
							g.peopleid = p.id
						where 
							p.name in(".$star_in_str.") limit 11";
		
		
		$row = $db->findAll($sql);
		$groupids = array();
		if(!empty($row))
			foreach($row as $k => $gid)
		{
			if($this->id == $gid['groupid']) continue;
			
			$groupids[] = $gid['groupid'];
		}
		if(empty($groupids)) return array();
		$ingroupids = implode(',', $groupids);
		$insql = "where id in ($ingroupids) limit 4";
		$groups = $this->record->findAllBySql($insql);
		return $groups;
	}
	public function getSameDirector()
	{
		$db = mini_db_connection::getHandle();
		$sql = "select distinct g.groupid from
							grouppeople as g
						left join
							vpeoples as p
						on
							g.peopleid = p.id
						where
							p.name ='".addslashes($this->director)."' limit 6";
	
	
		$row = $db->findAll($sql);
		$groupids = array();
		if(!empty($row))
			foreach($row as $k => $gid)
			{
				if($this->id == $gid['groupid']) continue;
					
				$groupids[] = $gid['groupid'];
			}
			if(empty($groupids)) return array();
			$ingroupids = implode(',', $groupids);
			$insql = "where id in ($ingroupids) limit 5";
			$groups = $this->record->findAllBySql($insql);
			return $groups;
	}
	public function getFirstEpisodes()
	{
		$videoids = json_decode($this->videoids, true);
		$firstvideos = array_shift($videoids);
		$videoid = $firstvideos['videoid'];
		$episodes = mini_db_model::model('episodes');
		return $episodes->getsByVideoid(array(':videoid'=>$videoid));
	}
	public function getEpisodes()
	{
		$videoids = $this->videoids;
		$videoids_arr = json_decode($videoids, true);
		$episodelist = array();
		$i = 0 ;
		foreach($videoids_arr as $siteid => $videoid)
		{
			
			$episodes = mini_db_model::model('episodes');
			if($siteid == 8)
			{
				$episodelist[$i]['episodes'] = $episodes->getsByVideoidLetv(array(':videoid'=>$videoid['videoid']));
			}
			else {
				$episodelist[$i]['episodes'] = $episodes->getsByVideoid(array(':videoid'=>$videoid['videoid']));
			}
			$episodelist[$i]['siteid'] = $siteid;
			$episodelist[$i]['videoid']  = $videoid['videoid'];
			$episodelist[$i]['playlink'] = $episodelist[$i]['episodes'][0]->playlink;
			$i++;
		}
		if(count($episodelist) <=1 || $this->vtype==1) return $episodelist;
		
		//冒泡排序 级数多的排前面
		$i =0 ; $j = 0;
		
		for($i=0; $i< count($episodelist); $i++)
		{
			for($j = $i+1; $j<count($episodelist); $j++)
			{
				if(count($episodelist[$i]['episodes']) < count($episodelist[$j]['episodes']))
				{
					$tmp = $episodelist[$j];
					$episodelist[$j] = $episodelist[$i];
					$episodelist[$i] = $tmp;
				}
			}
		}
		
		return $episodelist;
	}
	public function getRate()
	{
		$rate = floatval($this->rate);
		if(!empty($rate))
		{
			return number_format($rate,1);
		}
		else
		{
			return $rate;
		}
	}
	public function getOrder()
	{
		$map = array(1=>"热门",2=>"好评",3=>"最新");
		if(empty($key))
		{
			return $map;
		}
		else {
			return $map[$key];
		}
	}
	public function getVtypezh()
	{
		if($this->vtype==1)
			return "电影";
		else if($this->vtype==2)
			return "电视剧";
		else if($this->vtype ==3)
			return "动漫";
	}
	public function getCutSummary($len=40)
	{
		if(!empty($this->summary))
			return $this->adv_substr($this->summary, $len, true);
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
		
		//old substr ...........................
		/*
		$i = 0;
		$l = 0;
		$c = 0;
		$a = array();
		while ($l < $len) {
			$t = substr($str, $i, 1);
			if (ord($t) >= 224) {
				$c = 3;
				$t = substr($str, $i, $c);
				$l += 2;
			} elseif (ord($t) >= 192) {
				$c = 2;
				$t = substr($str, $i, $c);
				$l += 2;
			} else {
				$c = 1;
				$l++;
			}
			$i += $c;
			if ($l > $len) break;
			$a[] = $t;
		}
		$re = implode('', $a);
		if (substr($str, $i, 1) !== false) {
			array_pop($a);
			($c == 1) and array_pop($a);
			$re = implode('', $a);
			$dot and $re .= '...';
		}
		return $re;
		*/
		//old substr ...........................
	}
	public function getStars($num=4)
	{
		if($num==0)
		{
			return  explode("\t", $this->star);
		}
		else
		{
			$star =  explode("\t", $this->star);
			return array_slice($star, 0,$num);
		}
	}
	public function getCateSum()
	{
		if($this->vtype == 1)
		{
			$mapcate= array(
					"青春"=>"爱情",
					"魔幻"=>"奇幻",
					"武侠"=>"古装",
					"文艺"=>"爱情",
					"搞笑"=>"喜剧",
					"动画短片"=>"动画",
					"黑色幽默"=>"喜剧",
					"情色"=>"伦理",
					"暴力"=>"犯罪");
		}
		else
		{
			$mapcate = array();
		}
		$cate = $this->cate;
		$catearr = explode("\t", $cate);
		$catesum = 0;
		$existcate = array();
		foreach($catearr as $k => $v)
		{
			if(array_key_exists($v, $mapcate))
			{
				$cateone = $mapcate[$v];
			}
			else
			{
				$cateone = $v;
			}
			$catemap = $this->cateMap($this->vtype);
			$flipmap = array_flip($catemap);
			if(!in_array($cateone, $catemap))
			{
				$cateone = "其他";
				
			}
			if(!in_array($cateone, $existcate))
			{
				$key = $flipmap[$cateone];
				$existcate[] = $cateone;
				$catesum += pow(2,($key-1));
			}
		}
		return $catesum;
		
	}
	public function getAreaShow($area='')
	{
		$maparea = array("中国大陆"=>"大陆","中国"=>"大陆","法国"=>"欧洲","丹麦"=>"欧洲","英国"=>"欧洲","德国"=>"欧洲","意大利"=>"欧洲","瑞士"=>"欧洲","印度"=>"亚洲","泰国"=>"亚洲");
		if(empty($area))
		{
			$area = $this->area;
		}
		if(array_key_exists($area, $maparea))
		{
			$areaone = $maparea[$area];
		}
		else
		{
			$areaone = $area;
		}
		$areamap = $this->areaMap($this->vtype);
		if(!in_array($areaone, $areamap))
		{
			$areaone = "其他";
		}
		return $areaone;
	}
	public function areaMap($vtype = 1, $key='')
	{
		if($vtype==1)
		{
			$map = array(1=>"大陆", 2=>"美国",3 =>"韩国",6=>"日本",4=>"香港", 5=>"台湾",7=>"欧洲", 8=>"亚洲",9=>"其他");
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
    public function cateMap($vtype=1, $key='')
    {
    	
    	$map = $this->getCateList($vtype);
		if(empty($key))
		{
			return $map;
		}
		else {
			return $map[$key];
		}
    }
    public function starMap($vtype)
    {
    	if($vtype == 1)
    	{
    		$map = array(	
    						"成龙",
    						"周星驰",
    						"李连杰",
    						"周润发",
    						"林正英",
    						"甄子丹",
    						"刘德华",
    						"舒淇",
    						"任达华",
    						"黄渤",
    						"范冰冰",
    						"孙红雷",
    						"郑伊健");
    		
    						
    	}
    	else if($vtype == 2)
    	{
    		$map = array(	
    				"林心如",
    				"孙俪",
    				"杨幂",
    				"文章",
    				"李小璐",
    				"吴秀波",
    				"海清",
    				"冯绍峰",
    				"蔡少芬",
    				"陈思成",
    				"黄磊",
    				"张嘉译",
    				"陈浩民",
    				"陈楚河",
    				"贾乃亮");
    	}
    	return $map;
    }
    public function getCate2Arr()
    {
    	$catearr = explode("\t", $this->cate);
    	if(!empty($catearr))
    	{
    		return $catearr;
    	}
    	else
    	{
    		return array();
    	}
    }
    public function getCateKey($vtype, $name)
    {

    	$map = $this->getCateList($vtype);
    	 
    	$key = array_search($name, $map);
    	
    	if(empty($key))
    	{
    		$name='其他';
    		$key = array_search($name, $map);
    	}
    	return $key;
    }
    public function getCateList($vtype)
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
    	else if($vtype==2)
    	{
    		$map = array(
    				1=>'偶像',
    				2=>'爱情',
    				3=>'喜剧',
    				4=>'都市',
    				5=>'农村',
    				6=>'古装',
    				7=>'武侠',
    				8=>'神话',
    				9=>'家庭',
    				10=>'历史',
    				11=>'警匪',
    				12=>'科幻',
    				13=>'战争',
    				14=>'悬疑',
    				15=>'谍战',
    				16=>'其他');
    	}
    	else if($vtype == 3)
    	{
    		$map = array(
						1=>'奇幻',
						2=>'热血',
						3=>'社会',
						4=>'校园',
						5=>'战争',
						6=>'爱情',
						7=>'冒险',
						8=>'百合',
						9=>'耽美',
						10=>'搞笑',
						11=>'后宫',
						12=>'机战',
						13=>'竞技',
						14=>'科幻',
						15=>'悬疑',
						16=>'真人',
						17=>'其他');
    	}
    	return $map;
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'vtype'=>'类型',
		'videoids'=>'视频聚合',
		'title'=>'标题',
        'shorttitle'=>'格式化标题',
        'showtitle'=>'显示标题',
        'seriesid'=>'系列id',
		'imagelink'=>'图片地址',
		'doubanimage'=>'douban图片',
        'webimage'=>'其他网站图片',
        'showimage'=>'显示图片',
		'cate'=>'分类',
        'catesum'=>'分类index',
		'area'=>'地区',
		'year'=>'年',
		'star'=>'明星',
		'director'=>'导演',
		'summary'=>'概要',
        'epsign'=>'级数状态',
        'nowepisodes'=>'更新级数',
        'allepisodes'=>'全部级数',
		'tag'=>'TAG',
        'rate'=>'评分',
        'score'=>'分数',
       	'hot'=>'热度',
        'imdb'=>'IMDB',
        'doubanid'=>'豆瓣id',
        'mtime'=>'Mtime',
        'ctime'=>'Ctime');
    }
}
// //youku
// 剧情 动作 喜剧 惊悚 爱情 犯罪 冒险 恐怖 奇幻 科幻 悬疑 动画 战争 历史 传记 武侠 歌舞 纪录片 儿童 戏曲
// //letv
// 剧情 喜剧 动作 爱情 恐怖 动画 战争 惊悚 悬疑 奇幻 犯罪 冒险 科幻 警匪 武侠 灾难 伦理 歌舞 家庭 纪录 历史 短片 传记 体育
// //qiyi
// 动作 喜剧 爱情 惊悚 科幻 恐怖 剧情 战争 枪战 魔幻 青春 灾难 悲剧 艺术 传记 动画 犯罪 伦理 悬疑 运动 其它
// //sina
// 剧情 喜剧 爱情 动作 惊悚 犯罪 恐怖 冒险 家庭 伦理 动画 悬疑 短片 战争 歌舞 传记 历史 古装 运动 武侠 儿童 青春 纪实 科幻 魔幻 黑色幽默
// //360
// 喜剧 爱情 动作 恐怖 科幻 剧情 犯罪 奇幻 战争 悬疑 动画 文艺 伦理 纪录 传记 歌舞 古装 历史 惊悚 其他
// //douban
// 爱情(4892719)	喜剧(3571632)	动画(3182042)	经典(2254343)
// 科幻(2136266)	动作(1977406)	青春(1901368)	剧情(1829755)
// 悬疑(1514301)	惊悚(1086147)	纪录片(900759)	犯罪(863847)
// 励志(857332)	文艺(798604)	搞笑(737741)	恐怖(729494)
// 战争(704836)	魔幻(674827)	短片(663685)	动画短片(547680)
// 黑色幽默(493591)	情色(488310)	传记(406745)	童年(404690)
// 暴力(352906)	音乐(341276)	同志(336963)	感人(333837)
// 黑帮(296095)	女性(282688)	浪漫(267249)	家庭(228497)
// 童话(188650)	史诗(185455)	cult(122489)	震撼(71805)

// 青春=>爱情
// 魔幻=>奇幻
// 武侠=>古装
// 文艺=>爱情
// 搞笑=>喜剧
// 动画短片=>动画
// 黑色幽默=>喜剧
// 情色＝>伦理
// 暴力=>犯罪

// 喜剧 爱情 动作 冒险 战争 科幻 奇幻 剧情 古装 历史 犯罪 恐怖 惊悚 悬疑 动画 伦理 传记



//电视剧
//360
//言情 伦理 喜剧 悬疑 都市 偶像 古装 军事 警匪 历史 武侠 科幻 宫廷 情景 动作 励志 神话 谍战 粤语 其他
//tudou
//喜剧 爱情 神话 动作 古装 悬疑 历史 纪实 战争 偶像 家庭 军事 警匪 其它 剧情 犯罪 动画 惊悚 冒险
//魔幻 科幻 传记 儿童 恐怖 武侠 搞笑 年代 都市 其他 近代 革命 农村 穿越 宫廷 商战 传奇 谍战 现代 生活 言情 时装 音乐 娱乐 时尚
//youku
//不限 古装 武侠 警匪 军事 神话 科幻 悬疑 历史 儿童 农村 都市 家庭 搞笑 偶像 言情 时装
//letv
//全部 剧情 伦理 喜剧 军旅 奇幻 动作 战争 武侠 犯罪 悬疑 偶像 都市 历史 灾难 古装 科幻 情景 生活 情感 家庭 谍战 刑侦
//sogou
//偶像 言情 都市 农村 古装 历史 科幻 军事 悬疑 喜剧 伦理 谍战

//偶像 爱情 都市 农村 古装 武侠 神话 科幻 历史 警匪 科幻 战争 悬疑 喜剧 谍战


//奇幻 热血 社会 校园 战争 爱情  冒险 百合 耽美 搞笑 后宫 机战 竞技 科幻 悬疑  真人 其他 