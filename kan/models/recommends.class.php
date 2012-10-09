<?php 
class recommends extends mini_db_model
{
    protected  $table = 'recommends';
    protected  $columns = array('id','version','groupid','typeid','onum');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '推荐';
    // NOTE: you should only define rules for those attributes that
    public function rules()
    {
        return array();
    }
    // NOTE:array relational rules            
    public function relations()
    {
        return array(
        				'Relvgroup'=>array(
        								'hasbelong',
        								'vgroups',
        								array('title'),
        								'groupid')
        		);
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
    public function getByTypeid($typeid)
    {
    	$condition = new mini_db_condition();
    	$condition->compare("typeid","=", $typeid);
    	$condition->order = "onum desc";
    	return $this->record->findAll($condition);
    }
    public function typeidMap($typeid='')
    {
//     	$map = array(1=>"首页电影热门",2=>"首页电影动作",3=>"首页电影爱情");
//     	if(!empty($typeid))
//     	{
//     		return $map[$typeid];
//     	}
//     	return $map;

    	if(!empty($typeid))
    	{
    		$catalogs = self::model('catalogs');
    		return $catalogs->getByPk($typeid);
    	}
    	else
    	{
    		$catalogs = self::model('catalogs');
    		return $catalogs->getList();
    	}
    	
    	
    	
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'groupid'=>'聚合id',
		'typeid'=>'推荐类别',
		'onum'=>'排序');
    }
}