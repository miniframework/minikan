<?php
include dirname(__FILE__).'/../init.php';

// $doubanApi = new doubanService();
// print_r($doubanApi->movie(array("title"=>"画皮","director"=>'麦克·宾德尔',"year"=>'1999','star'=>array('玛瑞儿·海明威')),"画皮")) ;
$groupservice = new groupService();
$groupservice->together();
// $doubanApi = new doubanService();
// $reviews = $doubanApi->reviews(2135981);
// print_r($reviews);
// $doubanApi = new doubanService();
// $reviews = $doubanApi->reviewsImdb('tt0268380');
// print_r($reviews);
//  $doubanApi = new doubanService();
//  $photos = $doubanApi->findPhotos('1424406');

?>
