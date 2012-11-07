<?php 
class vseeds extends mini_db_model
{
    protected  $table = 'vseeds';
    protected  $columns = array('id','version','downloadid','siteid','seed','word','videoscreen','filesize','fileformat','duration','ctime','mtime');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '种子';
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
    			'getBySeed'=>array(
    					'hasmany'=>false,
    					'condition'=>'seed=:seed',
    			),
    			'getByDownloadids'=>array(
    					'hasmany'=>true,
    					'condition'=>'downloadid=:downloadid',
    			),
    			'getByDownloadid'=>array(
    					'hasmany'=>false,
    					'condition'=>'downloadid=:downloadid',
    			)
    	);
    }
    public function getThunder()
    {
    	return "thunder://".base64_encode("AA".$this->seed."ZZ");
    }
    public function getSeedName()
    {
    	if($pos = mb_stripos($this->seed, '】')){
    		return mb_substr($this->seed,$pos+3);
    	}
    	if($pos = strripos($this->seed, ']')){
    		return substr($this->seed,$pos+1);
    	}
    	
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
        'version'=>'Version',
		'downloadid'=>'下载id',
		'siteid'=>'站点id',
		'seed'=>'种子',
		'word'=>'字幕',
		'videoscreen'=>'分辨率',
		'filesize'=>'文件大小',
		'fileformat'=>'文件格式',
		'duration'=>'时长',
		'ctime'=>'Ctime',
		'mtime'=>'Mtime');
    }
}