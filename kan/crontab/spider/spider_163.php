<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';

$url = "http://search.v.163.com/searchtag/01-00-00-0000-02-00/";

print_r(spider163($url));



?>
