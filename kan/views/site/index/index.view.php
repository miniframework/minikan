<?php $this->layout('kan_main',array("title"=>"爱乐子在线电影,电视剧全集,最新动漫,在线观看",
									 "keywords"=>"在线电影,电视剧全集,最新动漫,视频聚合,爱乐子",
									 "description"=>"爱乐子-提供最全的最新最热门电影、电视剧、动漫、在线观看，以及聚合各大视频网站最新最全的电影、电视剧、动漫,让您和您的朋友同时观看各种聚合视频"
					));?>
<script>
            $(document).ready(function() {
				 $(".js_tab > h3").each(function(i){
					  $(this).mouseover(function(){
						  var type = $(this).attr('type');
						  $(".js_tab_"+type+ "> h3").each(function(i){
							  $(this).removeClass('on');
						  });
						  $(this).addClass('on');	
						  var type = $(this).attr('type');
						  var index= $(this).attr('index');
						  $("."+type+" > ul").each(function(i){
							  $(this).hide();
						  });
						  $("#"+type+index).show();	
					  })
				 });
			});
</script>
<div class="fm960 clearfix">
  <div class="left">
  
  
    <div class="gvideo">
      <div class="gvideo-hd mv-hd">
        <h2 class="tit"><a target="_blank" href="<?php echo $this->createUrl("site","kan","movie");?>">电影<span>Movie</span></a></h2>
        <div  class="act js_tab js_tab_mv">
	        <?php if(!empty($catalog_moives)) 
	        		 foreach($catalog_moives as $kk => $catalog_moive) {?>
	          <h3 <?php if($kk ==0) echo 'class="on"';?> index="<?php echo $kk;?>" type="mv">
	          	<?php echo $catalog_moive->showtitle;?>
	          </h3>
	        <?php } ?>
	         <h3  index="10" type="mv">最新</h3>
	         <h3  index="11" type="mv">爱情</h3>
	         <h3  index="12" type="mv">喜剧</h3>
	         <h3  index="13" type="mv">伦理</h3>
	         <h3  index="14" type="mv">微电影</h3>
	        <a target="_blank" href="<?php echo $this->createUrl("site","kan","movie");?>" class="more">更多&gt;&gt;</a> 
        </div>
      </div>
      
      <!-- rengong tuijian -->
      <?php if(!empty($catalog_moives)) { ?>
      <div class="gvideo-bd mv">
      	  <?php foreach($catalog_moives as $kk => $catalog_moive)  {?>
        <ul id="mv<?php echo $kk?>" class="gimglist" <?php if($kk !=0) echo 'style="display:none;"';?>>
        	<?php $regroups = $catalog_moive->getByRecommendByTypeid();
        		foreach($regroups as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        <?php }?>
        
          <!-- zuixing -->
        <ul id="mv10" class="gimglist" style="display:none;">
        	<?php
        		foreach($new_movies as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        <!-- aiqing -->
        <ul id="mv11" class="gimglist" style="display:none;">
        	<?php foreach($love_movies as $k => $v){?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        <!-- xiju -->
        <ul id="mv12" class="gimglist" style="display:none;">
        	<?php foreach($xiju_movies as $k => $v){?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
         <!-- xiju -->
        <ul id="mv13" class="gimglist" style="display:none;">
        	<?php foreach($lunli_movies as $k => $v){?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        
          <!-- wei -->
        <ul id="mv14" class="gimglist" style="display:none;">
        	<?php foreach($wei_movies as $k => $v){?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
      </div>
      <?php } ?>
      
      <div  class="movie-ft">
        <ul class="grecomlist">
        <?php if(!empty($vdownload10))
        		foreach($vdownload10 as $kk => $v) {?>
          <li><a target="_blank" title="<?php echo $v->title;?>" href="<?php echo $this->createUrl("site","kan","downdetail",array("id"=>$v->id)); ?>"><?php echo $v->title;?></a></li>
          <?php }?>
        </ul>
      </div>
    </div>
    
    
    
    <div class="gvideo">
      <div class="gvideo-hd tv-hd">
        <h2 class="tit"><a target="_blank" href="<?php echo $this->createUrl("site","kan","tv");?>">电视剧<span>Tv</span></a></h2>
        <div  class="act js_tab js_tab_tv">
        	<?php if(!empty($catalog_tvs)) 
	        		 foreach($catalog_tvs as $kk => $catalog_tv) {?>
	          <h3 <?php if($kk ==0) echo 'class="on"';?> index="<?php echo $kk;?>" type="tv">
	          	<?php echo $catalog_tv->showtitle;?>
	          </h3>
	        <?php } ?>
	         <h3  index="10" type="tv">最新</h3>
	         <h3  index="14" type="tv">美剧</h3>
	         <h3  index="11" type="tv">爱情</h3>
	         <h3  index="12" type="tv">喜剧</h3>
	         <h3  index="13" type="tv">偶像</h3>
          <a target="_blank"  href="<?php echo $this->createUrl("site","kan","tv");?>" class="more">更多&gt;&gt;</a> 
        </div>
      </div>
      <?php if(!empty($catalog_tvs)) { ?>
      <div class="gvideo-bd tv">
      	  <?php foreach($catalog_tvs as $kk => $catalog_tv)  {?>
        <ul id="tv<?php echo $kk?>" class="gimglist" <?php if($kk !=0) echo 'style="display:none;"';?>>
        	<?php $regroups = $catalog_tv->getByRecommendByTypeid();
        		foreach($regroups as $k => $v){
        	?>
          <li> 
          <a  target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        <?php }?>
         <!-- zuixing -->
        <ul id="tv10" class="gimglist" style="display:none;">
        	<?php 
        		foreach($new_tvs as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
         <!-- aiqing -->
        <ul id="tv11" class="gimglist" style="display:none;">
        	<?php 
        		foreach($love_tvs as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        <!-- xiju -->
        <ul id="tv12" class="gimglist" style="display:none;">
        	<?php 
        		foreach($xiju_tvs as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        <!-- ouxiang -->
        <ul id="tv13" class="gimglist" style="display:none;">
        	<?php 
        		foreach($ouxiang_tvs as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        
        <!-- meiju -->
        <ul id="tv14" class="gimglist" style="display:none;">
        	<?php 
        		foreach($usa_tvs as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
      </div>
      <?php } ?>
    </div>
    
    
    
    
    <div class="gvideo">
      <div class="gvideo-hd co-hd">
        <h2 class="tit"><a target="_blank" href="<?php echo $this->createUrl("site","kan","cartoon");?>">动漫<span>Cartoon</span></a></h2>
        <div  class="act js_tab js_tab_co">
          <?php if(!empty($catalog_comics)) 
	        		 foreach($catalog_comics as $kk => $catalog_comic) {?>
	          <h3 <?php if($kk ==0) echo 'class="on"';?> index="<?php echo $kk;?>" type="co">
	          	<?php echo $catalog_comic->showtitle;?>
	          </h3>
	             <h3  index="10" type="co">更新</h3>
	        <?php } ?>
          <a target="_blank" href="<?php echo $this->createUrl("site","kan","cartoon");?>" class="more">更多&gt;&gt;</a> </div>
      </div>
       
      <?php if(!empty($catalog_comics)) { ?>
      <div class="gvideo-bd co">
      	  <?php foreach($catalog_comics as $kk => $catalog_comic)  {?>
        <ul id="co<?php echo $kk?>" class="gimglist" <?php if($kk !=0) echo 'style="display:none;"';?>>
        	<?php $regroups = $catalog_comic->getByRecommendByTypeid();
        		foreach($regroups as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        <?php }?>
        
        <ul id="co10" class="gimglist" style="display:none;">
        	<?php 
        		foreach($new_cos as $k => $v){
        	?>
          <li> 
          <a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" class="avatar" > 
	          	<img src="<?php echo $v->getImageLink();?>" >
	            <span class="tip"></span> 
            </a>
            <h4 class="name"><a target="_blank" title="<?php echo $v->getShowTitle();?>"  href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>" ><?php echo $v->getShowTitle();?></a></h4>
            <p class="info"><?php echo $v->getShortcomment();?></p>
          </li>
          <?php }?>
        </ul>
        
      </div>
      <?php } ?>
      <div  class="comic-ft">
        <ul class="grecomlist">
           <?php if(!empty($co_botton_recs))
        		foreach($co_botton_recs as $kk => $co_botton) {
        			$reco = $co_botton->getByRecommendByTypeid();
        				foreach($reco as $k => $v){
        			?>
        
          <li><a target="_blank" href="<?php echo $this->createUrl("site","kan","player",array("id"=>$v->id)); ?>"><?php echo $v->getShowTitle();?></a></li>
          <?php }}?>
        </ul>
      </div>
    </div>
  </div>
  
  
  <div class="right">
    <div class="mod_a">
      <div class="th_a"><span class="th_mark">电影top10</span></div>
      <div class="tb_a">
        <ul class="list clearfix">
          <?php if(!empty($movietop10)) foreach($movietop10 as $k => $movietop) {?>
          <li> <em class="index top"><?php echo ($k+1);?></em> <span class="videoname"><a target="_blank" title="<?php echo $movietop->getShowTitle();?>" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$movietop->id));?>" ><?php echo $movietop->getShowTitle();?></a></span> <span class="info"><span class="orange_num"><?php echo $movietop->rate;?></span>分</span> </li>
          <?php }?>
        </ul>
      </div>
    </div>
    <div class="mod_a">
      <div class="th_a"><span class="th_mark">电影筛选</span></div>
      <h3 class="subtit">按类型</h3>
      <ul class="dlist clearfix">
        <?php $i = 0; foreach($vgroup->cateMap(1) as $k => $cate) { if($i==20) break;?>
        <li><a target="_blank" title="<?php echo $cate;?>" <?php if(array_key_exists($cate, $colorCate[1])) {echo 'style="'.$colorCate[1][$cate].'"';}?>  href="<?php echo $this->createUrl('site','kan','movie',array(),array('cate'=>$k));?>"><?php echo $cate;?></a></li>
        <?php $i++;}?>
      </ul>
      <h3 class="subtit">按地区</h3>
      <ul class="dlist clearfix">
        <?php $i = 0; foreach($vgroup->areaMap(1) as $k => $area) { if($i==8) break;?>
        <li><a target="_blank" title="<?php echo $area;?>" href="<?php echo $this->createUrl('site','kan','movie',array(),array('area'=>$k));?>"><?php echo $area;?></a></li>
        <?php $i++;}?>
      </ul>
      <h3 class="subtit">按年代</h3>
      <ul class="dlist clearfix">
        <?php $i = 0; foreach($vgroup->yearMap() as $k => $year) { if($i==7) break;?>
        <li><a target="_blank" title="<?php echo $year;?>年"  href="<?php echo $this->createUrl('site','kan','movie',array(),array('year'=>$k));?>"><?php echo $year;?></a></li>
        <?php $i++;}?>
        <li><a target="_blank" href="#">更早</a></li>
      </ul>
    </div>
    <div class="mod_a">
      <div class="th_a"><span class="th_mark">电视剧热播榜</span></div>
      <div class="tb_a">
        <ul class="list clearfix">
          <?php if(!empty($tvtop10)) foreach($tvtop10 as $k => $tvtop) {?>
          <li> <em class="index top"><?php echo ($k+1);?></em> <span class="videoname"><a target="_blank" title="<?php echo $tvtop->getShowTitle();?>" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$tvtop->id));?>" ><?php echo $tvtop->getShowTitle();?></a></span> <span class="info"><?php echo $tvtop->getEpSignforShortStr();?></span></li>
          <?php }?>
        </ul>
      </div>
    </div>
    <div class="mod_a clearfix">
      <div class="th_a"><span class="th_mark">电影筛选</span></div>
      <h3 class="subtit">按类型</h3>
      <ul class="dlist clearfix">
        <?php $i = 0; foreach($vgroup->cateMap(2) as $k => $cate) { if($i==20) break;?>
        <li><a target="_blank" title="<?php echo $cate;?>" <?php if(array_key_exists($cate, $colorCate[2])) {echo 'style="'.$colorCate[2][$cate].'"';}?>  href="<?php echo $this->createUrl('site','kan','tv',array(),array('cate'=>$k));?>"><?php echo $cate;?></a></li>
        <?php $i++;}?>
      </ul>
      <h3 class="subtit">按地区</h3>
      <ul class="dlist clearfix">
        <?php $i = 0; foreach($vgroup->areaMap(2) as $k => $area) { if($i==8) break;?>
        <li><a target="_blank" title="<?php echo $area;?>"  href="<?php echo $this->createUrl('site','kan','tv',array(),array('area'=>$k));?>"><?php echo $area;?></a></li>
        <?php $i++;}?>
      </ul>
      <h3 class="subtit">按年代</h3>
      <ul class="dlist clearfix">
        <?php $i = 0; foreach($vgroup->yearMap() as $k => $year) { if($i==7) break;?>
        <li><a target="_blank" title="<?php echo $year;?>年"  href="<?php echo $this->createUrl('site','kan','tv',array(),array('year'=>$k));?>"><?php echo $year;?></a></li>
        <?php $i++;}?>
        <li><a target="_blank" href="＃">更早</a></li>
      </ul>
    </div>
    <div class="mod_a">
      <div class="th_a"><span class="th_mark">热播动漫</span></div>
      <div class="tb_a">
        <ul class="list clearfix">
          <?php if(!empty($comictop10)) foreach($comictop10 as $k => $comictop) {?>
          <li> <em class="index top"><?php echo ($k+1);?></em> <span class="videoname"><a target="_blank" title="<?php echo $comictop->getShowTitle();?>" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$comictop->id));?>" ><?php echo $comictop->getShowTitle();?></a></span> <span class="info"><?php echo $comictop->getEpSignforShortStr();?></span></li>
          <?php }?>
        </ul>
      </div>
    </div>
  </div>
</div>