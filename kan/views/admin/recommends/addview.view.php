<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加推荐<a href="<?php echo $this->createUrl("admin","recommends","list");?>" class="sgbtn">返回推荐列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","recommends","add");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">聚合id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="groupid" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">推荐类别:</th>
					</tr>
					
					
					<tr>
						<td>
						<select name="typeid">
							<option value="0">请选择</option>
							<?php foreach($model->typeidMap() as $k => $v){?>
							<option value="<?php echo $v->id;?>"><?php echo $v->title;?></option>
							<?php }?>
						</select>
						
						</td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">排序:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="onum" value=""></td>
						<td></td>
                    </tr>
					                </tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>