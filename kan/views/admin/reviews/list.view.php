<?php $this->layout("admin_main");?><div class="container">
	<h3 class="marginbot">列表豆瓣评论<a href="<?php echo $this->createUrl("admin","reviews","addview");?>" class="sgbtn">添加豆瓣评论</a></h3>
	<div class="mainbox">
	    <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php } ?>		<form action="<?php echo $this->createUrl("admin","reviews","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
												<th>聚合id</th>
												<th>评论id</th>
												<th>作者</th>
												<th>标题</th>
												<th>发布时间</th>
												<th>更新时间</th>
												<th>评分</th>
												<th>投票</th>
												<th>评论数</th>
												<th>没用</th>
												<th>抓取时间</th>
												<td>操作</td>
					</tr>
					<?php foreach($models as $k => $model) {?>					
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
						                        <td><?php echo $model->groupid;?></td>
                                                <td><?php echo $model->reviewid;?></td>
                                                <td><?php echo $model->author;?></td>
                                                <td><?php echo $model->title;?></td>
                                                <td><?php echo $model->getPublished();?></td>
                                                <td><?php echo $model->getUpdated();?></td>
                                                <td><?php echo $model->rating;?></td>
                                                <td><?php echo $model->vote;?></td>
                                                <td><?php echo $model->comment;?></td>
                                                <td><?php echo $model->useless;?></td>
                                                <td><?php echo $model->ctime;?></td>
                        						<td><a href="<?php echo $this->createUrl("admin","reviews","modifyview",array("id"=>$model->id));?>">编辑</a></td>
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