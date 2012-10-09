<?php
class videosController extends mini_web_controller
{
	public function perinit()
	{
		$firsterror = $this->request->get("firsterror");
		$this->view->firsterror = $firsterror;
	}
	public function doList()
	{
        $model = $this->model("videos");
        $searchrow = array("id"=>$this->request->get("id"), "title"=>$this->request->get("title"),
        		"ishidden"=>$this->request->get("ishidden"),
        		"vtype"=>$this->request->get("vtype"),
        		"status"=>$this->request->get("status")
        );
        $this->view->searchrow = $searchrow;
        $page = $model->page(array("request"=>$this->request, "route"=>$this->route,
        					 "url"=>array("admin","videos","list",array(),$searchrow)));
        $this->view->page = $page;
       	$models = $model->search4SearchRow($searchrow);
		$this->view->models = $models;
	}
	public function doAddview()
	{
		$model = $this->model('videos');
		$this->view->model = $model;
	}
	public function doAdd()
	{
		$model = $this->model('videos')->createByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","videos", "list");
		$this->response->setRedirect($jumpurl);
	}
	public function doModifyview()
	{
		$id = $this->request->get("id");
		$model = $this->model("videos")->getByPk($id);
		if($model === null)
		{
			$this->error("error");
		}
		$this->view->model = $model;
	}
	public function doModify()
	{
		$this->request->set("star",$this->changeImplode($this->request->get("star"))) ;
 		$this->request->set("cate",$this->changeImplode($this->request->get("cate")));
		$this->request->set("director",$this->changeImplode($this->request->get("director")));
		
		$model = $this->model("videos")->setByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","videos", "list");
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
		$jumpurl = $this->route->createUrl("admin","videos", "list");
		$this->response->setRedirect($jumpurl);
	}
	private function error($message)
	{
		
		$jumpurl = $this->route->createUrl("admin","videos", "list",array("firsterror"=>$message));
		$this->jump($jumpurl);
	}
	private function delete($pk)
	{
		$model = $this->model("videos")->getByPk($pk);
		if($model === null)
		{
			$this->error("error");
		}
		$model->delete();
	}
}