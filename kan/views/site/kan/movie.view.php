<?php 
$cateMap = $vgroup->cateMap();
$areaMap = $vgroup->areaMap();
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


$change_title.=$search['star'];
$header_title = "最新的".$change_title."电影"." "."好看的".$change_title."电影";
$this->layout('kan_main',array("title"=>$header_title));?>


	<div id="searching" class="fm960 clearfix">
		<div class="searching-bd">
			<ul class="kinds clearfix">
				<li><a class="on"
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("area"=>$search['area'],"year"=>$search['year'],"star"=>$search['star'],"order"=>$search['order']));?>">全部类型</a></li>
			
			<?php $cateMap = $vgroup->cateMap();
			foreach($cateMap as $k => $cate){ ?>
			<li><a target="_self" <?php if($k==$search['cate']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$k,"area"=>$search['area'],"year"=>$search['year'],"star"=>$search['star'],"order"=>$search['order']));?>"><?php echo $cate;?></a></li>
			<?php }?>
			</ul>
			<ul class="region clearfix">
				<li><a class="on"
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"year"=>$search['year'],"star"=>$search['star'],"order"=>$search['order']));?>">全部地区</a></li>
			<?php $areaMap = $vgroup->areaMap();
			foreach($areaMap as $k => $area){ ?>
			<li><a target="_self" <?php if($k==$search['area']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$k,"year"=>$search['year'],"star"=>$search['star'],"order"=>$search['order']));?>"><?php echo $area;?></a></li>
			<?php }?>
		  </ul>
			<ul class="years clearfix">
				<li><a class="on"
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"star"=>$search['star'],"order"=>$search['order']));?>">全部年代</a></li>
			<?php $yearMap = $vgroup->yearMap();
			foreach($yearMap as $k => $year){ ?>
			<li><a target="_self" <?php if($k==$search['year']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$k,"star"=>$search['star'],"order"=>$search['order']));?>"><?php echo $year;?></a></li>
			<?php }?>
		
 		 </ul>
			<ul class="perform clearfix">
				<li><a class="on" href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$search['year'],"order"=>$search['order']));?>">全部明星</a></li>
				
				<?php $starMap = $vgroup->starMap(1);
				foreach($starMap as $k => $star){ ?>
				<li><a target="_self" <?php if($star==$search['star']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("star"=>$star,"order"=>$search['order']));?>"><?php echo $star;?></a></li>
				<?php }?>
				
				<?php if(!in_array($search['star'], $starMap) && !empty($search['star'])) {?>
				<li><a target="_self" class="on" href="<?php echo $this->createUrl("site","kan","moive",array("page"=>1),array("star"=>$search['star']));?>"><?php echo $search['star'];?></a></li>
					
				<?php }?>
			</ul>
		</div>
		<div class="searching-ft clearfix">
			<div class="tab">
				<?php 
					$orderMap = $vgroup->getOrder();
					$end = count($orderMap);
				foreach($orderMap as $k => $order){ ?>
				<a target="_self" <?php if($k == $search['order']) {echo 'class="on"';}?>
					href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$search['year'],"star"=>$search['star'],"order"=>$k));?>"><?php echo $order;?></a>
					
					<?php if($end != $k) {?>
						<em>&nbsp;|</em>
					<?php } ?>
				<?php }?>
		    </div>
		    <?php if(!empty($search['cate']) || !empty($search['area']) || !empty($search['year'])) {?>
		     <?php  $cateMap = $vgroup->cateMap();
		     		$areaMap = $vgroup->areaMap();
		    		$yearMap = $vgroup->yearMap();
		     ?>
		    <ul class="title_order_box">                             
			    <li>筛选结果：</li> 
			    <?php if(!empty($search['cate']) && array_key_exists($search['cate'], $cateMap)) {?>
			    <li><span><?php echo $cateMap[$search['cate']];?><a href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("area"=>$search['area'],"year"=>$search['year'],"order"=>$search['order']));?>" class="ct"></a></span></li>
			    <?php }?>
			    <?php if(!empty($search['area']) && array_key_exists($search['area'], $areaMap)) {?>
			    <li><span><?php echo $areaMap[$search['area']];?><a href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"year"=>$search['year'],"order"=>$search['order']));?>" class="ct"></a></span></li> 
			    <?php }?>
			    <?php if(!empty($search['year']) && array_key_exists($search['year'], $yearMap)) {?>
			    <li><span><?php echo $yearMap[$search['year']];?><a href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"order"=>$search['order']));?>" class="ct"></a></span></li>
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
					<a  href="<?php echo $this->createUrl('site','kan','player',array('id'=>$model->id));?>"> 
					<img width="120" height="160" src="<?php echo $model->getImageLink();?>">
					</a>
				</dt>
				<dd class="film_name">
					<span>
					<?php 
						$rate =  $model->getRate();
						if(!empty($rate)) { ?>
						<i><?php echo $rate;?></i>
						分
					<?php }?>
					</span> <a target="_blank" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$model->id));?>"><?php echo $model->getShowTitle();?></a>
					<?php echo $model->year;?></dd>
				<dd>
					导演：<span> <a href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("star"=>$model->director));?>"><?php echo $model->director;?></a></span>
				</dd>
				<dd class="actor">
					主演：<span><?php foreach($model->getStars() as $k => $star){?>
								<a href="<?php echo $this->createUrl("site","kan","movie",array("page"=>1),array("star"=>$star));?>">
								<?php echo $star;?>
								</a>
							  <?php }?>
						</span>
				</dd>
				<dd class="intro">
					简介：<span> <?php echo $model->getCutSummary(38);?></span> <a href="<?php  echo $this->createUrl('site','kan','player',array('id'=>$model->id));?>">[详情]</a>
				</dd>
				<dd class="play">
					<em>观看：</em> 
					<span>		
						<?php $videoids = $model->getVideoids() ; foreach($videoids as $siteid => $videoid) {?>
                              <a target="_blank" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$model->id),array('autoplay'=>1,'site'=>$siteid));?>"> 
                              <img src="/styles/kan/images/icon/<?php echo $video->getIcon($siteid);?>" /><?php echo $video->getSiteZh($siteid);?>
                              </a>
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
<p class="page_list">
<?php echo $page->pageHtml();?>
</p>