<?php $this->layout("admin_main");?>
<div class="container">
	<h3 class="marginbot">列表豆瓣信息<a href="<?php echo $this->createUrl("admin","doubans","addview");?>" class="sgbtn">添加豆瓣信息</a></h3>
	<div class="mainbox">
	    <?php if(isset($firsterror) && !empty($firsterror)) {?>		<div class="errormsg">
			<p><em><?php echo $firsterror;?></em></p>
		</div>
		<?php } ?>		<form action="<?php echo $this->createUrl("admin","doubans","delete");?>" onsubmit="return confirm('该操作不可恢复，您确认要删除吗？');" method="post">
				<table class="datalist"  onmouseover="addMouseEvent(this);">
					<tr>
						<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox">
						<label for="chkall">删除</label>
						</th>
												<th>聚合id</th>
												<th>豆瓣id</th>
												<th>标题</th>
												<th>海报</th>
												  <!--    <th>导演</th>-->
												  <!--    <th>编剧</th>-->
												  <!--    <th>明星</th>-->
												<th>分类</th>
												<th>地区</th>
												  <!--    <th>官网</th>-->
												<th>语言</th>
												<th>发布时间</th>
												<th>片长</th>
												<th>别名</th>
												 <!--<th>imdb</th>-->
												<th>评分</th>
												  <!--    <th>Tag</th>-->
												  <!--    <th>短评</th>-->
												 <!--    <th>Ctime</th>-->
												<td>操作</td>
					</tr>
					<?php foreach($models as $k => $model) {?>					
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?php echo $model->id;?>" class="checkbox"></td>
						                        <td><?php echo $model->groupid;?></td>
                                                <td><?php echo $model->doubanid;?></td>
                                                <td><?php echo $model->title;?></td>
                                                <td><?php echo $model->pic;?></td>
                                               <!--    <td><?php echo $model->director;?></td>-->
                                               <!--    <td><?php echo $model->writer;?></td>-->
                                               <!--    <td><?php echo $model->star;?></td>-->
                                                <td><?php echo $model->cate;?></td>
                                                <td><?php echo $model->area;?></td>
                                               <!--    <td><?php echo $model->website;?></td>-->
                                                <td><?php echo $model->lang;?></td>
                                                <td><?php echo $model->pubdate;?></td>
                                                <td><?php echo $model->runtime;?></td>
                                                <td><?php echo $model->alias;?></td>
                                             <!--   <td><?php echo $model->imdb;?></td>--> 
                                                <td><?php echo $model->rate;?></td>
                                             <!--  <td><?php echo $model->summary;?></td> -->
                                             <!--  <td><?php echo $model->tag;?></td> --> 
                                             <!--   <td><?php echo $model->shortcomment;?></td>--> 
                                              <!--  <td><?php echo $model->ctime;?></td>--> 
                        						<td><a href="<?php echo $this->createUrl("admin","doubans","modifyview",array("id"=>$model->id));?>">编辑</a></td>
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