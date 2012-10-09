<?php $this->layout("admin_main");?><div class="container">
	<h3 class="marginbot">列表Spiderlogs<a href="<?php echo $this->createUrl("admin","spiderlogs","addview");?>" class="sgbtn">添加Spiderlogs</a></h3>
	<div class="mainbox">
	    <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php } ?>		<form action="<?php echo $this->createUrl("admin","spiderlogs","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
												<th>Spiderid</th>
												<th>Type</th>
												<th>Starttime</th>
												<th>Endtime</th>
												<td>操作</td>
					</tr>
					<?php foreach($models as $k => $model) {?>					
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
						                        <td><?php echo $model->spiderid;?></td>
                                                <td><?php echo $model->type;?></td>
                                                <td><?php echo $model->starttime;?></td>
                                                <td><?php echo $model->endtime;?></td>
                        						<td><a href="<?php echo $this->createUrl("admin","spiderlogs","modifyview",array("id"=>$model->id));?>">编辑</a></td>
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