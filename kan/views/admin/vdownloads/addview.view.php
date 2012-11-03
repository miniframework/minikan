<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加视频下载<a href="<?php echo $this->createUrl("admin","vdownloads","list");?>" class="sgbtn">返回视频下载列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","vdownloads","add");?>" method="post">
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
						<th colspan="2">标题:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="title" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">头图:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="imagelink" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">豆瓣图片:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="doubanimage" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">别名:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="alias" value=""></td>
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
						<th colspan="2">分类:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="cate" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">国家:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="country" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">语言:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="lang" value=""></td>
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
						<th colspan="2">明星:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="star" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">摘要:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="summary" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">剧照:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="photo" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">豆瓣id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="doubanid" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Ctime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="ctime" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Mtime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="mtime" value=""></td>
						<td></td>
                    </tr>
					                </tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>