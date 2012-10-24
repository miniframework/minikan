<?php $this->layout("admin_main");?><div class="container">
	<h3 class="marginbot">列表视频信息<a href="<?php echo $this->createUrl("admin","videos","addview");?>" class="sgbtn">添加视频信息</a></h3>
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
			<li class="tabcurrent"><a href="javascript:;" >视频搜索</a></li>
		</ul>
		<div id="searchpmdiv" class="tabcontentcur" style="display:none">
			<form action="<?php echo $this->createUrl("admin","videos","list");?>" method="post">
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
					<tr>
						<th class="tbtitle">状态:</th>
						<td><input type="radio" name="status"  <?php if($searchrow['status']==0) echo 'checked="checked"'; ?> value="0">全部
							<input type="radio" name="status" <?php if($searchrow['status']==1) echo 'checked="checked"'; ?> value="1">已聚合
							<input type="radio" name="status" <?php if($searchrow['status']==2) echo 'checked="checked"'; ?> value="2">未聚合
							<input type="radio" name="status" <?php if($searchrow['status']==3) echo 'checked="checked"'; ?>  value="3">链接删除
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
		</div>
		<div class="mainbox">
			<form action="<?php echo $this->createUrl("admin","videos","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
					<table class="datalist"  onmouseover="addMouseEvent(this);">
						<tr>
							<th>ID
							</th>
													<th>来源</th>
													<th>聚合id</th>
													<th>类型</th>
													<th>状态</th>
													<th>标题</th>
													<th>分类</th>
													<th>地区</th>
													<th>品质</th>
													<th>评分</th>
													<th>年</th>
													<td>操作</td>
						</tr>
						<?php foreach($models as $k => $model) {?>					
						<tr>
							<td><?php echo $model->id;?></td>
							                        <td><a href="<?php echo $model->playlink;?>" target="_blank" ><img src="/styles/kan/images/icon/<?php echo $model->getIcon($model->siteid);?>"/></a></td>
	                                                <td><a href="<?php echo $this->createUrl("admin","vgroups","modifyview",array("id"=>$model->vgroupid));?>"><?php echo $model->vgroupid;?></a></td>
	                                                <td><?php echo $model->vtypeMap($model->vtype);?></td>
	                                                <td><?php echo $model->status;?></td>
	                                                <td><?php echo $model->title;?></td>
	                                                <td><?php echo $model->cate;?></td>
	                                                <td><?php echo $model->area;?></td>
	                                                <td><?php echo $model->quality;?></td>
	                                                <td><?php echo $model->score;?></td>
	                                                <td><?php echo $model->year;?></td>
	                        						<td><a href="<?php echo $this->createUrl("admin","videos","modifyview",array("id"=>$model->id));?>">编辑</a></td>
						</tr>
						<?php } ?>					<tr class="nobg">
							<td></td>
							<td class="tdpage"></td>
						</tr>		
					</table>
					<p class="page_list"><?php echo $page->pageHtml();?></p>
			</form>
		</div>
	</div>
</div>