<?php 
$cateMap = $vgroup->cateMap(3);
$areaMap = $vgroup->areaMap(3);
$yearMap = $vgroup->yearMap();
$change_title = '';
if(isset($cateMap[$search['year']]))
{
	$change_title.=$yearMap[$search['year']]."年";
}
if(isset($cateMap[$search['area']]))
{
	$change_title.=$areaMap[$search['area']];
}
if(isset($cateMap[$search['cate']]))
{
	$change_title.=$cateMap[$search['cate']];
}

$header_title = "最新的".$change_title."动漫"." "."最热".$change_title."动漫";
$this->layout('kan_main',array("title"=>$header_title));?>

<div id="searching" class="fm960 clearfix">
  <div class="searching-bd">
    <ul class="kinds clearfix">
      <li><a class="on"
					href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("area"=>$search['area'],"year"=>$search['year'],"order"=>$search['order']));?>">全部类型</a></li>
      <?php $cateMap = $vgroup->cateMap(3);
			foreach($cateMap as $k => $cate){ ?>
      <li><a target="_self" <?php if($k==$search['cate']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$k,"area"=>$search['area'],"year"=>$search['year'],"order"=>$search['order']));?>"><?php echo $cate;?></a></li>
      <?php }?>
    </ul>
    <ul class="region clearfix">
      <li><a class="on"
					href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$search['cate'],"year"=>$search['year'],"order"=>$search['order']));?>">全部地区</a></li>
      <?php $areaMap = $vgroup->areaMap(3);
			foreach($areaMap as $k => $area){ ?>
      <li><a target="_self" <?php if($k==$search['area']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$search['cate'],"area"=>$k,"year"=>$search['year'],"order"=>$search['order']));?>"><?php echo $area;?></a></li>
      <?php }?>
    </ul>
    <ul class="years clearfix">
      <li><a class="on"
					href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"order"=>$search['order']));?>">全部年代</a></li>
      <?php $yearMap = $vgroup->yearMap();
			foreach($yearMap as $k => $year){ ?>
      <li><a target="_self" <?php if($k==$search['year']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$k,"order"=>$search['order']));?>"><?php echo $year;?></a></li>
      <?php }?>
    </ul>
  </div>
  <div class="searching-ft clearfix">
    <div class="tab">
      <?php 	$orderMap = $vgroup->getOrder();
      			$end = count($orderMap);
				foreach($orderMap as $k => $order){ ?>
     				<a target="_self"  <?php if($k == $search['order']) {echo 'class="on"';}?>
					href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$search['year'],"order"=>$k));?>">
					<?php echo $order;?>
					</a> 
					<?php if($end != $k) {?>
						<em>&nbsp;|</em>
				  	<?php } ?>
      <?php }?>
    </div>
    <?php if(!empty($search['cate']) || !empty($search['area']) || !empty($search['year'])) {?>
		     <?php  $cateMap = $vgroup->cateMap(3);
		     		$areaMap = $vgroup->areaMap(3);
		    		$yearMap = $vgroup->yearMap();
		     ?>
		    <ul class="title_order_box">                             
			    <?php if(!empty($search['cate']) && array_key_exists($search['cate'], $cateMap)) {?>
			    <li><span><?php echo $cateMap[$search['cate']];?><a href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("area"=>$search['area'],"year"=>$search['year'],"order"=>$search['order']));?>" class="ct"></a></span></li>
			    <?php }?>
			    <?php if(!empty($search['area']) && array_key_exists($search['area'], $areaMap)) {?>
			    <li><span><?php echo $areaMap[$search['area']];?><a href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$search['cate'],"year"=>$search['year'],"order"=>$search['order']));?>" class="ct"></a></span></li> 
			    <?php }?>
			    <?php if(!empty($search['year']) && array_key_exists($search['year'], $yearMap)) {?>
			    <li><span><?php echo $yearMap[$search['year']];?><a href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"order"=>$search['order']));?>" class="ct"></a></span></li>
			    <?php }?>
		    </ul>
		    <?php }?>
  </div>
</div>
<div class="video-list fm960 clearfix">
  <ul class="clearfix">
    <?php 		
	if(!empty($models)) {
	foreach($models as $k => $model)
	{?>
    <li>
      <dl>
        <dt> 
        <a target="_blank" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$model->id));?>"> 
        	<img width="120" height="160" src="<?php echo $model->getImageLink();?>">
        	<span class="tip"><?php  echo $model->getEpSignforStr();?></span>  
        </a>
        </dt>
        <dd class="film_name"> 
        <span>
          <?php 
			$rate =  $model->getRate();
			if(!empty($rate)) { ?>
          <i><?php echo $rate;?></i> 分
          <?php }?>
          </span> <a target="_blank" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$model->id));?>"><?php echo $model->getShowTitle();?></a> <?php echo $model->year;?></dd>
        <dd> 类型：<span><?php foreach($model->getCate2Arr() as $c=>$name){?>
        			<a href="<?php echo $this->createUrl("site","kan","cartoon",array("page"=>1),array("cate"=>$model->getCateKey(3,$name)));?>"><?php echo $name;?></a>
					<?php }	?></span> 地区：<span><?php echo $model->area;?></span> </dd>
        <dd class="intro"> 简介：<span>
        	<?php echo $model->getCutSummary(38);?></span> <a href="<?php  echo $this->createUrl('site','kan','player',array('id'=>$model->id));?>">[详情]</a> </dd>
        <dd class="player">
          <?php echo  $this->helper('episodes')->showFirst($model);?> 
        </dd>
        <dd class="play"> <em>观看：</em> <span>
          <?php $videoids = $model->getVideoids() ;  foreach($videoids as $siteid => $videoid) {?>
          <a target="_blank" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$model->id),array('autoplay'=>1,'site'=>$siteid));?>"> <img src="/styles/kan/images/icon/<?php echo $video->getIcon($siteid);?>" /><?php echo $video->getSiteZh($siteid);?> </a>
          <?php }?>
          </span> 
       </dd>
      </dl>
    </li>
    <?php }
		} else {?>
<div style="margin:15px;">没有找到相关影片，请尝试其他分类！</div>
<?php }?>
  </ul>
</div>
<p class="page_list"> <?php echo $page->pageHtml();?> </p>