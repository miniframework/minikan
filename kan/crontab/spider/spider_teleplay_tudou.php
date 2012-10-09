<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';


$url = "http://tv.tudou.com/albumtop/c30t61v-1z-1a-1y-1h-1s1p1.html";

print_r(spiderTeleplayTudou($url));



?>
