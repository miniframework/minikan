<?php
class spiderController extends mini_web_controller
{
	public function perinit()
	{
		$firsterror = $this->request->get("firsterror");
		$this->view->firsterror = $firsterror;
	}
	public function doLookxml()
	{
		$id = $this->request->get("id");
		$model = $this->model("vspiders")->getByPk($id);
		$data = file_get_contents($model->getSpiderXml(0));
		header("Content-type:text/xml");
		echo $data;
		$this->closeRender();
	}
	public function doTodb()
	{
		$id = $this->request->get("id");
		$model = $this->model("vspiders")->getByPk($id);
		$xmlpath = $model->getSpiderXml(0);
		
		
		libxml_use_internal_errors(true);
		$xmlobj = simplexml_load_file($xmlpath);
		if(empty($xmlobj))
		{
		
			$error = libxml_get_errors();
			libxml_clear_errors();
			foreach($error as $k => $v) {
				$message .= "path:" . $xmlpath . "\tline:" . $v->line . "\tcolumn" . $v->column . "\tmessage:" . $v->message;
			}
			mini::e("config not load xml file {file} message:{message}" ,array('{file}'=>$xmlpath,'{message}'=>$message));
		}
		if($model->dtype == 1)
		{
			foreach($xmlobj->video as $k => $xml)
			{
				$vdownloads = $this->model('vdownloads');
				$vdownloads->createByXml($xml);
			}
		}
		else 
		{
			foreach($xmlobj->video as $k => $xml)
			{
				$videos = $this->model('videos');
				$videos->createByXml($xml);
			}
		}
		$model->isstore = 2;
		echo "store..db..over.";
		$this->closeRender();
	}
	public function doGospider()
	{
		$id = $this->request->get("id");
		$model = $this->model("vspiders")->getByPk($id);
		$runpath = mini_base_application::app()->getRunPath();
		include $runpath."/libs/spiderlib.php";
		$url = $model->targeturl;
		$videos = $this->model('videos');
		
		$spiderType = $videos->siteidMap($model->siteid);
		$spiderFun = $model->spiderCall($model->spidercall);
		
		$spiderFunction =  "spider".$spiderFun.$spiderType;
		$model->catchtime = date("Y-m-d H:i:s");
		$model->updatenum = $model->updatenum+1;
		$data = $spiderFunction($url);
		
		$message="$spiderFunction find ".count($data)."|doGospider.";
		$this->logger->log($message ,mini_log_logger::LEVEL_INFO ,"spider.action");
		$this->logger->flush();
		
		
		$endtime = time();
		$model->endtime = date("Y-m-d H:i:s",$endtime);
		$model->storeXml($data,date("Y-m-d",$endtime));
		echo "spider..store..over.";
		$this->closeRender();
	}
	
	
	public function doList()
	{
		$model = $this->model("vspiders");
		$page = $model->page(array("request"=>$this->request, "route"=>$this->route,"url"=>array("admin","spider","list")));
		$this->view->page = $page;
		$models = $model->getList();
		$this->view->models = $models;
		
		$video = $this->model("videos");
		$this->view->video = $video;
	}
	public function doAddview()
	{
		$vspider = $this->model("vspiders");
		$this->view->vspider = $vspider;
		$video = $this->model("videos");
		$this->view->video = $video;
	}
	public function doAdd()
	{
		$model = $this->model("vspiders")->createByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		
		
		$jumpurl = $this->route->createUrl("admin","spider", "list");
		$this->response->setRedirect($jumpurl);
	}
	public function doModifyview()
	{
		$id = $this->request->get("id");
		$model = $this->model("vspiders")->getByPk($id);
		if($model === null)
		{
			$this->error("error");
		}
		
		$video = $this->model("videos");
		$this->view->video = $video;
		$this->view->model = $model;
	}
	public function doModify()
	{
		$model = $this->model("vspiders")->setByRequest($this->request);
		if($model === null)
		{
			$this->error("error");
		}
		$jumpurl = $this->route->createUrl("admin","spider", "list");
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
		$jumpurl = $this->route->createUrl("admin","spider", "list");
		$this->response->setRedirect($jumpurl);
	}
	private function error($message)
	{
		
		$jumpurl = $this->route->createUrl("admin","spider", "list",array("firsterror"=>$firsterror));
		$this->jump($jumpurl);
	}
	private function delete($pk)
	{
		$model = $this->model("vspiders")->getByPk($pk);
		if($model === null)
		{
			$this->error("error");
		}
		$model->delete();
	}
}
