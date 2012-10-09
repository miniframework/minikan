<?php
include dirname(__FILE__).'/../init.php';
date_default_timezone_set('Asia/Shanghai');

$curl = new mini_tool_curl();

$data = $curl->get("http://showme.sinaapp.com/",array('CLIENT-IP:8.8.8.8','X-FORWARDED-FOR:8.8.8.9'));
print_r($data);
//',10.2.3.4',