<?php
include dirname(__FILE__).'/../init.php';


ini_set('memory_limit','128M');
$spider = new spiderService();
$spider->todbDay(2,20);