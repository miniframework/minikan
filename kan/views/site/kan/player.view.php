<?php if($vgroup->vtype==1){
	$header_title = "《".$vgroup->title."》高清在线观看－电影－爱乐子电影";
} else if($vgroup->vtype ==2) {
	$header_title = "《".$vgroup->title."》全集在线观看－电视剧－爱乐子电视剧";
} else if($vgroup->vtype ==3) {
	$header_title = "《".$vgroup->title."》全集在线观看-动漫－爱乐子动漫";
} ?>
<?php $this->layout('kan_main',array("title"=>$header_title));?>

	<div class="flv_player fm960 clearfix" id="flv_player_now"   style="height:530px;display:none" >
		<div id="flash_play"></div>
		<div class="fm960 clearfix" id="play_info">
			<div class="web_play">(如无法播放)
				<a class="web_playlink" target="_blank" href="#">
					点击<font style="color:#fff">官网</font>播放
				</a>
			</div>
			<div style="float:left;width:600px; height:30px;"></div>
		</div>
	</div>

	<img id="pimglink" src="#" style="position: absolute;z-index:10;display:none;" />
	
	<div class="detail fm960 clearfix">
		<div class="left_detail">
			<div class="poster">
				<a href=""><img style="width:230px;height:307px"  src="<?php echo $vgroup->getBigImageLink();?>"></a>
			</div>
		</div>
		<div class="right_detail">
		 <script type="text/javascript"  src="/styles/kan/js/swfobject/swfobject.js" ></script>
		 <script>
			$(document).ready(function() {
		        var flashvars = {};
		        var params = {};  
		        var attributes = {};
		        var flash_id = "flash_play";
				var builderTudouFlash = function (sid, iid){
					/*
					var embed ='<embed src="http://www.tudou.com/a/'+sid+'/&resourceId=0_05_05_99&iid='+iid+'&bid=05/v.swf" \
					 					type="application/x-shockwave-flash" \
						 				allowscriptaccess="always"  \
						 				allowfullscreen="true"  \
						 				wmode="opaque"  \
						 				width="960"	\
						 				height="500"></embed>';
	 				
			 		return embed;
					*/
			 		var src='http://www.tudou.com/a/'+sid+'/&resourceId=0_05_05_99&iid='+iid+'&bid=05/v.swf';
					params.type="application/x-shockwave-flash";
					params.quality = "high";
					params.align="middle";
					params.wmode="opaque"
					params.allowfullscreen="true";
					params.allownetworking="all";
					params.allowscriptaccess="always";
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					
					/* new play tudou, not share
					var src='http://js.tudouui.com/bin/player_online/TudouVideoPlayer_Homer_NewSkin_25.swf';
					params.type="application/x-shockwave-flash";
					params.quality = "high";
					params.align="middle";
					params.wmode="opaque"
					params.allowfullscreen="true";
					params.allownetworking="all";
					params.allowscriptaccess="always";
					flashvars.iid=iid;
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					*/
					return true;
				}
				var builderYoukuFlash = function (sid){

					/*
					var embed ='<embed src="http://player.youku.com/player.php/sid/'+sid+'/v.swf?autoplay=1" \
										allowFullScreen="true" \
										quality="high" \
										width="960" \
										height="500" \
										align="middle" \
										allowScriptAccess="always"\
										type="application/x-shockwave-flash"></embed>';
			 		return embed;
			 		*/
			 		var src='http://player.youku.com/player.php/sid/'+sid+'/v.swf?auto=1';
					params.type="application/x-shockwave-flash";
					params.quality = "high";
					params.align="middle";
					params.allowfullscreen="true";
					params.allownetworking="all";
					params.allowscriptaccess="always";
					flashvars.isAutoPlay="true";
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					return true;
				}
				var builderM1905Flash = function(pid)
				{
					/*
					var embed ='<embed type="application/x-shockwave-flash" src="http://static.m1905.com/v/20120627/vp.swf" \
								width="960" \
								height="500" \
								bgcolor="#FFFFFF" \
								quality="high" \
								allowscriptaccess="always" \
								allownetworking="all" \
								allowfullscreen="true" \
								flashvars="configUrl=http://static.m1905.com/profile/vod'+pid+'"></embed>';
					return embed;
					*/
					var src='http://static.m1905.com/v/20120627/vp.swf';
					params.type="application/x-shockwave-flash";
					params.quality = "high"
					params.bgcolor = "#FFFFFF"
					params.allowfullscreen="true";
					params.allownetworking="all";
					params.allowscriptaccess="always";
					flashvars.configUrl='http://static.m1905.com/profile/vod'+pid;
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					return true;
				}
				var builderSohuFlash = function(vid)
				{
					/*
					var embed ='<object width="960" height="500"> \
								<param name="movie" value="http://share.vrs.sohu.com/'+vid+'/v.swf&autoplay=true&xuid="> \
								</param><param name="allowFullScreen" value="true"> \
								</param><param name="allowscriptaccess" value="always"> \
								</param>\
								<embed \
								width="960" \
								height="500" \
								allowfullscreen="true" \
								allowscriptaccess="always" \
								quality="high" \
								src="http://share.vrs.sohu.com/'+vid+'/v.swf&autoplay=true&xuid=" \
								type="application/x-shockwave-flash"/></embed></object>';
					return embed;
					*/
					var src='http://share.vrs.sohu.com/'+vid+'/v.swf&autoplay=true';
					params.type="application/x-shockwave-flash";
					params.quality = "high"
					params.allowfullscreen="true";
					params.allowscriptaccess="always";
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					return true;
				}
				var builderPPtvFlash = function(sid)
				{
					/*
					var embed ='<embed src="http://player.pptv.com/v/'+sid+'.swf" \
								quality="high"  \
								width="960" \
								height="500" \
								align="middle"  \
								allowScriptAccess="always"  \
								allownetworking="all"  \
								type="application/x-shockwave-flash"  \
								wmode="window"  \
								allowFullScreen="true"></embed>';
					return embed;
					*/
					var src='http://player.pptv.com/v/'+sid+'.swf';
					params.type="application/x-shockwave-flash";
					params.quality = "high"
					params.align="middle";
					params.wmode="window";
					params.allownetworking="all";
					params.allowscriptaccess="always";
					params.allowFullScreen="true"
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					return true;
				}
				var builderSinaFlash = function(vid)
				{
					/*
					var embed='<object width="960" height="500"> \
								<param name="allowScriptAccess" value="always">	\
								<embed pluginspage="http://www.macromedia.com/go/getflashplayer" \
								src="http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid='+vid+'_0/s.swf"	\
								type="application/x-shockwave-flash" \
								name="ssss" wmode="transparent"	\
								allowfullscreen="true" \
								allowscriptaccess="always" \
								width="960" height="500"> \
								</object>';
						return embed;
					*/
					var src='http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid='+vid+'_0/s.swf';
					params.type="application/x-shockwave-flash";
					params.value = "always"
					params.name="ssss";
					params.wmode="transparent";
					params.allowfullscreen="always";
					params.allowscriptaccess="always";
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					return true;
					
				}
				//object有问题 chrome
				var builderLetvFlash = function(vid)
				{
					/*
					var embed='<embed src="http://i7.imgs.letv.com/player/swfPlayer.swf?id='+vid+'&autoplay=1" \
								width="960" height="500"  \
								allowFullScreen="true" \
								type="application/x-shockwave-flash" /> \
								</object>';
					return embed;
					*/
					var src = "http://i7.imgs.letv.com/player/swfPlayer.swf";
					flashvars.id=vid;
					flashvars.autoplay="1";
					params.allowFullScreen="true";
					swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
							"/styles/kan/js/swfobject/expressInstall.swf",
							flashvars,params,attributes);
					return true;
				}
				var builderQqFlash = function(vid)
				{
						var src = "http://static.video.qq.com/TPout.swf?vid="+vid+"&auto=1";
						params.allowFullScreen="true";
						params.allowScriptAccess="always";
						params.align = "middle";
						params.quality = "high";
						swfobject.embedSWF(src, flash_id, "960", "500", "9.0.0", 
								"/styles/kan/js/swfobject/expressInstall.swf",
								flashvars,params,attributes);
						return true;
				}
				
				var buildflash= function(siteid, ep) {
					
				 	for(var i = 0; i <player.length ;i++) {
				 		if(siteid == player[i].siteid) {
				 			if(ep != 0) ep = ep-1;
				 			$(".web_playlink").attr("href",player[i].flv[ep].playlink);
				 			if(siteid ==1) {
				 				var sid = player[i].flv[ep].flv.sid;
				 				var iid = player[i].flv[ep].flv.iid;
				 			    return builderTudouFlash(sid, iid);
				 			}
				 			else if(siteid ==2)
				 			{
				 				var sid = player[i].flv[ep].flv.sid;
				 				return builderYoukuFlash(sid);
				 				
				 			}
				 			else if(siteid ==4)
				 			{
				 				var vid = player[i].flv[ep].flv.vid;
				 				return builderSinaFlash(vid);
				 				
				 			}
				 			else if(siteid == 5)
				 			{
				 				var pid = player[i].flv[ep].flv.pid;
				 				return builderM1905Flash(pid);
				 			}
				 			else if(siteid == 6)
				 			{
				 				var vid = player[i].flv[ep].flv.vid;
				 				return builderSohuFlash(vid);
				 			}
				 			else if(siteid == 7)
				 			{
				 				var sid = player[i].flv[ep].flv.sid;
				 				return builderPPtvFlash(sid);
				 			}
				 			else if(siteid == 8)
				 			{
				 				var vid = player[i].flv[ep].flv.vid;
				 				return builderLetvFlash(vid);
				 			}
				 			else if(siteid == 9)
				 			{
				 				var vid = player[i].flv[ep].flv.vid;
				 				return builderQqFlash(vid);
				 			}
				 			break;
				 			
				 		}
				 	}
				};
				var BuildImage=function(siteid,ep)
				{
					for(var i = 0; i <player.length ;i++) {
				 		if(siteid == player[i].siteid) {
				 			if(ep != 0) ep = ep-1;
					 		if(typeof(player[i].flv[ep].imagelink) != "undefined" && player[i].flv[ep].imagelink != ''
					 			&& player[i].flv[ep].imagelink!= "http://css.tudouui.com/skin/play/img/b_0.gif"
							 )
					 		{
								$("#pimglink").attr("src",player[i].flv[ep].imagelink);
					 			$("#pimglink").show();
					 		}
					 	}
					}
				};
				var showFlashParams = function(siteid, ep)
				{
					$("#flv_player_now").hide();
					buildflash(siteid,ep);
					$("#flv_player_now").slideDown("slow");
				};
				var showFlash = function (obj)
				{
					var siteid = $(obj).attr('siteid');
					var ep = $(obj).attr('ep');
					$("#flv_player_now").hide();
					//$("#flv_player_now").html(buildflash(siteid,ep));
					buildflash(siteid,ep);
					$("#flv_player_now").slideDown("slow");
				};
				var showImage = function(obj)
				{
					var siteid = $(obj).attr('siteid');
					var ep = $(obj).attr('ep');
					BuildImage(siteid,ep);
				};
				 $.extend({showFlashParams:showFlashParams});
				 $.extend({showFlash:showFlash});
				 $.extend({showImage:showImage});
			});
			 $(document).ready(function() {
				<?php if($autoplay == true) {?>
						$.showFlashParams(<?php echo $playsite;?>, <?php echo $playepisode;?>);
				<?php } ?>
			});
			</script>
			<?php if($vgroup->isMovie()) {?>
				<?php $this->partial("player.moive.htm",array("vgroup"=>$vgroup,'video'=>$video)); ?>
            <?php } else if($vgroup->isTv()){?>
            	<?php $this->partial("player.tv.htm",array("vgroup"=>$vgroup,'video'=>$video)); ?>
            <?php } else if($vgroup->isCartoon()){?>
            	<?php $this->partial("player.cartoon.htm",array("vgroup"=>$vgroup,'video'=>$video)); ?>
            <?php }?>
           
            
            <script>
            $(document).ready(function() {
				 $("#tab_select > a").each(function(i){
					  
						$(this).click(function(){
							
							$("#tab_select > a").each(function(i){
								
								 $(this).removeClass('selected');
							});
							var type = $(this).attr('type');
							$(this).addClass('selected');	
							$("#tab_content > ul").each(function(i){
								
								 $(this).hide();
								 if($(this).attr('type') == type)
								 {
									 $(this).show();
								 }
							});
							
				 		});

				 });
				 	
			});
            </script>

            
            <div class="v-related">
				<div id="tab_select" class="clearfix">
					<a href="javascript:void(0);"  class="tab selected"  type="love" >你或许也喜欢</a>	
					<?php $samestars =  $vgroup->getSameStar(); if(!empty($samestars)){?>				
					<a href="javascript:void(0);"  class="tab"  type="star">该片主演作品</a>
					<?php }?>
					<?php $samedirectors =  $vgroup->getSameDirector();if(!empty($samedirectors)){?>
					<a href="javascript:void(0);"  class="tab"  type="director">该片导演作品</a>
					<?php }?>
				</div>
				<div id="tab_content">
					<ul class="list clearfix" type="love" >
						<?php $yourlikes = $vgroup->getYourLike();
							 foreach($yourlikes as $k => $yourlike){
						?>
						<li class="item">
							<a class="poster-link" title="<?php echo $yourlike->title;?>" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$yourlike->id));?>" >
								<img class="poster-img" src="<?php echo $yourlike->getImageLink()?>">
								<div class="hide-bg"></div>
								<div class="hide-text">
									<span class="trait"><?php echo $yourlike->year;?>年</span>							
									<!--  <span class="grade"><em><?php echo $yourlike->getRate();?></em>分</span>-->
								</div>
							</a>
							<h2 class="title"><a title="<?php echo $yourlike->title;?>" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$yourlike->id));?>" ><?php echo $yourlike->title;?></a></h2>
							<p class="text"><?php echo $yourlike->star;?></p>
						</li>
						<?php }?>
				
					</ul>
					<?php  if(!empty($samestars)) {?>
					<ul class="list clearfix" type="star" style="display: none; ">
						<?php foreach($samestars as $k => $samestar) {?>
						<li class="item">
							<a class="poster-link" title="<?php echo $samestar->title;?>" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$samestar->id));?>" >
								<img class="poster-img" src="<?php echo $samestar->getImageLink()?>">
								<div class="hide-bg"></div>
								<div class="hide-text">
									<span class="trait"><?php echo $samestar->year;?>年</span>							
									<!-- <span class="grade"><em><?php echo $samestar->getRate();?></em>分</span> -->
								</div>
							</a>
							<h2 class="title"><a title="<?php echo $samestar->title;?>" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$samestar->id));?>" ><?php echo $samestar->title;?></a></h2>
							<p class="text"><?php echo $samestar->star;?></p>
						</li>
						<?php }?>
					<?php }?>
					</ul>
					<?php  if(!empty($samedirectors)) {?>
					<ul class="list clearfix" type="director" style="display: none; ">
						<?php foreach($samedirectors as $k => $samedirector) {?>
						<li class="item">
							<a class="poster-link" title="<?php echo $samedirector->title;?>" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$samedirector->id));?>" >
								<img class="poster-img" src="<?php echo $samedirector->getImageLink()?>">
								<div class="hide-bg"></div>
								<div class="hide-text">
									<span class="trait"><?php echo $samedirector->year;?>年</span>							
									<!-- <span class="grade"><em><?php echo $samedirector->getRate();?></em>分</span>-->
								</div>
							</a>
							<h2 class="title"><a title="<?php echo $samedirector->title;?>" target="_self" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$samedirector->id));?>"><?php echo $samedirector->title;?></a></h2>
							<p class="text"><?php echo $samedirector->star;?></p>
						</li>
						<?php }?>
					<?php }?>
					</ul>
				</div>
			</div>
			<script>
			 $(document).ready(function() {
				 $(".more-link").click(function(){
					var id = $(this).attr('id');
					var id_arr =id.split("_"); 
					var showid = "s_"+id;
					if(id_arr[0] == 'less')
					{
						var cid='more';
					}
					else
					{
						var cid='less';
					}
					var hidid = "s_"+cid+"_"+id_arr[1];
					$("#"+hidid).hide();
					$("#"+showid).show();
				});
			 });

			</script>
			<?php if(!empty($reviews)) {?>
			<div class="reviews">
				<?php foreach($reviews as $k => $review) {?>
				<ul class="list">
					<li class="item">
						<div class="title-bg">
							<a target="_blank" href="<?php echo $review->getDoubanUrl();?>" class="title-link">
								<h4 class="title"><?php echo $review->title;?></h4>
							</a>
						</div>
						<p  id="s_less_<?php echo $review->id;?>"  class="text"><?php echo $review->getCutSummary(260);?><a id="more_<?php echo $review->id;?>"  href="javascript:void(0);" class="more-link">全部&gt;&gt;</a></p>
						<p  id="s_more_<?php echo $review->id;?>" style="display:none" class="text"><?php echo $review->summary;?><a id="less_<?php echo $review->id;?>" href="javascript:void(0);" class="more-link">收起&gt;&gt;</a></p>
					</li>
				</ul>
				<?php }?>
			</div>
			<?php }?>
			
		</div>
		
	</div>
