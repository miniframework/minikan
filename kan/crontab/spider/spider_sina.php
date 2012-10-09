<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';


$url = "http://video.sina.com.cn/interface/movie/category.php?category=movie&page=1&pagesize=20&liststyle=1&topid=2&leftid=movie-index&rnd=0.614149";

print_r(spiderSina($url));




?>
