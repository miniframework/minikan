<?php 
class groupvideo extends mini_db_model
{
    protected  $table = 'groupvideo';
    protected  $columns = array('id','version','groupid','videoid','siteid','vtype');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = 'Groupvideo';
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
    			'getByVideoid'=>array(
    					'hasmany'=>false,
    					'condition'=>'videoid=:videoid',
    			),
    	);
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'groupid'=>'聚合id',
		'videoid'=>'视频id',
		'siteid'=>'来源',
		'vtype'=>'类型');
    }
}