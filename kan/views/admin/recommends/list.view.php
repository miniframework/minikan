<?php $this->layout("admin_main");?><div class="container">
	<h3 class="marginbot">列表推荐<a href="<?php echo $this->createUrl("admin","recommends","addview");?>" class="sgbtn">添加推荐</a></h3>
	<br/>
	<?php foreach($recommend->typeidMap() as $k => $v) {?>
	<a class="sgbtn" style="margin-left:0px" href="<?php echo $this->createUrl("admin","recommends","list",array("typeid"=>$v->id));?>"><?php echo $v->title;?></a>
	<?php }?>
	
	<div class="mainbox">
	    <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php } ?>		<form action="<?php echo $this->createUrl("admin","recommends","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
												<th>聚合id</th>
												<th>标题</th>
												<th>推荐类别</th>
												<th>排序</th>
												<td>操作</td>
					</tr>
					<?php foreach($models as $k => $model) {?>					
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
						                        <td><?php echo $model->groupid;?></td>
						                        <td><?php echo $model->Relvgroup()->title;?>
                                                <td><?php echo $model->typeidMap($model->typeid)->title;?></td>
                                                <td><?php echo $model->onum;?></td>
                                                
                        						<td><a href="<?php echo $this->createUrl("admin","recommends","modifyview",array("id"=>$model->id));?>">编辑</a></td>
					</tr>
					<?php } ?>					<tr class="nobg">
						<td><input type="submit" value="删 除" class="btn"></td>
						<td class="tdpage"></td>
					</tr>		
				</table>
				<p class="page_list"></p>
		</form>
	</div>
</div>