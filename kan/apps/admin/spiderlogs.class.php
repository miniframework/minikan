<?php
class spiderlogsController extends mini_web_controller
{
	public function perinit()
	{
		$firsterror = $this->request->get("firsterror");
		$this->view->firsterror = $firsterror;
	}
	public function doList()
	{
       
//         $model = $this->model('spiderlogs');
// 		$page = $model->page(array("request"=>$this->request, "route"=>$this->route,"url"=>array("admin","spiderlogs","list")));
// 		$this->view->page = $page;
// 		$models = $model->getList();
// 		$this->view->models = $models;
		
		
		$model = $this->model('spiderlogs');
		
		$searchrow = array("spiderid"=>$this->request->get("spiderid"));
		$this->view->searchrow = $searchrow;
		
		$page = $model->page(array("request"=>$this->request, "route"=>$this->route,"url"=>array("admin","spiderlogs","list",array(),$searchrow)));
		$this->view->page = $page;
		
		
		$models = $model->search4SearchRow($searchrow);
		$this->view->models = $models;
	}
	public function doAddview()
	{

	}
	public function doAdd()
	{
		$model = $this->model('spiderlogs')->createByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","spiderlogs", "list");
		$this->response->setRedirect($jumpurl);
	}
	public function doModifyview()
	{
		$id = $this->request->get("id");
		$model = $this->model("spiderlogs")->getByPk($id);
		if($model === null)
		{
			$this->error("error");
		}
		$this->view->model = $model;
	}
	public function doModify()
	{
		$model = $this->model("spiderlogs")->setByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","spiderlogs", "list");
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
		$jumpurl = $this->route->createUrl("admin","spiderlogs", "list");
		$this->response->setRedirect($jumpurl);
	}
	private function error($message)
	{
		
		$jumpurl = $this->route->createUrl("admin","spiderlogs", "list",array("firsterror"=>$message));
		$this->jump($jumpurl);
	}
	private function delete($pk)
	{
		$model = $this->model("spiderlogs")->getByPk($pk);
		if($model === null)
		{
			$this->error("error");
		}
		$model->delete();
	}
}