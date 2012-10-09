<?php $this->layout("admin_main");?><div class="container">
	<h3 class="marginbot">列表电影分组<a href="<?php echo $this->createUrl("admin","catalogs","addview");?>" class="sgbtn">添加电影分组</a></h3>
	<div class="mainbox">
	    <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php } ?>		<form action="<?php echo $this->createUrl("admin","catalogs","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
												<th>分组</th>
												<th>标题</th>
												<th>显示标题</th>
												<th>上线</th>
												<th>排序</th>
												<td>操作</td>
					</tr>
					<?php foreach($models as $k => $model) {?>					
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
						                        <td><?php echo $model->getGroupMap($model->ogroup);?></td>
                                                <td><?php echo $model->title;?></td>
                                                <td><?php echo $model->showtitle;?></td>
                                                <td><?php echo $model->online;?></td>
                                                <td><?php echo $model->onum;?></td>
                        						<td><a href="<?php echo $this->createUrl("admin","catalogs","modifyview",array("id"=>$model->id));?>">编辑</a></td>
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