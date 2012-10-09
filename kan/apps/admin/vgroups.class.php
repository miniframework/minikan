<?php
class vgroupsController extends mini_web_controller
{
	public function perinit()
	{
		$firsterror = $this->request->get("firsterror");
		$this->view->firsterror = $firsterror;
	}
	public function doList()
	{
        $model = $this->model('vgroups');
        
        $searchrow = array("id"=>$this->request->get("id"), "title"=>$this->request->get("title"),
        		"ishidden"=>$this->request->get("ishidden"),
        		"vtype"=>$this->request->get("vtype")
        		);
        $this->view->searchrow = $searchrow;
        
		$page = $model->page(array("request"=>$this->request, "route"=>$this->route,"url"=>array("admin","vgroups","list",array(),$searchrow)));
		$this->view->page = $page;
		
		
		$models = $model->search4SearchRow($searchrow);
		$this->view->models = $models;
		
		
		
		$video = $this->model("videos");
		$this->view->video = $video;
	}
	public function doMergin()
	{
		$fromid = $this->request->get("fromid");
		$toid = $this->request->get("toid");

		if(empty($fromid) || empty($toid))
		{
			$message = "fromid 或者 toid 不能为空.";
			$jumpurl = $this->route->createUrl("admin","vgroups", "list",array("firsterror"=>$message,"ishidden"=>1));
			$this->jump($jumpurl);
		}
		
		$fromgroup = $this->model('vgroups')->getByPk($fromid);
		$togroup = $this->model('vgroups')->getByPk($toid);
		
		if(empty($fromgroup) || empty($togroup))
		{
			$message = "fromid 或者 toid 聚合视频没找到.";
			$jumpurl = $this->route->createUrl("admin","vgroups", "list",array("firsterror"=>$message,"ishidden"=>1));
			$this->jump($jumpurl);
		}
		$db  = mini_db_connection::getHandle();
		$fromvideoids = $fromgroup->getVideoids();
		$tovideoids = $togroup->getVideoids();
		foreach($fromvideoids as $fsiteid => $from)
		{
			
			if(array_key_exists($fsiteid, $tovideoids))
			{
				//已经在togroup中
				
				if(!empty($from['videoid']))
				{
					$video = $this->model('videos')->getByPk($from['videoid']);
					if(!empty($video))
					{
						//更新groupvideo表
						$video->vgroupid = 0;
						$video->status = 1;
						$sql = "delete from groupvideo  where videoid={$from['videoid']}";
						$db->query($sql);
					}
						
				}
				
			}
			else 
			{
				//未在togroup中,归并
				$tovideoids[$fsiteid] = $from;
				
				if(!empty($from['videoid']))
				{
					$video = $this->model('videos')->getByPk($from['videoid']);
					if(!empty($video))
					{
						//更新groupvideo表
						$video->vgroupid = $toid;
						$sql = "update  groupvideo set groupid={$toid} where videoid={$from['videoid']}";
						$db->query($sql);
					}
					
				}
			}
		}
		$togroup->videoids = json_encode($tovideoids);
// 		//删除与vgroup有关系的表
// 		$sql = "delete from grouppeople  where groupid={$fromid}";
// 		$db->query($sql);
// 		$sql = "delete from doubans  where groupid={$fromid}";
// 		$db->query($sql);
// 		$sql = "delete from reviews  where groupid={$fromid}";
// 		$db->query($sql);
// 		$fromgroup->delete();
		$this->delete($fromid);
		$jumpurl = $this->route->createUrl("admin","vgroups", "list",array("ishidden"=>1,"id"=>$toid));
		$this->response->setRedirect($jumpurl);
		
	}
	public function doAddview()
	{

	}
	public function doAdd()
	{
		$model = $this->model('vgroups')->createByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","vgroups", "list");
		$this->response->setRedirect($jumpurl);
	}
	public function doModifyview()
	{
		$id = $this->request->get("id");
		$model = $this->model("vgroups")->getByPk($id);
		if($model === null)
		{
			$this->error("error");
		}
		$this->view->model = $model;
		
		$video = $this->model("videos");
		$this->view->video = $video;
	}
	public function doModify()
	{
		
		$this->request->set("star",$this->changeImplode($this->request->get("star"))) ;
		$this->request->set("cate",$this->changeImplode($this->request->get("cate")));
		$this->request->set("director",$this->changeImplode($this->request->get("director")));
		
		$model = $this->model("vgroups")->setByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","vgroups", "list");
		$this->response->setRedirect($jumpurl);
	}
	public function changeImplode($str)
	{
		return str_replace(";","\t", $str);
	}
	public function doDelete()
	{
		$deletePk = $this->request->get("delete");
		
		if(is_array($deletePk))
		{
			foreach($deletePk as $pk)
			{
				
				$this->delete($pk);
			}
		}
		else 
		{
			$this->delete($deletePk);
		}
		$jumpurl = $this->route->createUrl("admin","vgroups", "list");
		$this->response->setRedirect($jumpurl);
	}
	public function doDoubanidview()
	{
		$id = $this->request->get("id");
		$this->view->vgroupid = $id;
	}
	public function doHotset()
	{
		$id = $this->request->get("id");
		$firhot = $this->request->get("firhot");
		$midhot = $this->request->get("midhot");
		$endhot = $this->request->get("endhot");
		$vgroup = $this->model("vgroups")->getByPk($id);
		$vgroup->combineHot($firhot,$midhot,$endhot);
		
		$this->closeRender();
	}
	public function doDoubanid()
	{
		$id = $this->request->get("id");
		$subjectid = $this->request->get("subjectid");
		if(empty($subjectid))
		{
			echo "empty subjectid";
			exit;
		}
		$vgroup = $this->model("vgroups")->getByPk($id);
		if($vgroup === null)
		{
			$this->error("error");
		}
		if(0){
			$douban = new doubanService();
			$data = $douban->subjectId($subjectid);
			if(empty($data)) 
			{
				echo "get douban data empty!";
				exit;
			}
			if(isset($data['title'])) 
				$vgroup->title = $data['title'];
			if(isset($data['area']))
				$vgroup->area = $data['area'];
			if(isset($data['director']))
				$vgroup->director = $data['director'];
			if(isset($data['year']))
				$vgroup->year = $data['year'];
			if(isset($data['star']))
				$vgroup->star = implode("\t",$data['star']);
			if(isset($data['cate']))
				$vgroup->cate = implode("\t",$data['cate']);
			if(isset($data['summary']))
				$vgroup->summary = $data['summary'];
			if(isset($data['image']))
				$vgroup->doubanimage = $data['image'];
			if(isset($data['tag']))
				$vgroup->tag = implode("\t",$data['tag']);
			if(isset($data['rate']))
				$vgroup->rate = $data['rate'];
			if(isset($data['imdb']))
				$vgroup->imdb = $data['imdb'];
			if(isset($data['subjectid']))
				$vgroup->doubanid = $data['subjectid'];
			
		} else {
			
			$doubanSpider = new doubanspiderService();
			$data = $doubanSpider->subjectId($subjectid);
			if(empty($data))
			{
				echo "get douban data empty!";
				exit;
			}
			
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
// 			if(isset($data['imdb'][0]))
// 				$rdata['imdb'] = $data['imdb'][0];
			$rdata['rate'] = $data['rate'];
			$rdata['tag'] = $data['tag'];
			if(isset($data['pubdate'][0]))
			{
				if(preg_match('/(\d{4})/', $data['pubdate'][0], $match))
				{
					$rdata['year'] = $match[1];
				}
			}
			
			if(isset($rdata['area']))
				$vgroup->area = $rdata['area'];
			if(isset($rdata['director']))
				$vgroup->director = $rdata['director'];
			if(isset($rdata['year']))
				$vgroup->year = $rdata['year'];
			if(isset($rdata['star']))
				$vgroup->star = implode("\t",$rdata['star']);
			if(isset($rdata['cate']))
				$vgroup->cate = implode("\t",$rdata['cate']);
			if(isset($rdata['summary']))
				$vgroup->summary = $rdata['summary'];
			if(isset($rdata['image']))
				$vgroup->doubanimage = $rdata['image'];
			if(isset($rdata['tag']))
				$vgroup->tag = implode("\t",$rdata['tag']);
			if(isset($rdata['rate']))
				$vgroup->rate = $rdata['rate'];
			if(isset($rdata['imdb']))
				$vgroup->imdb = $rdata['imdb'];
			if(isset($rdata['subjectid']))
				$vgroup->doubanid = $rdata['subjectid'];
			
			
			$groupService = new groupService();
			
			$groupService->insertDouban($vgroup, $data);
		}
		$vgroup->catesum = $vgroup->getCateSum();
		$vgroup->areashow = $vgroup->getAreaShow();
		
		$dgrouppeople = mini_db_model::model("grouppeople");
		$dgrouppeoples = $dgrouppeople->getByGroupids(array(':groupid'=>$vgroup->id));
		if(!empty($dgrouppeoples))
			foreach($dgrouppeoples as $k => $dgp)
		{
			$dgp->delete();
		}
		
		
		$pstars = $data['star'];
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
		echo "get douban data success!";		
		$this->closeRender();
	}
	public function doDelvideo()
	{
		$id = $this->request->get("id");
		$siteid = $this->request->get("siteid");
		$vgroup = $this->model("vgroups")->getByPk($id);
		
		
		if(empty($vgroup))
		{
			$message = "没有找到vgroup.";
			$jumpurl = $this->route->createUrl("admin","vgroups", "modifyview",array("id"=>$id,"firsterror"=>$message));
			$this->jump($jumpurl);
		}
		$videoids = $vgroup->getVideoids();
		if(count($videoids) == 1 )
		{
			$message = "视频只有一个，请删除聚合视频.";
			$jumpurl = $this->route->createUrl("admin","vgroups", "modifyview",array("id"=>$id,"firsterror"=>$message));
			$this->jump($jumpurl);
		}
		foreach($videoids as $vsiteid => $video)
		{
			if($vsiteid == $siteid)
			{
				if(isset($video['videoid']) && !empty($video['videoid']))
				{
					//删除关系表
					$groupvideo = $this->model("groupvideo")->getByVideoid(array(":videoid"=>$video['videoid']));
					
					if(!empty($groupvideo))
					{
						$groupvideo->delete();
					}
					/*
					//删除聚集
					$db  = mini_db_connection::getHandle();
					$sql = "delete from episodes where videoid={$video['videoid']}";
					$db->query($sql);
					//删除视频
					$videos = $this->model("videos")->getByPk($video['videoid']);
					if(!empty($videos))
						$videos->delete();
					*/
					$videos = $this->model("videos")->getByPk($video['videoid']);
					$videos->status = 1;
					$videos->vgroupid = 0;
				}
				$delsiteid = $siteid;
				
				break;
			}
		}
		if(isset($delsiteid))
			unset($videoids[$delsiteid]);
		
		$vgroup->videoids = json_encode($videoids);
		$jumpurl = $this->route->createUrl("admin", "vgroups", "modifyview",array("id"=>$id));
		$this->response->setRedirect($jumpurl);
	}
	private function error($message)
	{
		
		$jumpurl = $this->route->createUrl("admin","vgroups", "list",array("firsterror"=>$message));
		$this->jump($jumpurl);
	}
	 
	private function delete($pk)
	{
		$model = $this->model("vgroups")->getByPk($pk);
		if($model === null)
		{
			$this->error("error");
		}
		$db  = mini_db_connection::getHandle();
		$videoids = $model->getVideoids();
		foreach($videoids as $vsiteid => $video)
		{
				if(isset($video['videoid']) && !empty($video['videoid']))
				{
					//删除关系表
					$groupvideo = $this->model("groupvideo")->getByVideoid(array(":videoid"=>$video['videoid']));
						
					if(!empty($groupvideo))
					{
						$groupvideo->delete();
					}
					$videos = $this->model("videos")->getByPk($video['videoid']);
					$videos->status = 1;
					$videos->vgroupid = 0;
				}
		}
		$del_grouppeople_sql = "delete from grouppeople where groupid=$pk";
		$db->query($del_grouppeople_sql);
		
		$del_doubans_sql = "delete from doubans where groupid=$pk";
		$db->query($del_doubans_sql);
		
		$del_reviews_sql = "delete from reviews where groupid=$pk";
		$db->query($del_reviews_sql);
		
		$del_groupseries_sql = "delete from groupseries where groupid=$pk";
		$db->query($del_groupseries_sql);
		
		$del_recommends_sql = "delete from recommends where groupid=$pk";
		$db->query($del_recommends_sql);
		
		
		$model->delete();
	}
	
}