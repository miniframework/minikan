<?php 
class reviews extends mini_db_model
{
    protected  $table = 'reviews';
    protected  $columns = array('id','version','groupid','doubanid','reviewid','author','title','published','updated','summary','rating','vote','comment','useless','ctime');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
    public     $modelTag = '豆瓣评论';
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
    					'hasmany'=>true,
    					'condition'=>'groupid=:groupid',
    			),
    	);
    }
    public function getDoubanUrl()
    {
    	$reviewUrl = "http://movie.douban.com/review/{reviewId}/";
    	return strtr($reviewUrl, array('{reviewId}'=>$this->reviewid));
    }
	public function getCutSummary($len=80)
	{
		$search=array("\r","\n","　","<br/>");
		$summary = str_replace($search, array(), $this->summary);
		return $this->adv_substr($summary, $len, true);
	}
	private function adv_substr($str, $len = 12, $dot = true,$encoding='utf-8') 
	{
		$strlen = mb_strlen($str, $encoding);
		$substr = mb_substr($str, 0, $len, $encoding);
		if($strlen > $len && $dot == true)
		{
			$substr .= "...";
		}
		return $substr;
	}
    public function getUpdated()
    {
    	return date("Y-m-d",$this->published);
    }
    public function getPublished()
    {
    	return date("Y-m-d",$this->published);
    }
    public function tags()
    {
        return array(
		'id'=>'Id',
		'version'=>'Version',
		'groupid'=>'聚合id',
        'doubanid'=>'豆瓣id',
		'reviewid'=>'评论id',
		'author'=>'作者',
		'title'=>'标题',
		'published'=>'发布时间',
		'updated'=>'更新时间',
		'summary'=>'内容',
		'rating'=>'评分',
		'vote'=>'投票',
		'comment'=>'评论数',
		'useless'=>'没用',
		'ctime'=>'抓取时间');
    }
}