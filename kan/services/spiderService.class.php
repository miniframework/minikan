<?php
class spiderService implements mini_db_unbuffer
{
	public $onedaynum = 5;
	public $db = null;
	public function __construct($params = array())
	{
		foreach($params as $key => $param)
		{
			$this->$key = $param;
		}
	}
	public function todbDay($daynum=0,$limit = 10)
	{
		$model = mini_db_model::model("vspiders");
		$vspiders= $model->getDaynumAndIsstoreAndUnlock($daynum,1,2,$limit);
		if(!empty($vspiders))
			foreach($vspiders as $k => $vspider)
			{
				$ids[] = $vspider->id;
			}
			mini_db_unitofwork::getHandle()->commit();
			$this->todb($ids);
			
	}
	public function todb($ids=array())
	{
		foreach($ids as $s => $id)
		{
			$model = mini_db_model::model("vspiders");
			$model = $model->getByPk($id);
			$spiderlogs = mini_db_model::model('spiderlogs');
			$starttime = date("Y-m-d H:i:s");
			$model->catchtime = $starttime;
			$spiderlogs->create(array("spiderid"=>$id,"type"=>2,"starttime"=>$starttime));
			
			$xmlpath = $model->getSpiderXml(0);
			
			
			libxml_use_internal_errors(true);
			$xmlobj = simplexml_load_file($xmlpath);
			if(empty($xmlobj))
			{
			
				$error = libxml_get_errors();
				libxml_clear_errors();
				$message ="";
				foreach($error as $k => $v) {
					$message .= "path:" . $xmlpath . "\tline:" . $v->line . "\tcolumn" . $v->column . "\tmessage:" . $v->message;
				}
				mini::e("config not load xml file {file} message:{message}" ,array('{file}'=>$xmlpath,'{message}'=>$message));
			}
			foreach($xmlobj->video as $k => $xml)
			{
				$videos = mini_db_model::model('videos');
				$videos->createByXml($xml);
			}
			if($model->daynum >= $this->onedaynum)
			{
				$model->daynum = 0;
				
				//$model->daynum = $model->daynum + 1;
			}
			else
			{
				$model->daynum = $model->daynum + 1;
			}
			$model->isstore = 2;
			$model->unlock();
			//start time， end time 分开
			mini_db_unitofwork::getHandle()->commit();
			$spiderlogs->endtime = date("Y-m-d H:i:s");
		}
		mini_db_unitofwork::getHandle()->commit();
	}
	public function spiderDay($daynum=0, $limit=10)
	{
		$model = mini_db_model::model("vspiders");
		$vspiders= $model->getDaynumAndIsstoreAndUnlock($daynum,2,0,$limit,1,$this->onedaynum);
		$ids = array();
		if(!empty($vspiders))
			foreach($vspiders as $k => $vspider)
		{
			$ids[] = $vspider->id;
			
			$vspider->lockxml();
			
// 			if($vspider->daynum >= $this->onedaynum)
// 			{
// 				$vspider->daynum = 0;
				
// 			}
			
		}
		mini_db_unitofwork::getHandle()->commit();
		$this->toxml($ids);
	}
	public function toxml($ids= array())
	{
		$runpath = mini_base_application::app()->getRunPath();
		include $runpath."/libs/spiderlib.php";
		$videos = mini_db_model::model('videos');
		$logger = mini::getLogger();
		if(!empty($ids))
			foreach($ids as $k => $id)
		{
			$model = mini_db_model::model("vspiders");
			$model = $model->getByPk($id);
			$url = $model->targeturl;
			
			echo $url."\r\n";
			$spiderType = $videos->siteidMap($model->siteid);
			$spiderFun = $model->spiderCall($model->spidercall);
			
			$spiderFunction =  "spider".$spiderFun.$spiderType;
			$spiderlogs = mini_db_model::model('spiderlogs');
			$starttime = date("Y-m-d H:i:s");
			$model->catchtime = $starttime;
			$spiderlogs->create(array("spiderid"=>$id,"type"=>1,"starttime"=>$starttime));
			$model->updatenum = $model->updatenum+1;
			$data = $spiderFunction($url);
			//start time， end time 分开
			mini_db_unitofwork::getHandle()->commit();
			
			$message="spider:$spiderFunction;id:$id;count:".count($data)."|service.toxml.";
			$logger->log($message ,mini_log_logger::LEVEL_INFO ,"spider.service");
		
			
			
			$endtime = time();
			$model->endtime = date("Y-m-d H:i:s",$endtime);
			$spiderlogs->endtime = date("Y-m-d H:i:s");
			$model->lockdb();
			$model->storeXml($data,date("Y-m-d",$endtime));
		}
		$logger->flush();
		mini_db_unitofwork::getHandle()->commit();
	}
	public function updateEpisodeVideo($where='')
	{
		$this->db  = mini_db_connection::getHandle();
		$videosql = "select * from videos $where";
		$this->db->unbuffer($videosql, $this);
	}
	public function callback($row)
	{
		$videos = mini_db_model::model("videos");
		$spiderType = $videos->siteidMap($row['siteid']);
		$vtypeFun = $videos->vtypeMapFun($row['vtype']);
		
		
		$updateFunction = "cover".$vtypeFun.ucwords($spiderType);
		
		if($row['vtype'] == 2 || $row['vtype'] == 3)
		{
			switch($updateFunction)
			{
	// 			case "coverTeleplaySina":
	// 				break;
				case "coverTeleplayYouku":
					break;
				case "coverCartoonYouku":
					break;
				case "coverCartoonTudou":
					break;
				case "coverTeleplayTudou":
					break;
				case "coverTeleplayLetv":
					break;
				case "coverCartoonLetv":
					break;
				case "coverTeleplaySohu":
					break;
				case "coverTeleplayQq":
					break;
				case "coverTeleplayPptv":
					break;
				case "coverCartoonPptv":
					break;
				default:
					return;
			}
			
		} else {
			
			switch($updateFunction)
			{
				//case "coverMovieSina":
				//break;
				case "coverMovieYouku":
					break;
				case "coverMovieTudou":
					break;
				case "coverMovieLetv":
					break;
				case "coverMovieSohu":
					break;
				case "coverMovieQq":
					break;
				case "coverMoviePptv":
					break;
				default:
					return;
			}
			
		}
		
		$runpath = mini_base_application::app()->getRunPath();
		include_once $runpath."/libs/spiderlib.php";
		
		
		$data = $this->$updateFunction($updateFunction,$row);
		if(!empty($data))
		{
			//如果状态为2,则还原原来状态
			if($row['status'] == 2 )
			{
				if(!empty($row['vgroupid']))
				{
					$status = 0;
					
				} else {
					$status = 1;
				}
				
				$updatesql = "update videos set status={$status} where id ='{$row['id']}'";
				$this->db->query($updatesql);
				echo $row['id']."revers..\r\n";
			}
			if($row['vtype'] == 2 || $row['vtype'] == 3)
			{
				//更新剧集
				$nowepisodes_num = count($data['episodes']);
				if(isset($data['episodes']) &&  $nowepisodes_num > $row['nowepisodes'])
				{
						$videoid = $row['id'];
						$delete_episodes_sql = "delete from episodes  where videoid={$videoid}";
						$this->db->query($delete_episodes_sql);
						foreach($data['episodes'] as $k => $v)
						{
							$erow = array();
							$erow['playlink'] = $v['playlink'];
							if(isset($v['imagelink']))
							$erow['imagelink'] = $v['imagelink'];
							if(isset($v['flv']))
							$erow['flv'] = $v['flv'];
							$erow['vtype'] = $row['vtype'];
							$erow['videoid'] = $videoid;
							$episodes = mini_db_model::model("episodes");
							$episodes->create($erow);
							
							
						}
						$time = time();
						mini_db_unitofwork::getHandle()->commit();
						$update_episodes_sql = "update  videos  set 
														epsign='{$data['epsign']}' , 
														nowepisodes = '{$data['nowepisodes']}', 
														allepisodes='{$data['allepisodes']}',mtime='{$time}' where id={$videoid}";
						
						$this->db->query($update_episodes_sql);
						
						if(!empty($row['vgroupid']))
						{
							$update_vgroup_sql = "update  vgroups  set
							epsign='{$data['epsign']}' ,
									nowepisodes = '{$data['nowepisodes']}',
									allepisodes='{$data['allepisodes']}',mtime='{$time}' where id={$row['vgroupid']}";
									$this->db->query($update_vgroup_sql);
						}
						
					echo $row['id']."update ep..\r\n";
				} else {
					echo $row['id']."no update ep..\r\n";
				}
				
			}
			
		}
		else 
		{
			$updatesql = "update videos set status=2 where id ='{$row['id']}'";
			$this->db->query($updatesql);
			echo $row['id']."link error..\r\n";
		}
		echo $updateFunction."\r\n";
	}
	
	
	public function coverMovieYouku($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverMovieTudou($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverMovieLetv($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverMovieSohu($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverMovieQq($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverMoviePptv($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}

	
	public function coverTeleplayYouku($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverCartoonYouku($fun,$row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverCartoonTudou($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverTeleplayTudou($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverTeleplayLetv($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverCartoonLetv($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverTeleplaySohu($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverTeleplayQq($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverTeleplayPptv($fun, $row)
	{

		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverCartoonPptv($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		return $data;
	}
	public function coverTeleplaySina($fun, $row)
	{
		$infolink = $row['infolink'];
		$data = $fun($infolink);
		if(empty($data)) return array();
		return array('episodes'=>$data);
	}
}