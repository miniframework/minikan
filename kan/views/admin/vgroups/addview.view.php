<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加视频聚合<a href="<?php echo $this->createUrl("admin","vgroups","list");?>" class="sgbtn">返回视频聚合列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","vgroups","add");?>" method="post">
			<table class="opt">
				<tbody>
					                    <tr>
						<th colspan="2">类型:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="vtype" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">视频聚合:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="videoids" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="title" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">图片地址:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="imagelink" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">douban图片:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="doubanimage" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">分类:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="cate" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">地区:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="area" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">年:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="year" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">明星:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="star" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">导演:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="director" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">概要:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="summary" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">TAG:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="tag" value=""></td>
						<td></td>
                    </tr>
					                </tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>