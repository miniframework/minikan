<?php 
$header_keywords = $keyword."在线电影,".$keyword."在线动漫,".$keyword."在线电视剧";
$header_description = "最全的".$keyword."在线电影,动漫,电视剧信息";
$this->layout('kan_main',array("title"=>$keyword."在线播放-爱乐子",'keywords'=>$header_keywords,'description'=>$header_description));?>
<div class="search_list fm960 clearfix">
  <div class="search_left">
    <ul class="clearfix">
      <?php if(!empty($vgroups)) foreach($vgroups as $k => $vgroup){?>
      <li class="list"> <a target="_blank" class="pic" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$vgroup->id));?>"> <img src="<?php echo $vgroup->getImageLink();?>">
        <!--  <span>高清 01:24:23</span>	-->
        </a>
        <div class="content">
          <h2> <a  target="_blank" class="title" href="<?php echo $this->createUrl('site','kan','player',array('id'=>$vgroup->id));?>"><?php echo $vgroup->getShowTitle();?></a> <em>[</em><strong style="color:green"><?php echo $vgroup->getVtypezh();?></strong><em>]</em> </h2>
          <?php if($vgroup->vtype!=3) {?>
          <p class="detail_list"> <span>主演：</span><em>
            <?php foreach($vgroup->getStars() as $k => $star){?>
            <?php echo $star;?>
            <?php }?>
            </em> </p>
          <?php }?>
          <p class="detail_list"> <span>类型：</span><em><?php echo $vgroup->cate;?></em> <span>地区：</span><em><?php echo $vgroup->area;?></em> </p>
          <p class="detail_list">
          <?php if($vgroup->vtype!=3) {?>
           <span>导演：</span><em><a href="#"><?php echo $vgroup->director;?></a></em> 
          <?php }?> 
           <span>年份：</span><em><?php echo $vgroup->year;?></em>
            <!--  <span>片长：</span><em>01:24:23</em>-->
          </p>
          <p class="detail_list" > <span class="more">简介：</span> <em class="more"><?php echo $vgroup->getCutSummary(110);?></a> </em> </p>
        </div>
      </li>
      <?php } else {?>
      
<style>
#noresult {
padding: 60px 0 50px;
padding-left: 100px;
width: 400px;
margin: 0 auto;
background: url(/styles/kan/images/noresult.png) 0 center no-repeat;
}
#noresult .red {
color: red;
}
#noresult .noresult-hd {
padding-bottom: 20px;
line-height: 20px;
font-size: 14px;
}
#noresult .noresult-bd {
line-height: 26px;
}  
#noresult a {
color: rgb(4, 120, 179);
}
</style>
      <div id="noresult">
            <p class="noresult-hd">抱歉，没有找到“<span class="red"><?php echo $keyword;?></span>”的相关视频</p>
            <ul class="noresult-bd">
                <li class="title">建议您:</li>
                <li><span class="dot">·</span>检查输入的关键词是否正确</li>
                <li><span class="dot">·</span>给我们<a href="＃" target="_blank">留言求片</a></li>
                <li><span class="dot">·</span>去<span class="blue"><a href="<?php echo $this->createUrl('site','kan','movie');?>" target="_blank">电影</a>、
                <a href="<?php echo $this->createUrl('site','kan','tv');?>" target="_blank">电视剧</a>、
                <a href="<?php echo $this->createUrl('site','kan','cartoon');?>" target="_blank">动漫</a></span>寻找喜欢的内容</li>
            </ul>
        </div>
      <?php }?>
    </ul>
    <div class="page_list"> <?php echo $page->pageHtml();?> </div>
  </div>
  <div class="search_right">
    <div class="mod_a">
      <div class="th_a"><span class="th_mark">电影top10</span></div>
      <div class="tb_a">
        <ul class="list clearfix">
          <?php if(!empty($movietop10)) foreach($movietop10 as $k => $movietop) {?>
          <li> <em class="index top"><?php echo ($k+1);?></em> <span class="videoname"><a href="/m/fqXnYkn6QHr8Tx.html" ><?php echo $movietop->title;?></a></span> <span class="info"><span class="orange_num"><?php echo $movietop->rate;?></span>分</span> </li>
          <?php }?>
        </ul>
      </div>
    </div>
  </div>
</div>
