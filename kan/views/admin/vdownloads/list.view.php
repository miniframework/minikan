<?php $this->layout("admin_main");?><div class="container">
	<h3 class="marginbot">列表视频下载<a href="<?php echo $this->createUrl("admin","vdownloads","addview");?>" class="sgbtn">添加视频下载</a></h3>
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
			<form action="<?php echo $this->createUrl("admin","vdownloads","list");?>" method="post">
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
						<th></th>
						<td>
						<input type="hidden" value="<?php echo  $searchrow['ishidden'];?>" id="ishidden" name="ishidden">
						<input type="submit" value="提 交" class="btn"></td>
					</tr>
				</tbody></table>
			</form>
		
		</div>
		<div class="mainbox">
			<form action="<?php echo $this->createUrl("admin","vdownloads","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
												<th>ID</th>
												<th>类型</th>
												<th>标题</th>
												<th>头图</th>
												<th>时间</th>
												<th>年</th>
												<th>分类</th>
												<th>国家</th>
												<td>操作</td>
					</tr>
					<?php foreach($models as $k => $model) {?>					
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
												 <td><?php echo $model->id;?></td>
						                        <td><?php echo $model->vtype;?></td>
                                                <td><?php echo $model->title;?></td>
                                                <td><?php echo date("Y-m-d H:i",$model->ctime);?></td>
                                                <td><?php echo $model->doubanimage;?></td>
                                                <td><?php echo $model->year;?></td>
                                                <td><?php echo $model->cate;?></td>
                                                <td><?php echo $model->country;?></td>
                        						<td><a href="<?php echo $this->createUrl("admin","vdownloads","modifyview",array("id"=>$model->id));?>">编辑</a></td>
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