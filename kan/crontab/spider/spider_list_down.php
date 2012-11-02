<?php
date_default_timezone_set('Asia/Shanghai');
include dirname(__FILE__).'/../init.php';
$spider = new spiderService();
$spider->spiderDayDown(1,100);
mini::end();