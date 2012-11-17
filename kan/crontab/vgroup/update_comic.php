<?php
include dirname(__FILE__).'/../init.php';

$spider = new spiderService();
$spider->updateEpisodeVideo("where vtype=3 and id>79095 order by id");
?>
