<?php
include dirname(__FILE__).'/../init.php';
include dirname(__FILE__).'/../../libs/spiderlib.php';


$url = "http://www.youku.com/v_olist/c_96_a__s__g__r__lg__im__st__mt__tg__d_1_et_0_fv_0_fl__fc__fe__o_7_p_1.html";

print_r(spiderYouku($url));


?>
