<?php
class repairService 
{

	public static function none2Episode($vtype)
	{
		$db  = mini_db_connection::getHandle();
		$sql = "select id,videoids from vgroups where vtype=$vtype";
		
		$vrow = $db->findAll($sql);
		foreach($vrow as $k => $v)
		{
			//echo "groupid:".$v['id']."\r\n";
			$videoids = json_decode($v['videoids'], true);
			foreach($videoids as $kk => $vv)
			{
				//echo "videoid:".$vv['videoid']."\r\n";
				$epsql = "select id from episodes where videoid = {$vv['videoid']}";
				$erow = $db->find($epsql);
				if(empty($erow))
				{
					echo "groupid:".$v['id']."\r\n";
					echo "videoid:".$vv['videoid']."\r\n";
					self::removeVideoFromGroup($vv['videoid']);
				}
			}
			
		}
	}
	public static function delVideo($videoid)
	{
		if(empty($videoid)) { echo "videoid is empty.\r\n";return ; }
		$videos = mini_db_model::model("videos");
		$video = $videos->getByPk($videoid);
		if(empty($video))
		{
			echo "not find video.\r\n";
			return ;
		}
		
		if($video->status != 1) {echo "video status must 1.\r\n";return ;}
		$vgroupid = $video->vgroupid;
		if(!empty($vgroupid)) {echo "video groupid must 0.\r\n";return ;}
		$db  = mini_db_connection::getHandle();
		
		$del_videogroup_sql = "delete from groupvideo where videoid=$videoid";
		$db->query($del_videogroup_sql);
		$del_episode_sql = "delete from episodes where videoid=$videoid";
		$db->query($del_episode_sql);
		
		$video->delete();
		echo "del video:".$videoid."\r\n";
		mini_db_unitofwork::getHandle()->commit();
	}
	public static function removeVideoFromGroup($videoid)
	{
		if(empty($videoid)) { echo "videoid is empty.\r\n";return ; }
		$videos = mini_db_model::model("videos");
		$video = $videos->getByPk($videoid);
		if(empty($video)) 
		{
			echo "not find video.\r\n";
			return ;
		}
		
		if($video->status != 2) {echo "video status must 2.\r\n";return ;}
		$vgroupid = $video->vgroupid;
		if(empty($vgroupid))
		{
			$video->status = 1;
		}
		else
		{
			$vgroups  = mini_db_model::model("vgroups");
			$vgroup = $vgroups->getByPk($vgroupid);
			if(empty($vgroup)) {echo "not find vgroup.\r\n";return ;}
			
			$videoids = $vgroup->getVideoids();
			if(count($videoids) == 1 )
			{
				foreach($videoids as $vsiteid => $svideo)
				{
					if($svideo['videoid'] == $videoid)
					{
						self::deleteGroupByGroupid($vgroupid);
					}
				}
			}
			else 
			{
			
				foreach($videoids as $vsiteid => $svideo)
				{
					if($svideo['videoid'] == $videoid)
					{
						
							//删除关系表
						$groupvideo = mini_db_model::model("groupvideo")->getByVideoid(array(":videoid"=>$svideo['videoid']));
							
						if(!empty($groupvideo))
						{
							$groupvideo->delete();
						}
						$video->status = 1;
						$video->vgroupid = 0;
						
						$delsiteid = $vsiteid;
						break;
					}
				}
				
				
				if(isset($delsiteid))
					unset($videoids[$delsiteid]);
				
				$vgroup->videoids = json_encode($videoids);
			}
		}
		
		echo "del videoid:".$videoid."\r\n";
		mini_db_unitofwork::getHandle()->commit();
	}
	
	public static  function deleteGroupByGroupid($pk)
	{
		$model = mini_db_model::model("vgroups")->getByPk($pk);
		
		if(empty($model)) {echo "not find vgroup.\r\n";return ;}
		$db  = mini_db_connection::getHandle();
		$videoids = $model->getVideoids();
		foreach($videoids as $vsiteid => $video)
		{
			if(isset($video['videoid']) && !empty($video['videoid']))
			{
				//删除关系表
				$groupvideo = mini_db_model::model("groupvideo")->getByVideoid(array(":videoid"=>$video['videoid']));
	
				if(!empty($groupvideo))
				{
					$groupvideo->delete();
				}
				$videos = mini_db_model::model("videos")->getByPk($video['videoid']);
				$videos->status = 1;
				$videos->vgroupid = 0;
			}
		}
		$del_grouppeople_sql = "delete from grouppeople where groupid=$pk";
		$db->query($del_grouppeople_sql);
	
		$del_doubans_sql = "delete from doubans where groupid=$pk";
		$db->query($del_doubans_sql);
	
		$del_reviews_sql = "delete from reviews where groupid=$pk";
		$db->query($del_reviews_sql);
	
		$del_groupseries_sql = "delete from groupseries where groupid=$pk";
		$db->query($del_groupseries_sql);
	
		$del_recommends_sql = "delete from recommends where groupid=$pk";
		$db->query($del_recommends_sql);
	
		echo "del group:".$pk."\r\n";
		$model->delete();
		mini_db_unitofwork::getHandle()->commit();
	}
}