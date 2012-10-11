<?php
class groupService implements mini_db_unbuffer
{
	public $vtype = 0;
	public $id = 0;
	public $db = null;
	public $isgroup = true;
	
	public $nowtime = 0;
	public function __construct($params = array())
	{
		foreach($params as $key => $value)
		{
			$this->$key = $value;
		}
		$this->db  = mini_db_connection::getHandle();
		
	}
	public function buildVideoSql()
	{
		$where = "where 1=1 ";
		if(!empty($this->id))
		{
			$where .= "and id='{$this->id}'";
		}
		if(!empty($this->vtype))
		{
			$where .= "and vtype='{$this->vtype}'";
		}
		if($this->isgroup)
		{
			$where .= "and vgroupid =''";
		}
		$where .= "and status = 0";
		$sql = "select * from videos  ".$where;
		return $sql;
	}
	
	public function  together()
	{
		$videosql = $this->buildVideoSql();
		$this->db->unbuffer($videosql, $this);
	}
	public function callback($row)
	{
		//is title and siteid exists
		$sql = "select id,vgroupid,siteid from videos where title='{$row['title']}' and vtype='{$row['vtype']}' and  vgroupid !=''";
		$vrow = $this->db->find($sql);
		if(!empty($vrow))
		{
			//如果不是同一个站点 则更新
			if($vrow['siteid'] != $row['siteid'])
			{
				$this->updateVGroup($row,$vrow);
			}
			else
			{
				//同一个站点则标志状态1
				$updatesql = "update videos set status=1 where id ='{$row['id']}'";
				$this->db->query($updatesql);
			}
		}
		else {
			$this->insertVGroup($row);
		}
	}
	public function defaultPlay($videoids, $row)
	{
		if($row['vtype'] == 2 || $row['vtype'] == 3)
		{
			foreach($videoids as $k => $v)
			{
				$videoid = $v['videoid'];
				
				$sql = "select * from videos where id = $videoid";
				$vrow = $this->db->find($sql);
				
				break;
				
			}
			//如果是电视剧动漫，当前级数>默认 设为默认播放
			if($row['nowepisodes'] > $vrow['nowepisodes'])
			{
				$newvideoids[$row['siteid']] = array('playlink'=>$row['playlink'],'videoid'=>$row['id']);
				foreach($videoids as $k => $v)
				{
					$newvideoids[$k] = $v;
				}
			}
			else
			{
				$newvideoids = $videoids;
				$newvideoids[$row['siteid']] = array('playlink'=>$row['playlink'],'videoid'=>$row['id']);
			}
			
			
			
		}
		else
		{
			$newvideoids = $videoids;
			$newvideoids[$row['siteid']] = array('playlink'=>$row['playlink'],'videoid'=>$row['id']);
		}
		return $newvideoids;
	}
	public function updateVGroup($row, $vrow)
	{
		$vgroup = mini_db_model::model("vgroups");
		
		$vgroup = $vgroup->getBypk($vrow['vgroupid']);
		if(empty($vgroup))
		{
			echo $vrow['vgroupid'];
			echo "not exists!";
			return ;
		}
		$videoidsjson = $vgroup->videoids;
		$videoids = json_decode($videoidsjson,true);
		if(!array_key_exists($row['siteid'], $videoids))
		{

			//$videoids[$row['siteid']] = array('playlink'=>$row['playlink'],'videoid'=>$row['id']);
			//如果第一个为sina电视剧则 强制改变图片为别的
// 			foreach($videoids as $k => $v)
// 			{
// 				if($k == 4 && $vgroup->vtype == 2)  
// 					$vgroup->imagelink = $row['imagelink'];
// 				break;
// 			}
			
			if(empty($vgroup->score))
				$vgroup->score = $row['score'];
			
			
			$videoids[$row['siteid']] = array('playlink'=>$row['playlink'],'videoid'=>$row['id']);
			
// 			$videoids = $this->defaultPlay($videoids, $row);
			$updatevideoids = json_encode($videoids);
			$vgroup->videoids = $updatevideoids;
			
			
			
			$relrow['groupid'] = $vgroup->id;
			$relrow['videoid'] = $row['id'];
			$relrow['siteid'] = $row['siteid'];
			$relrow['vtype'] = $row['vtype'];
			$groupvideo = mini_db_model::model("groupvideo");
			$groupvideo->create($relrow);
			mini_db_unitofwork::getHandle()->commit();
			
			$updatesql = "update videos set vgroupid='{$vgroup->id}' where id ='{$row['id']}'";
			$this->db->query($updatesql);
		}
		else
		{
			//同一个站点,在group中已经存在则标志状态1
			$updatesql = "update videos set status=1 where id ='{$row['id']}'";
			$this->db->query($updatesql);
		}
	}
	public function insertVGroup($row)
	{
		
		//抓取豆瓣接口
		if(0) {
			
			//豆瓣api接口
			$doubanApi = new doubanService();
			$data = $doubanApi->movie($row, $row['title']);
		
			$grow['title'] = $row['title'];
			$grow['area'] = isset($data['area']) ? $data['area'] : $row['area'];
			$grow['director'] = isset($data['director']) ? $data['director'] : $row['director'];
			$grow['year'] = isset($data['year']) ? $data['year'] : $row['year'];
			$grow['star'] = isset($data['star']) ? implode("\t", $data['star']) : $row['star'];
			$grow['cate'] = isset($data['cate']) ? implode("\t", $data['cate']) : $row['cate'];
			$grow['summary'] = isset($data['summary']) ? $data['summary'] : $row['summary'];
			$grow['imagelink'] =  $row['imagelink'];
			$grow['doubanimage'] =isset($data['image']) ? $data['image'] : '';
			$grow['tag'] = isset($data['tag']) ?implode("\t", $data['tag']): '';
			$grow['vtype'] = $row['vtype'];
			$grow['rate'] = isset($data['rate']) ? $data['rate']: 0;
			//$grow['imdb'] = isset($data['imdb']) ? $data['imdb']:0;
			
		} else {
			
			
// 			if(time() - $this->nowtime > 30)
// 			{
				
// 				echo "sleep start 10/m ...\r\n";
// 				sleep(60);
// 				$this->nowtime = time();
// 				echo "sleep end 10/m ...\r\n";
// 			}
			
			//豆瓣搜索蜘蛛接口
			$params = array('cookiefile'=>dirname(__FILE__).'/../crontab/spider/douban_spider_cookie.txt');
			$doubanspider = new doubanspiderService($params);
			$data = $doubanspider->searchLikeApi($row);
			if(empty($data))
			{
				file_put_contents($params['cookiefile'], '');
				echo "cookie get bid...\r\n";
			}
			$grow['title'] = $row['title'];
			$grow['area'] = isset($data['area']) ? $data['area'] : $row['area'];
			$grow['director'] = isset($data['director']) ? $data['director'] : $row['director'];
			$grow['year'] = isset($data['year']) ? $data['year'] : $row['year'];
			$grow['star'] = isset($data['star']) ? implode("\t", $data['star']) : $row['star'];
			$grow['cate'] = isset($data['cate']) ? implode("\t", $data['cate']) : $row['cate'];
			$grow['summary'] = isset($data['summary']) ? $data['summary'] : $row['summary'];
			$grow['imagelink'] =  $row['imagelink'];
			$grow['doubanimage'] =isset($data['image']) ? $data['image'] : '';
			$grow['tag'] = isset($data['tag']) ?implode("\t", $data['tag']): '';
			$grow['vtype'] = $row['vtype'];
			$grow['rate'] = isset($data['rate']) ? $data['rate']: 0;
			//$grow['imdb'] = isset($data['imdb']) ? $data['imdb']:0;
		}
		$grow['epsign'] = $row['epsign'];
		$grow['nowepisodes'] = $row['nowepisodes'];
		$grow['allepisodes'] = $row['allepisodes'];
		//创建归并视频 segment.
		$grow['score'] = $row['score'];
		//$grow['doubanid'] =  isset($data['subjectid']) ? $data['subjectid']:0;
		$videoids[$row['siteid']] = array("playlink"=>$row['playlink'],"videoid"=>$row['id']);
		$grow['videoids'] = json_encode($videoids);
		$vgroup = mini_db_model::model("vgroups");
		$grow['ctime'] = $grow['mtime'] = time();
		$vgroup->create($grow);
		$vgroup->catesum = $vgroup->getCateSum();
		$vgroup->areashow = $vgroup->getAreaShow();
		if(isset($data['ext']) && !empty($data['ext']))
		{
			$this->insertDouban($vgroup, $data['ext']);
		}
		
		
		
		//视频系列归并 segment.
		$group_title = $this->formatTitle($grow['title']);
	
		$shorttitle = $group_title['main'];
		$vgroup->shorttitle = $shorttitle;
		
		
		$svgroup = mini_db_model::model("vgroups");
		$gsvgroup = $svgroup->getByShorttitle($shorttitle,$grow['vtype'],$vgroup->id);
		if(!empty($gsvgroup))
		{
			
			if(empty($gsvgroup->seriesid))
			{
				$seriesid = mini_tool_generator::getInstance()->getNextID();
				
				$groupseries = mini_db_model::model("groupseries");
				$srow = array();
				$srow['groupid'] = $gsvgroup->id;
				$srow['seriesid'] = $seriesid;
				$groupseries->create($srow);
				$gsvgroup->seriesid = $seriesid;
			}
			else
			{
				$seriesid = $gsvgroup->seriesid;
			}
				
		
			$groupseries = mini_db_model::model("groupseries");
			$srow = array();
			$srow['groupid'] = $vgroup->id;
			$srow['seriesid'] = $seriesid;
			$groupseries->create($srow);
			$vgroup->seriesid = $seriesid;
			
		}
		
		//明星 导演 关系表 segment.
		$pstars = explode("\t", $grow['star']);
		
		if(!empty($pstars))
			foreach($pstars as $k => $pvalue)
		{
			$pstar = trim($pvalue);
			if(empty($pstar)) continue;
			$vpeoples = mini_db_model::model("vpeoples");
			$vpeople = $vpeoples->getByName(array(':name'=>$pstar));
			if(empty($vpeople))
			{	$prow = array();
				$prow['name'] = $pstar;
				$vpeople = mini_db_model::model("vpeoples");
				$vpeople->create($prow);
				$gprow = array();
				$grouppeople = mini_db_model::model("grouppeople");
				$gprow['groupid'] = $vgroup->id;
				$gprow['peopleid'] = $vpeople->id;
				$gprow['type'] = 2;
				$grouppeople->create($gprow);
			}
			else
			{
				$grouppeoples = mini_db_model::model("grouppeople");
				$grouppeople = $grouppeoples->getByGroupid(array(':groupid'=>$vgroup->id,':peopleid'=>$vpeople->id));
				if(empty($grouppeople))
				{
					$gprow = array();
					$grouppeople = mini_db_model::model("grouppeople");
					$gprow['groupid'] = $vgroup->id;
					$gprow['peopleid'] = $vpeople->id;
					$gprow['type'] = 2;
					$grouppeople->create($gprow);
				}
			}
		}
		
		
		
		//360 rate segment.
// 		$v360api = new v360Service();
// 		$v360row = $v360api->search($vgroup);
// 		if($v360row)
// 		{
// 			$vgroup->v360image = $v360row['image'];
// 			if(empty($vgroup->rate) && isset($v360row['score']))
// 			{
// 				$vgroup->rate = $v360row['score'];
// 			}
// 		}
		
		
		//group video 关系表 segment.
		$relrow['groupid'] = $vgroup->id;
		$relrow['videoid'] = $row['id'];
		$relrow['siteid'] = $row['siteid'];
		$relrow['vtype'] = $row['vtype'];
		$groupvideo = mini_db_model::model("groupvideo");
		$groupvideo->create($relrow);
		
		//更新video groupid 字段
		$updatesql = "update videos set vgroupid='{$vgroup->id}' where id ='{$row['id']}'";
		$this->db->query($updatesql);
		mini_db_unitofwork::getHandle()->commit();
	}
	
	public function insertDouban($vgroup,$drow)
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
			$row['groupid'] = $vgroup->id;
		
			$douban->create($row);
			if(!empty($drow['pic']))
				$vgroup->doubanimage = $drow['pic'];
			if(!empty($drow['rate']))
				$vgroup->rate = $row['rate'];
		
			$vgroup->doubanid = $row['doubanid'];
		
		
			if(isset($drow['comment']) && !empty($drow['comment']))
			{
				foreach($drow['comment'] as $c => $vv)
				{
					$crow = array();
					$reviews = mini_db_model::model("reviews");
					$crow['title'] = $vv['title'];
					$crow['groupid'] = $vgroup->id;
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
			echo $vgroup->id." hit..\r\n";
		} else {
			echo $vgroup->id." no hit..\r\n";
		}
	}
	public function replaceTitle($title)
	{
		$search = array('(',')',':','Ⅰ','Ⅱ','Ⅲ','Ⅳ');
		$replace = array('（','）','：','1','2','3','4');
		$title = str_replace($search, $replace, $title);
		return $title;
	}
	public function formatTitle($title)
	{
		$title = $this->replaceTitle($title);
		
		$patterns = array(
							//'性感尤物 99版' '花样年华-国语版' '镜湖幻影-上季'
						1=>	"/(.*?)[\s|-](.*?(版|部|季|集))$/u", 
							//黑色大丽花（国语版）
						2=>	"/(.*?)（(.*)）$/u",
							//'英雄本色3'
						3=>	"/(.*?)(\d+)$/u",
							//喜羊羊之开心闯龙年
						4=>	"/(.*?)[：|―](.*?)$/u",
						5=>	"/(.*?)-(.*?)$/u",
						6=>	"/(.*?)之(.*?)$/u",
						);
		$title_group = array();
		foreach($patterns as $k=>$pattern)
		{
			if(preg_match($pattern, $title, $match))
			{
				if($k == 6 && mb_strlen($match[2]) <=3) continue;
				$title_group['main'] = trim($match[1]);
				$title_group['ext'] = trim($match[2]);
				break;
			}
		}
		if(empty($title_group['main']))
		{
			$title_group['main'] = trim($title);
			if(isset($title_group['ext']))
			$title_group['ext'] = trim($title_group['ext']);
		}
		else
		{
			$twotitle = $this->formatTitle($title_group['main']);
			$title_group['main'] = trim($twotitle['main']);
			if(isset($twotitle['ext']))
			$title_group['ext'] .= trim($twotitle['ext']);
		}
		return 	$title_group;
	}
}
