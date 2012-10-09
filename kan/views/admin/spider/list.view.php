<?php $this->layout("admin_main");?>	
<div class="container">
	<h3 class="marginbot">抓取列表<a href="<?php echo $this->createUrl("admin","spider","addview");?>" class="sgbtn">添加新抓取</a></h3>
	<div class="mainbox">
		<?php if($firsterror) {?>
		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php }?>
		<form action="<?php echo $this->createUrl("admin","spider","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
						<th>ID</th>
						<th>TITLE</th>
						<!-- <th>抓取目标 URL</th> -->
						<th>网站</th>
						<th>类型</th>
						<th>次数</th>
						<th>lock</th>
						<th>日次数</th>
						<th>抓取时间</th>
						<th>结束时间</th>
						<th>存储状态</th>
						<th>操作</th>
					</tr>
					<?php foreach($models as $k => $model) {?>
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
						<td><?php echo $model->id;?></td>
						<td><?php echo $model->title;?></td>
					<!--  <td><?php echo $model->targeturl;?></td>-->	
						<td><?php echo $video->siteidMap($model->siteid);?></td>
						<td><?php echo $video->vtypeMap($model->vtype);?></td>
						<td><?php echo $model->updatenum;?></td>
						<td><?php echo $model->locking;?> </td>
						<td><?php echo $model->daynum;?></td>
						<td><?php echo $model->catchtime;?></td>
						<td><?php echo $model->endtime;?></td>
						<td><?php echo $model->isstoreMap($model->isstore);?>
						
						<a href="javascript:window.open('<?php echo $this->createUrl("admin","spider","gospider",array("id"=>$model->id));?>', '_blank','width=600,height=400');void(0);">(抓取)</a>
						<?php if($model->isstore == 1 || $model->isstore == 2) {?>
							<a href="javascript:window.open('<?php echo $this->createUrl("admin","spider","lookxml",array("id"=>$model->id));?>', '_blank','width=600,height=400');void(0);">(查看)</a>
							<a href="javascript:window.open('<?php echo $this->createUrl("admin","spider","todb",array("id"=>$model->id));?>', '_blank','width=600,height=400');void(0);">(入库)</a>
						<?php }?>
						</td>
						<td><a href="<?php echo $this->createUrl("admin","spider","modifyview",array("id"=>$model->id));?>">编辑</a>|
							<a href="<?php echo $this->createUrl("admin","spiderlogs","list",array("spiderid"=>$model->id));?>">Log</a>	
						</td>
						
					</tr>
					<?php }?>
					<tr class="nobg">
						<td><input type="submit" value="提 交" class="btn"></td>
						<td class="tdpage" ></td>
					</tr>	
				</table>
				<p class="page_list"><?php echo $page->pageHtml();?></p>
		</form>
	</div>
</div>
