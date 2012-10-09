<?php
include dirname(__FILE__).'/../init.php';

$vgroup = mini_db_model::model('vgroups');
$vgroups = $vgroup->getByVtype(array(':vtype'=>3));
foreach($vgroups as $k => $v)
{
	$cate = $v->cate;
	$cate_arr = explode("\t", $cate);
	foreach($cate_arr as $kk => $vv)
	{
		if(!isset($big[$vv]))
		{
			$big[$vv] = 1;
		}
		{
			$big[$vv]++;
		}
	}
}
arsort($big);
print_r($big);
//mini_db_unitofwork::getHandle()->commit();



?>
