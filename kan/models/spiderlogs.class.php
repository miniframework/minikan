<?php 
class spiderlogs extends mini_db_model
{
    protected  $table = 'spiderlogs';
    protected  $columns = array('id','version','spiderid','type','starttime','endtime');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = 'Spiderlogs';
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
    public function search4SearchRow($searchrow)
    {
    	$condition = new mini_db_condition();
    	if(!empty($searchrow["spiderid"]))
    	{
    		$condition->compare("spiderid","=",$searchrow["spiderid"]);
    	}
    	return $this->record->findAll($condition);
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'spiderid'=>'Spiderid',
		'type'=>'Type',
		'starttime'=>'Starttime',
		'endtime'=>'Endtime');
    }
}