<?php
include dirname(__FILE__).'/../init.php';

$spider = new spiderService();
$spider->updateEpisodeVideo("where (vtype=2 or vtype=3)  and  epsign <>2 limit 0,10");
?>
