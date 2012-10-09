<?php 
class groupseries extends mini_db_model
{
    protected  $table = 'groupseries';
    protected  $columns = array('id','version','groupid','seriesid');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '视频系列';
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
    	);
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'groupid'=>'聚合id',
        'seriesid'=>'系列id');
    }
}