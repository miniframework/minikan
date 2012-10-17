<?php
class recommendsController extends mini_web_controller
{
	public function perinit()
	{
		$firsterror = $this->request->get("firsterror");
		$this->view->firsterror = $firsterror;
	}
	public function doList()
	{
        $typeid = $this->request->get("typeid");
        $model = $this->model('recommends');
		//$page = $model->page(array("request"=>$this->request, "route"=>$this->route,"url"=>array("admin","recommends","list")));
		if(!empty($typeid))
		{  
			$model = $this->model('recommends');
			$models = $model->getByTypeid($typeid);
		}
		else
		{
			$models = $model->getList();
		}
		$this->view->recommend = $model;
		$this->view->models = $models;
	}
	public function doAddview()
	{
		$model = $this->model('recommends');
		$this->view->model = $model;
	}
	public function doAdd()
	{
		$vgroup = $this->model("vgroups");
		
		$vgroup->getByPk($this->request->get("groupid"));
		if($vgroup === null)
		{
			$this->error("error");
		}
		$shortcomment = $this->request->get("shortcomment");
		if(!empty($shortcomment))
		{
			$shortjson = json_encode(array("shortcomment"=>$shortcomment));
			$this->request->set("info",$shortjson);
		}
		
		$model = $this->model('recommends')->createByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","recommends", "list");
		$this->response->setRedirect($jumpurl);
	}
	
	public function doModifyview()
	{
		$id = $this->request->get("id");
		$model = $this->model("recommends")->getByPk($id);
		if($model === null)
		{
			$this->error("error");
		}
		$this->view->model = $model;
	}
	public function doModify()
	{
		
		$shortcomment = $this->request->get("shortcomment");
		if(!empty($shortcomment))
		{
			$shortjson = json_encode(array("shortcomment"=>$shortcomment));
			$this->request->set("info",$shortjson);
		}
		$model = $this->model("recommends")->setByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","recommends", "list");
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
		$jumpurl = $this->route->createUrl("admin","recommends", "list");
		$this->response->setRedirect($jumpurl);
	}
	private function error($message)
	{
		
		$jumpurl = $this->route->createUrl("admin","recommends", "list",array("firsterror"=>$message));
		$this->jump($jumpurl);
	}
	private function delete($pk)
	{
		$model = $this->model("recommends")->getByPk($pk);
		if($model === null)
		{
			$this->error("error");
		}
		$model->delete();
	}
}