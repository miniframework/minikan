<?php $this->layout("admin_main");?><div class="container">
	<h3 class="marginbot">列表视频下载<a href="<?php echo $this->createUrl("admin","vdownloads","addview");?>" class="sgbtn">添加视频下载</a></h3>
	<div class="mainbox">
	    <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php } ?>		<form action="<?php echo $this->createUrl("admin","vdownloads","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
												<th>类型</th>
												<th>标题</th>
												<th>头图</th>
												<th>豆瓣图片</th>
												<th>年</th>
												<th>分类</th>
												<th>国家</th>
												<th>语言</th>
												
												<td>操作</td>
					</tr>
					<?php foreach($models as $k => $model) {?>					
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
						                        <td><?php echo $model->vtype;?></td>
                                                <td><?php echo $model->title;?></td>
                                                <td><a target="_blank" href="<?php echo $model->imagelink;?>">图片</a></td>
                                                <td><?php echo $model->doubanimage;?></td>
                                                <td><?php echo $model->year;?></td>
                                                <td><?php echo $model->cate;?></td>
                                                <td><?php echo $model->country;?></td>
                                                <td><?php echo $model->lang;?></td>
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