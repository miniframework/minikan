<?php
class indexController extends mini_web_controller
{
	private function colorCate()
	{
		return array(1=>
						array('爱情'=>'color:red;','动作'=>'color:dodgerBlue;','悬疑'=>'color:green;'),
					 2=>
						array()
					);
	}
    public function doIndex()
    {
    	$vgroup = $this->model("vgroups");
    	$this->view->vgroup = $vgroup;
    	
//     	$main_hot_vgroups = $vgroup->getByRecommendByTypeid(1);
//     	$this->view->main_hot_vgroups = $main_hot_vgroups;
    	
    	$catalogs = $this->model('catalogs');
    	$catalog_moives = $catalogs->getByOgroup(1);
    	$this->view->catalog_moives = $catalog_moives;
    	
    	$catalog_tvs = $catalogs->getByOgroup(2);
    	$this->view->catalog_tvs = $catalog_tvs;
    	
    	$catalog_comics = $catalogs->getByOgroup(3);
    	$this->view->catalog_comics = $catalog_comics;
    	
    	
    	$mv_botton_recs = $catalogs->getByOgroup(4);
    	$this->view->mv_botton_recs = $mv_botton_recs;
    	
    	$co_botton_recs = $catalogs->getByOgroup(5);
    	$this->view->co_botton_recs = $co_botton_recs;
    	
    	$this->view->colorCate = $this->colorCate();
    	
    	
    	$model = $this->model("vgroups");
    	$movietop10 = $model->getTop10(1);
    	$this->view->movietop10 = $movietop10;
    	
    	$tvtop10 = $model->getTop10(2);
    	$this->view->tvtop10 = $tvtop10;
    	
    	$comictop10 = $model->getTop10(3);
    	$this->view->comictop10 = $comictop10;
    }
    public function doHeader()
    {
    	$this->view->hotSearch = $this->getHotSearch();
    	$this->view->parentId = $this->parentId;
    }
    private function getHotSearch()
    {
    	$hot_search_file = mini_base_application::app()->getRunPath()."/data/editor/hot_search.data";
    	$data_content = file_get_contents($hot_search_file);
    	return $data_content;
    }
    public function doMovie()
    {
    	$cate = $this->request->get("cate");
    	$area = $this->request->get("area");
    	$year = $this->request->get("year");
    	$order = $this->request->get("order");
    	$searchRow['cate'] = $cate;
    	$searchRow['area'] = $area;
    	$searchRow['year'] = $year;
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
    	$order = $this->request->get("order");
    	$searchRow['cate'] = $cate;
    	$searchRow['area'] = $area;
    	$searchRow['year'] = $year;
    	$searchRow['order'] = $order;
    	$searchRow['vtype'] = 2;
    	$vgroup = $this->model('vgroups');
    	$url = $this->buildUrl($searchRow,'tv');
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
    private function buildUrl($search,$action)
    {
    	$url = array("site","index",$action,array(),array('cate'=>$search['cate'],'area'=>$search['area'],'year'=>$search['year']));
    	return $url;
    }
    public function doHttpproxy()
    {
    	echo "http://www.baidu.com";
    	
    	exit;
    }
    public function doHttpdata()
    {
    
    	echo "ok";
    	exit;
    }
  
}