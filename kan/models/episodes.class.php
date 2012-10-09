<?php 
class episodes extends mini_db_model
{
    protected  $table = 'episodes';
    protected  $columns = array('id','version','videoid','vtype','playlink','imagelink','flv');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = true;
    public     $modelTag = '分级地址';
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
    			'getsByVideoid'=>array(
    					'hasmany'=>true,
    					'condition'=>'videoid=:videoid',
    					),
    			'getByVideoid'=>array(
    					'hasmany'=>false,
    					'condition'=>'videoid=:videoid',
    			)
    	);
    }
    public function isExistPlayer()
    {
    	$flv = $this->flv;
    	if(!empty($flv))
    		return true;
    	else
    		return false;
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'videoid'=>'视频id',
		'vtype'=>'类型',
		'playlink'=>'播放地址',
		'imagelink'=>'图片地址',
        'flv'=>'flash参数');
    }
}