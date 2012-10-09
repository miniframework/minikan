<?php
include dirname(__FILE__).'/../init.php';

$vgroup = mini_db_model::model('vgroups');
$vgroups = $vgroup->getList();
foreach($vgroups as $k => $v)
{
	
	$videoids = $v->videoids;
	$videojson = json_decode($videoids, true);
	$new = array();
	foreach($videojson['siteid'] as $kk => $siteid)
	{
		$new[$siteid]['playlink'] = $videojson['playlink'][$kk];
		if(isset($videojson['videoid'][$kk]))
		$new[$siteid]['videoid'] = $videojson['videoid'][$kk];
	 
	}
	print_r($new);
	$v->videoids= json_encode($new);
}
mini_db_unitofwork::getHandle()->commit();


?>
