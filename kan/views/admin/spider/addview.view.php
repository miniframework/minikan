<?php $this->layout("admin_main");?>	
<div class="container">
<h3 class="marginbot">添加新抓取<a href="<?php echo $this->createUrl("admin","spider","list");?>" class="sgbtn">返回抓取列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","spider","add");?>" method="post">
			<table class="opt">
				<tbody>
					<tr>
						<th colspan="2">抓取标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="title" value=""></td>
						<td></td>
					</tr>
					<tr>
						<th colspan="2">目标地址:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="targeturl" value="" style="width:600px;"></td>
						<td></td>
					</tr>
					<tr>
						<th colspan="2">类型:</th>
					</tr>
					<tr>
						<td>
							
							<select name="dtype">
							<?php foreach($vspider->typeMap() as $k => $v){?>
							<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
						</select>
						</td>
						<td></td>
					</tr>
					<tr>
						<th colspan="2">抓取网站:</th>
					</tr>
					<tr>
						<td>
							
							<select name="siteid">
							<option value="0">请选择</option>
							<?php foreach($video->siteidMap() as $k => $v){?>
							<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
						</select>
						</td>
						<td></td>
					</tr>
					<tr>
						<th colspan="2">蜘蛛类型:</th>
					</tr>
					<tr>
						<td>
							
							<select name="spidercall">
							<option value="0">请选择</option>
							<?php foreach($vspider->spiderCall() as $k => $v){?>
							<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
						</select>
						</td>
						<td></td>
					</tr>
					<tr>
						<th colspan="2">抓取类型:</th>
					</tr>
					<tr>
						<td>
						<select name="vtype">
							<option value="0">请选择</option>
							<?php foreach($video->vtypeMap() as $k => $v){?>
							<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
						</select>
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>