<?php
date_default_timezone_set('Asia/Shanghai');
include dirname(__FILE__).'/../init.php';
$spider = new spiderService();
$spider->todbDayDown(0,20);