<?php $this->layout("admin_main");?>
<script src="/styles/admin/js/calendar.js" type="text/javascript"></script>
<div id="append"></div>
<div class="container">
	<h3 class="marginbot">列表视频聚合<a href="<?php echo $this->createUrl("admin","vgroups","addview");?>" class="sgbtn">添加视频聚合</a></h3>

	<script>
	 $(document).ready(function() {
		 if($("#ishidden").val()) {
			 $("#searchpmdiv").show();
		 } else {
			 $("#searchpmdiv").hide();
		 }
		 $(".tabcurrent").click(function(){
			if($("#searchpmdiv").is(":hidden"))	{
				$("#searchpmdiv").show();
				$("#ishidden").val(1);
			} else {
				$("#searchpmdiv").hide();
				$("#ishidden").val(0);
			}
		});
	 });

	</script>
		 <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
				<p><em><?php echo $firsterror;?></em></p>
			</div>
			<?php } ?>
	<div class="hastabmenu" >

		<ul class="tabmenu">
			<li class="tabcurrent"><a href="javascript:;" >视频聚合搜索</a></li>
		</ul>
		<div id="searchpmdiv" class="tabcontentcur" style="display:none">
			<form action="<?php echo $this->createUrl("admin","vgroups","list");?>" method="post">
				<table class="dbtb">
					<tbody><tr>
						<th class="tbtitle">ID:</th>
						<td><input type="text" name="id" class="txt" value="<?php echo $searchrow['id'];?>"></td>
					</tr>
					<tr>
						<th class="tbtitle">标题:</th>
						<td><input type="text" name="title" class="txt" value="<?php echo $searchrow['title'];?>"></td>
					</tr>
					<tr>
						<th class="tbtitle">类型:</th>
						<td><input type="radio" name="vtype"  <?php if(empty($searchrow['vtype'])) echo 'checked="checked"'; ?> value="0">全部
							<input type="radio" name="vtype" <?php if($searchrow['vtype']==1) echo 'checked="checked"'; ?> value="1">电影
							<input type="radio" name="vtype" <?php if($searchrow['vtype']==2) echo 'checked="checked"'; ?> value="2">电视剧
							<input type="radio" name="vtype" <?php if($searchrow['vtype']==3) echo 'checked="checked"'; ?>  value="3">动漫
						</td>
					</tr>
					<!-- 
					<tr>
						<th class="tbtitle">时间范围:</th>
						<td><input type="text" name="srchstarttime" class="txt" style="margin-right: 0;" value="" onclick="showcalendar();"> - <input type="text" name="srchendtime" class="txt" value="" onclick="showcalendar();"></td>
					</tr>
					 -->
					<tr>
						<th></th>
						<td>
						<input type="hidden" value="<?php echo  $searchrow['ishidden'];?>" id="ishidden" name="ishidden">
						<input type="submit" value="提 交" class="btn"></td>
					</tr>
				</tbody></table>
			</form>
			<form action="<?php echo $this->createUrl("admin","vgroups","mergin");?>" method="post">
				<table class="dbtb">
					<tbody><tr>
						<th class="tbtitle">归并：</th>
						<td><input type="text" name="fromid" class="txt" value="">-->
							<input type="text" name="toid" class="txt" value="">
							<input type="submit" value="mergin" class="btn"></td>
						</td>
					</tr>
				</tbody></table>
			</form>
		</div>
	
	<script>

	 $(document).ready(function() {

		 var hoturl = "<?php echo $this->createUrl("admin","vgroups","hotset");?>";
		 $("input[name='hot']").each(function(i){
				$(this).focusout(function(){
						var hotid = $(this).attr('id');
						var ids = new Array();
						ids = hotid.split("_");
						var groupid = ids[1];
						var firhot = $("#hot_"+groupid+"_1").val();
						var midhot = $("#hot_"+groupid+"_2").val();
						var endhot = $("#hot_"+groupid+"_3").val();
						$.ajax({
							   type: "get",
							   url: hoturl,
							   data: {'id':groupid,'firhot':firhot,'midhot':midhot,'endhot':endhot},
							   success: function(msg){
							    	
							   }
							});

						$(this).css({ border: "1px solid rgb(181, 207, 217)",color:'rgb(158, 190, 203)'});
					})

			 });
		
	}); 

	</script>
	
		<div class="mainbox">
		   		<form action="<?php echo $this->createUrl("admin","vgroups","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
					<table class="datalist"  onmouseover="addMouseEvent(this);">
						<tr>
							<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
							<label for="chkall">删除</label>
							</th>
													<th>ID</th>
													<th>类型</th>
													<th>标题</th>
													<th>热度</th>
													<th>来源</th>
													<th>分类</th>
													<th>地区</th>
													<th>年</th>
													<th>豆瓣info</th>
													<th>分</th>
													<th>导演</th>
													<td>操作</td>
						</tr>
						<?php foreach($models as $k => $model) {?>					
						<tr>
							<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
													<td><?php echo $model->id;?></td>
							                        <td><?php echo $video->vtypeMap($model->vtype);?></td>
	                                                <td><?php echo $model->title;?></td>
	                                                <td>
	                                                	<?php $hot = $model->hot;
	                                                		$firhot = substr($hot,1,2);
	                                                		$midhot = substr($hot,3,2);
	                                                		$endhot = substr($hot,5);
	                                                	
	                                                	?>
	                                                	<input style="width:20px;" type="text" id="hot_<?php echo $model->id;?>_1" value="<?php echo $firhot;?>" name="hot"/>
	                                                	<input style="width:20px;" type="text" id="hot_<?php echo $model->id;?>_2" value="<?php echo $midhot;?>" name="hot"/>
	                                                	<input style="width:40px;" type="text" id="hot_<?php echo $model->id;?>_3" value="<?php echo $endhot;?>" name="hot"/>
	                                                </td>
	                                                <td>
	                                                <?php $videoids = $model->getVideoids() ; foreach($videoids as $siteid => $videoid) {?>
	                                                <a target="_blank" href="<?php echo $videoid['playlink'];?>" >
	                                               	 <img src="/styles/kan/images/icon/<?php echo $video->getIcon($siteid);?>" />
	                                                </a>
	                                                <?php }?>
	                                                </td>
	                                                <td><?php echo $model->cate;?></td>
	                                                <td><?php echo $model->area;?></td>
	                                                <td><?php echo $model->year;?></td>
	                                                <td><?php echo empty($model->doubanid) ? "":'豆瓣';?></td>
	                                                <th><?php echo $model->rate;?></th>
	                                                <td><?php echo $model->director;?></td>
	                        						<td><a href="<?php echo $this->createUrl("admin","vgroups","modifyview",array("id"=>$model->id));?>">编辑</a>|
	                        							<a href="javascript:window.open('<?php echo $this->createUrl("admin","vgroups","doubanidview",array("id"=>$model->id));?>', '_blank','width=600,height=400');void(0);">获取</a>
	                        						</td>
						</tr>
						<?php } ?>					<tr class="nobg">
							<td><input type="submit" value="删 除" class="btn"></td>
							<td class="tdpage"></td>
						</tr>		
					</table>
					<p class="page_list"><?php echo $page->pageHtml();?></p>
			</form>
		</div>
	</div>
</div>