<?php 

$header_title = "《".$vdownload->getTitle()."》电影下载-手机电影下载-迅雷电影下载-爱乐子";
$header_keywords = "电影".$vdownload->getTitle().",".$vdownload->getTitle()."电影下载,手机电影下载,迅雷电影下载,爱乐子电影";
$header_description = "最新电影".$vdownload->title."下载，，以及迅雷电影下载".$vdownload->getTitle().",超快速的电影下载种子";
$this->layout('kan_main',array("title"=>$header_title,"keywords"=>$header_keywords,"description"=>$header_description));?>
<style>
.right_detail {
overflow: hidden;
</style>
<div class="detail fm960 clearfix">
		<div class="left_detail">
			<div class="poster">
				<a href=""><img style="width:230px;height:307px" src="<?php echo  $vdownload->getBigImageLink();?>"></a>
			</div>
		</div>
		<div class="right_detail">
		 
			<div class="ptitle clearfix">
  			<h1><?php echo  $vdownload->getTitle();?></h1>
			</div>
			<div class="vinfo clearfix">
			</div>

			<div class="play_box">
			<span ><img src="<?php echo  $vdownload->getOneImageLink();?>"></span><br/>
			<span style="font-size:16px;color:orange">◎片　　名　</span><span style="font-size:14px;"><?php echo $vdownload->alias?></span><br/>
			<span style="font-size:16px;color:orange">◎年　　代　</span><span style="font-size:14px;"><?php echo $vdownload->year?></span><br/>
			<span style="font-size:16px;color:orange">◎国　　家　</span><span style="font-size:14px;"><?php echo $vdownload->country?></span><br/>
			<span style="font-size:16px;color:orange">◎类　　别　</span><span style="font-size:14px;"><?php echo $vdownload->cate?></span><br/>
			<span style="font-size:16px;color:orange">◎语　　言　</span><span style="font-size:14px;"><?php echo $vdownload->lang?></span><br/>
			
					
					<?php $seeds = $vdownload->getSeeds();?>
					
					<?php 
						  if(!empty($seeds))
						  	foreach($seeds as $k =>$seed)
						  { ?>
							<span style="font-size:16px;color:orange">◎文件格式　</span><?php echo $seed->fileformat?><br/>
							<span style="font-size:16px;color:orange">◎视频尺寸　</span><?php echo $seed->videoscreen?><br/>
							<span style="font-size:16px;color:orange">◎文件大小　</span><?php echo $seed->filesize?><br/>
							<span style="font-size:16px;color:orange">◎片　　长　</span><?php echo $seed->duration?><br/>
							<span style="font-size:16px;color:orange">◎字　　幕　</span><?php echo $seed->word?><br/>
							<br/>
							<span style="font-size:16px;color:orange">◎下　　载　</span>(非IE用户拷贝地址到迅雷下载)<br/><br/>
							<span style="font-size:16px;color:blue"><?php echo $seed->getSeedName();?></span>
							<div style="background:orange"><a href="<?php  echo $seed->getThunder();?>"><?php  echo $seed->getThunder();?></a></div><br/>
						
								
						<?php  } ?>
						<br/>
						
					<span style="font-size:16px;color:orange">◎导演</span><br/>
					 <?php echo $vdownload->director;?><br/>
					 <span style="font-size:16px;color:orange">◎演员</span><br/>
					<span style="font-size:13px;"></span> <?php $i=0; $stars = $vdownload->getStar();
					 	foreach($stars  as $k => $v)
					 	{
					 		$i++;
					 		echo $v."<br>";
					 		if($i >=10) break;
					 	}
					 
					 ?></span><br/><br/>
					 <span style="font-size:16px;color:orange">◎摘要</span><br/>
					 <pre style="font-size:14px;">
					 <?php echo $vdownload->summary;?>
					</pre>
					<?php $photos = $vdownload->getPhotos();
					
						
						
						foreach($photos as $k => $v)
						{?>
							<img src="http://img.store.sogou.com/net/a/04/link?appid=501&url=<?php echo $v?>" >
					<?php }?>
					<br/>
					<br/>
					<br/>
					<a name="download_thunder"></a>
					<?php 
						  if(!empty($seeds))
						  	foreach($seeds as $k =>$seed)
						  { ?>
							<span style="font-size:16px;color:orange">◎下　　载　</span>(非IE用户拷贝地址到迅雷下载)<br/><br/>
							<span style="font-size:16px;color:blue"><?php echo $seed->getSeedName();?></span>
						 	<div style="background:orange"><a href="<?php  echo $seed->getThunder();?>"><?php  echo $seed->getThunder();?></a></div><br/>
						
						
						<?php  } ?>
			</div>                       
            

            
		</div>
		
	</div>