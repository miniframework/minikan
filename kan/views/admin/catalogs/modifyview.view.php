<?php $this->layout("admin_main");?><div class="container">
<h3 class="marginbot">编辑电影分组<a href="<?php echo $this->createUrl("admin","catalogs","list");?>" class="sgbtn">返回电影分组列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","catalogs","modify");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">分组:</th>
					</tr>
					 <tr>
						<td>
						<select name="ogroup">
							<option value="0">请选择</option>
							<?php foreach($model->getGroupMap() as $k => $v){?>
							<option <?php if($model->ogroup == $k) echo "selected"; ?> value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
						</select>
						</td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="title" value="<?php echo $model->title;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">显示标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="showtitle" value="<?php echo $model->showtitle;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">上线:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="online" value="<?php echo $model->online;?>"></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">排序:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="onum" value="<?php echo $model->onum;?>"></td>
						<td></td>
                    </tr>
									</tbody>
			</table>
			<div class="opt">
			<input type="hidden" name="id"	value="<?php echo $model->id;?>">
			<input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3">
			</div>
			</form>
		</div>
	</div>
</div>