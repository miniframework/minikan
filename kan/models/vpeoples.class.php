<?php 
class vpeoples extends mini_db_model
{
    protected  $table = 'vpeoples';
    protected  $columns = array('id','version','name');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '人名';
    // NOTE: you should only define rules for those attributes that
    public function rules()
    {
        return array();
    }
    // NOTE:array relational rules            
    public function relations()
    {
        return array();
    }
    // NOTE:user defind select scopes            
    public function scopes()
    {
    	return array(
    			'getList'=>array(
    					'hasmany'=>true,
    			),
    			'getByName'=>array(
    					'hasmany'=>false,
    					'condition'=>'name=:name',
    					)
    	);
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'name'=>'名字');
    }
}