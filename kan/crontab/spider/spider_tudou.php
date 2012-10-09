<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';


$url = "http://cartoon.tudou.com/albumtop/c9t-1v-1z-1a-1y-1h-1s1p1.html";

print_r(spiderCartoonTudou($url));



?>
