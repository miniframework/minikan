<?php
class catalogsController extends mini_web_controller
{
	public function perinit()
	{
		$firsterror = $this->request->get("firsterror");
		$this->view->firsterror = $firsterror;
	}
	public function doList()
	{
       
        $model = $this->model('catalogs');
		$page = $model->page(array("request"=>$this->request, "route"=>$this->route,"url"=>array("admin","catalogs","list")));
		$this->view->page = $page;
		$models = $model->getList();
		$this->view->models = $models;
	}
	public function doAddview()
	{
		$model = $this->model('catalogs');
		$this->view->model = $model;
	}
	public function doAdd()
	{
		$model = $this->model('catalogs')->createByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","catalogs", "list");
		$this->response->setRedirect($jumpurl);
	}
	public function doModifyview()
	{
		$id = $this->request->get("id");
		$model = $this->model("catalogs")->getByPk($id);
		if($model === null)
		{
			$this->error("error");
		}
		$this->view->model = $model;
	}
	public function doModify()
	{
		$model = $this->model("catalogs")->setByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","catalogs", "list");
		$this->response->setRedirect($jumpurl);
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
		$jumpurl = $this->route->createUrl("admin","catalogs", "list");
		$this->response->setRedirect($jumpurl);
	}
	private function error($message)
	{
		
		$jumpurl = $this->route->createUrl("admin","catalogs", "list",array("firsterror"=>$message));
		$this->jump($jumpurl);
	}
	private function delete($pk)
	{
		$model = $this->model("catalogs")->getByPk($pk);
		if($model === null)
		{
			$this->error("error");
		}
		$model->delete();
	}
}