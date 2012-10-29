<?php 
class vspiders extends mini_db_model
{
    protected  $table = 'vspiders';
    protected  $columns = array('id','version','siteid','vtype','dtype','spidercall','title','targeturl','catchtime','endtime','updatenum','daynum','locking','isstore');
    protected  $primaryKey = 'id';
    protected  $autoSave = true;
    protected  $autoIncrement = false;
	public  $modelTag = '';
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
    			"getList"=>array(
    					'hasmany'=>true,
    			),
    	);
    }
    public function spiderCall($key='')
    {
    	$map = array(1=>"Movie",2=>"Teleplay",3=>"Cartoon",4=>"Variety",5=>"ApiMovie",6=>"DownMovie");
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function typeMap($key='')
    {
    	$map = array(0=>"在线",1=>"下载");
    	if(empty($key))
    	{
    		return $map;
    	}
    	else {
    		return $map[$key];
    	}
    }
    public function getSpiderXml($date)
    {
    	$xmlfile = $this->id."_".$this->siteid.".xml";
    	 
    	$runpath = mini_base_application::app()->getRunPath();
    	
    	if(empty($data))
    	{
    		$path = date("Y-m-d",strtotime($this->endtime));
    	}
    	else 
    	{
    		$path = $date;
    	}
    	if(!file_exists($runpath."/data/spiderxml/".$path))
    	{
    		$oldmask = umask(0);
    		mkdir($runpath."/data/spiderxml/".$path,0777);
    		umask($oldmask);
    	}
    	$xmlpath = $runpath."/data/spiderxml/".$path."/".$xmlfile;
    	return $xmlpath;
    }
    public function storeXml($data,$day)
    {
    	$xmldata = '';
    	if(!empty($data))
    	{
    		$xmldata = '<?xml version="1.0" encoding="UTF-8"?>';
    		$xmldata .="<data>";
    		foreach($data as $kk => $row)
    		{
    			$xmldata .= "<video>";
    			$xmldata .= "<siteid>".$this->siteid."</siteid>";
    			$xmldata .= "<vtype>".$this->vtype."</vtype>";
    			foreach($row as $k => $info)
    			{
    					
    				$xmldata .="<$k>";
    				if(is_array($info))
    				{
    					if($k == 'episodes')
    					{
    						
    						foreach($info as $i =>$list)
    						{	
    							$xmldata .="<link>";
    							foreach($list as $e => $link)
    							{
    								$xmldata .="<$e>$link</$e>";
    							}
    							$xmldata .="</link>";
    						}
    						
    					}
    					else 
    					{
	    					$line = implode("\t", $info);
	    					$xmldata .="<![CDATA[";
	    					$xmldata .= trim($line);
	    					$xmldata .="]]>";
    					}
    				}
    				else
    				{
    					$xmldata .= "<![CDATA[".$info."]]>";
    				}
    				$xmldata .="</$k>\r\n";
    					
    			}
    			$xmldata .= "</video>";
    		}
    		$xmldata .="</data>";
    			
    	}
    	if(empty($xmldata)) return false;
    	$xmlfilename = $this->getSpiderXml($day);
    	file_put_contents($xmlfilename, $xmldata);
    	$this->isstore = 1;
	   	return true;
    }
    public function getDaynumAndIsstoreAndUnlock($daynum, $isstore, $locking,$limit=10,$isInit=0,$onedaynum=0)
    {
    	$condition = new mini_db_condition();
    	$condition->compare("isstore","=", $isstore);
    	if($isInit == 1)
    	{
    		$condition->compare("isstore","=", 0,"or");
    	}
    	$condition->compare("daynum","=", $daynum);
    	if($onedaynum>0)
    	{
    		$condition->compare("daynum","<=", $onedaynum,'or');
    	}
    	$condition->compare("locking","=", $locking);
    	$condition->limit = $limit;
    	return $this->record->findAll($condition);
    }
   
    public function lockxml()
    {
    	$this->locking = 1;
    }
    public function lockdb()
    {
    	$this->locking = 2;
    }
    public function unlock()
    {
    	$this->locking = 0;
    }
    public function isstoreMap($key ='')
    {
    	$map = array(1=>"xml",2=>"db");
    	if(empty($key))
    	{
    		return 'no';
    	}
    	else {
    		return $map[$key];
    	}
    }
}