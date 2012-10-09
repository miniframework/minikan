<?php 
class catalogs extends mini_db_model
{
    protected  $table = 'catalogs';
    protected  $columns = array('id','version','ogroup','title','showtitle','online','onum');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = true;
    public     $modelTag = '电影分组';
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
    public function getByOgroup($ogroup)
    {
    	$condition = new mini_db_condition();
    	$condition->compare("ogroup","=",$ogroup);
    	$condition->compare("online","=",1);
    	$condition->order = " onum desc";
    	return $this->record->findAll($condition);
    }
    public function getByRecommendByTypeid($num = 10)
    {
    	
    	$typeid = $this->id;
    	$sql = "select onum,vgroups.*
			    	from
			    		recommends
			    	left join
			    		vgroups
			    	on
			    		recommends.groupid = vgroups.id
			    	where
			    		recommends.typeid=$typeid
			    	order by recommends.onum desc limit $num";
    	return mini_db_record::getAll($sql, array(),'vgroups');
    }
    public function getGroupMap($key='')
    {
    	$map = array(1=>"首页电影模块",2=>"首页电视剧模块",3=>"首页动漫模块",4=>"首页电影底部",5=>"首页动漫底部");
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'ogroup'=>'分组',
		'title'=>'标题',
		'showtitle'=>'显示标题',
		'online'=>'上线',
		'onum'=>'排序');
    }
}