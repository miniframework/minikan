<?php
class SegmentsController extends mini_web_controller
{
	public function perinit()
	{
		$firsterror = $this->request->get("firsterror");
		$this->view->firsterror = $firsterror;
	}
    public function doList()
    {
    	
    	$id = $this->request->get("id");
    	if(empty($id)) $id = 'hot_search';
    	if($id == 'hot_search')
    	{
    		$hot_search_file = mini_base_application::app()->getRunPath()."/data/editor/hot_search.data";
    		$data_content = file_get_contents($hot_search_file);
    		if(empty($data_content)) $data_content = '';
    		$this->view->data_content = $data_content;
    	}
    	$this->view->id = $id;
    }
    public function doSave()
    {
    	$id = $this->request->get("id");
    	if($id == 'hot_search' )
    	{
    		$data_content = $this->request->get("data_content");
    		$hot_search_file = mini_base_application::app()->getRunPath()."/data/editor/hot_search.data";
    		$data_content = file_put_contents($hot_search_file, $data_content);
    	}
    	$this->jump($this->route->createUrl('admin','segments','list',array('id'=>$id)));
    	$this->closeRender();
    }
}