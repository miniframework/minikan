<?php 
class doubans extends mini_db_model
{
    protected  $table = 'doubans';
    protected  $columns = array('id','version','groupid','doubanid','title','pic','director','writer','star','cate','area','website','lang','pubdate','runtime','alias','imdb','rate','summary','tag','shortcomment','ctime');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '豆瓣信息';
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
		'doubanid'=>'豆瓣id',
		'title'=>'标题',
		'pic'=>'海报',
		'director'=>'导演',
		'writer'=>'编剧',
		'star'=>'明星',
		'cate'=>'分类',
		'area'=>'地区',
		'website'=>'官网',
		'lang'=>'语言',
		'pubdate'=>'发布时间',
		'runtime'=>'片长',
		'alias'=>'别名',
		'imdb'=>'imdb',
		'rate'=>'评分',
		'summary'=>'摘要',
		'tag'=>'Tag',
		'shortcomment'=>'短评',
		'ctime'=>'Ctime');
    }
}