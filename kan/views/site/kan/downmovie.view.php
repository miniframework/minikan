<?php 
$change_title = '';
if(isset($search['year']))
{
	$change_title.=$search['year']."年";
}
if(isset($search['area']))
{
	$change_title.=$search['area'];
}
if(isset($search['cate']))
{
	$change_title.=$search['cate'];
}


$header_title = "最新的".$change_title."电影下载"." "."好看的".$change_title."电影下载-手机电影下载－下载电影列表第".$page->currentpage."页-爱乐子";
$header_keywords = $change_title."电影下载,电影下载".$page->currentpage."页,电影下载,手机电影下载,迅雷电影下载,爱乐子电影";
$header_description = "爱乐子-最新电影".$change_title."下载，以及迅雷电影下载".$change_title.",超快速的电影下载种子";

$this->layout('kan_main',array("title"=>$header_title,"keywords"=>$header_keywords,"description"=>$header_description));?>



<div id="searching" class="fm960 clearfix">
		<div class="searching-bd">
			<ul class="kinds clearfix">
				<li><a class="on"
					href="<?php echo $this->createUrl("site","kan","downmovie",array("page"=>1),array("area"=>$search['area'],"year"=>$search['year'],"order"=>$search['order']));?>">全部类型</a></li>
			
			<?php 
			$cateMap = $vdownload->cateMap();
			foreach($cateMap as $k => $cate){ ?>
			<li><a target="_self" <?php if($cate==$search['cate']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","downmovie",array("page"=>1),array("cate"=>$cate,"area"=>$search['area'],"year"=>$search['year'],"order"=>$search['order']));?>"><?php echo $cate;?></a></li>
			<?php }?>
			</ul>
			<ul class="region clearfix">
				<li><a class="on"
					href="<?php echo $this->createUrl("site","kan","downmovie",array("page"=>1),array("cate"=>$search['cate'],"year"=>$search['year'],"order"=>$search['order']));?>">全部地区</a></li>
			<?php 
			$areaMap = $vdownload->areaMap();
			foreach($areaMap as $k => $area){ ?>
			<li><a target="_self" <?php if($area==$search['area']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","downmovie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$area,"year"=>$search['year'],"order"=>$search['order']));?>"><?php echo $area;?></a></li>
			<?php }?>
		  </ul>
			<ul class="years clearfix">
				<li><a class="on"
					href="<?php echo $this->createUrl("site","kan","downmovie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"order"=>$search['order']));?>">全部年代</a></li>
			<?php 
			$yearMap = $vdownload->yearMap();
			foreach($yearMap as $k => $year){ ?>
			<li><a target="_self" <?php if($year==$search['year']) echo 'class="on"';?>
					href="<?php echo $this->createUrl("site","kan","downmovie",array("page"=>1),array("cate"=>$search['cate'],"area"=>$search['area'],"year"=>$year,"order"=>$search['order']));?>"><?php echo $year;?></a></li>
			<?php }?>
		
 		 </ul>
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
					<a title="<?php echo $model->getTitle();?>"  href="<?php echo $this->createUrl('site','kan','downdetail',array('id'=>$model->id));?>"> 
					<img width="120" height="160" src="<?php echo $model->getImageLink();?>">
					</a>
				</dt>
			
				<dd class="film_name">
					 <a title="<?php echo $model->getTitle();?>" target="_blank" href="<?php echo $this->createUrl('site','kan','downdetail',array('id'=>$model->id));?>"><?php echo $model->getTitle();?></a>
					<?php echo $model->year;?></dd>
				<?php $seed = $model->getSeed();?>
				<dd>
					类型：<span><?php echo $model->cate?></span> 
				</dd>
				<dd>
					国家：<span><?php echo $model->country?></span>
				</dd>
				<dd>
					演员：<span><?php  echo $model->getStarOne()?></span>
				</dd>
				<dd>
					尺寸/格式：<span><?php echo $seed->videoscreen?>;<?php echo $seed->fileformat?></span>
				</dd>
				
				<dd class="intro">
					简介：<span> <?php echo $model->getCutSummary(45);?></span>
					<a title="<?php echo $model->getTitle();?>" href="<?php  echo $this->createUrl('site','kan','downdetail',array('id'=>$model->id));?>">[下载]</a>
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