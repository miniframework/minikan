<?php
include dirname(__FILE__).'/../init.php';

$spider = new spiderService();
$spider->updateEpisodeVideo("where  epsign <>2");
?>
