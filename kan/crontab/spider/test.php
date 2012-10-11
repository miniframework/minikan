<?php 

include dirname(__FILE__).'/../init.php';

$params = array('cookiefile'=>'./test.txt');
		
$curl = new mini_tool_curl($params);
print_r($curl->getData("http://movie.douban.com/subject_search?cat=1002&search_text=潮爆大状"));
