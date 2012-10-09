<?php 
class grouppeople extends mini_db_model
{
    protected  $table = 'grouppeople';
    protected  $columns = array('id','version','groupid','peopleid','type');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = true;
    public     $modelTag = 'Grouppeople';
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
    			'getByGroupid'=>array(
    					'hasmany'=>false,
    					'condition'=>'groupid=:groupid and peopleid=:peopleid',
    			),
    			'getByGroupids'=>array(
    					'hasmany'=>true,
    					'condition'=>'groupid=:groupid',
    			)
    	);
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'groupid'=>'Groupid',
		'peopleid'=>'Peopleid',
		'type'=>'Type');
    }
}