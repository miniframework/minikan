<?php 

include dirname(__FILE__).'/../init.php';

$params = array('cookiefile'=>'./test.txt');
		
$curl = new mini_tool_curl($params);
$bid="";
for($i=0;$i<100;$i++){
$params = array('cookiefile'=>'./test.txt');
	$curl->getData("http://movie.douban.com/subject_search?cat=1002&search_text=潮爆大状");
	$cookie = file_get_contents($params['cookiefile']);
	
	if(preg_match('/bid.*?"(.*?)"/ism', $cookie,$match))
	{
		$bid .= $match[1]."\t";
	}
	file_put_contents($params['cookiefile'], '');
	echo "clear...\r\n";
}
file_put_contents("./bid.data",$bid);

