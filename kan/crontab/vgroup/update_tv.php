<?php
include dirname(__FILE__).'/../init.php';

$spider = new spiderService();
$spider->updateEpisodeVideo("where vtype=2 and id >46168");
?>
