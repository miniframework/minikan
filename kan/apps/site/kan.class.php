<?php
class kanController extends mini_web_controller
{
	public function doMovie()
	{
		$cate = $this->request->get("cate");
		$area = $this->request->get("area");
		$year = $this->request->get("year");
		$star = $this->request->get("star");
		$order = $this->request->get("order");
		$searchRow['cate'] = $cate;
		$searchRow['area'] = $area;
		$searchRow['year'] = $year;
		$searchRow['star'] = htmlspecialchars($star);
		if(empty($order)) $order = 1;
		$searchRow['order'] = $order;
		$searchRow['vtype'] = 1;
		$vgroup = $this->model('vgroups');
		$url = $this->buildUrl($searchRow,'movie');
		$page = $vgroup->page(array("request"=>$this->request, "route"=>$this->route, "url"=>$url));
		$models = $vgroup->search($searchRow);
		 
		
		//$models = $vgroup->getList();
		$video = $this->model("videos");
		 
		$this->view->search = $searchRow;
		$this->view->page = $page;
		$this->view->models = $models;
		$this->view->video = $video;
		$this->view->vgroup = $vgroup;
	}
	public function doTv()
	{
		$cate = $this->request->get("cate");
		$area = $this->request->get("area");
		$year = $this->request->get("year");
		$star = $this->request->get("star");
		$order = $this->request->get("order");
		$searchRow['cate'] = $cate;
		$searchRow['area'] = $area;
		$searchRow['year'] = $year;
		$searchRow['star'] = htmlspecialchars($star);
		if(empty($order)) $order = 1;
		$searchRow['order'] = $order;
		$searchRow['vtype'] = 2;
		$vgroup = $this->model('vgroups');
		$url = $this->buildUrl($searchRow,'tv');
		$page = $vgroup->page(array("request"=>$this->request, "route"=>$this->route, "url"=>$url));
		$models = $vgroup->search($searchRow);
			
			
		
		$video = $this->model("videos");
			
		$this->view->search = $searchRow;
		$this->view->page = $page;
		$this->view->models = $models;
		$this->view->video = $video;
		$this->view->vgroup = $vgroup;
	}
	public function doCartoon()
	{
		$cate = $this->request->get("cate");
		$area = $this->request->get("area");
		$year = $this->request->get("year");
		$order = $this->request->get("order");
		$searchRow['cate'] = $cate;
		$searchRow['area'] = $area;
		$searchRow['year'] = $year;
		if(empty($order)) $order = 1;
		$searchRow['order'] = $order;
		$searchRow['vtype'] = 3;
		$vgroup = $this->model('vgroups');
		$url = $this->buildUrl($searchRow,'cartoon');
		$page = $vgroup->page(array("request"=>$this->request, "route"=>$this->route, "url"=>$url));
		$models = $vgroup->search($searchRow);
			
			
	
		$video = $this->model("videos");
			
		$this->view->search = $searchRow;
		$this->view->page = $page;
		$this->view->models = $models;
		$this->view->video = $video;
		$this->view->vgroup = $vgroup;
	}
	public function doPlayer()
	{
		$id = $this->request->get("id");
		$autoplay = $this->request->get("autoplay");
		$playsite = $this->request->get("site");
		$playepisode = $this->request->get("episode");
		$vgroup = $this->model('vgroups');
		$video = $this->model('videos');
	
		if(empty($id))
		{
			$this->jumperror();
		}
		
		$vgroup = $vgroup->getByPk($id);
		if($vgroup === null)
		{
			$message="not find vgroup getByPk|doPlayer.";
			$this->logger->log($message ,mini_log_logger::LEVEL_TRACE ,"kanapp");
			$this->logger->flush();
			$this->jumperror();
		}
		
		
	
		
		//是否自动播放
		if(!empty($autoplay))
		{
			if(!empty($playsite) && array_key_exists($playsite, $video->siteidMap())
				&& $vgroup->isExistsPlayer($playsite)
			) {
				$this->view->autoplay = true;
				$this->view->playsite = $playsite;
				
			} else if($vgroup->isExistsPlayer($vgroup->getDefaultSiteid())) {
				
				$this->view->autoplay = true;
				$this->view->playsite = $vgroup->getDefaultSiteid();
			}
			else
			{
				$this->view->autoplay = false;
			}
			
			if($vgroup->isMovie()) {
				$this->view->playepisode = 0;
			} else if($vgroup->isTv() || $vgroup->isCartoon()) {
				
				if(!empty($playepisode) && is_numeric($playepisode))
				{
					$this->view->playepisode = $playepisode;
				} else {
					$this->view->playepisode = 1;
				}
			}
		}
		else
		{
			$this->view->autoplay = false;
		}
		
		
		
		
		
		//评论
		$review = $this->model('reviews');
		$reviews = $review->getByGroupid(array(':groupid'=>$vgroup->id));
		
		$this->view->reviews = $reviews;
		$this->view->video = $video;
		$this->view->vgroup = $vgroup;
	}
	public function doSearch()
	{
		$keyword = $this->request->get("q");
		$model = $this->model("vgroups");
		$url = array("site","kan","search",array(),array("q"=>$keyword));
		$page = $model->page(array("request"=>$this->request, "route"=>$this->route, "url"=>$url,"pagesize"=>10));
		$vgroups = $model->searchByKeyword($keyword);
		$model = $this->model("vgroups");
		$movietop10 = $model->getTop10(2);
		$this->view->keyword = htmlspecialchars($keyword);
		$this->view->movietop10 = $movietop10;
		$this->view->vgroups = $vgroups;
		$this->view->page = $page;
	}
	private function buildUrl($search,$action)
	{
		$url = array("site","kan",$action,array(),array('cate'=>$search['cate'],'area'=>$search['area'],'year'=>$search['year'],"order"=>$search['order']));
		return $url;
	}
}