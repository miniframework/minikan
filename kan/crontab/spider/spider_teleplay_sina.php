<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';


$url = "http://video.sina.com.cn/interface/movie/category.php?category=teleplay&page=1&pagesize=20&liststyle=1&topid=2&leftid=teleplay-index&rnd=0.2162436991930008";

print_r(spiderTeleplaySina($url));




?>
