<?php
include dirname(__FILE__).'/../init.php';


$doubansearch = new doubanspiderService();

print_r($doubansearch->search(array('title'=>'末日情缘','down'=>1)));