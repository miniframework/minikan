<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加豆瓣信息<a href="<?php echo $this->createUrl("admin","doubans","list");?>" class="sgbtn">返回豆瓣信息列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","doubans","add");?>" method="post">
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
						<th colspan="2">豆瓣id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="doubanid" value=""></td>
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
						<th colspan="2">海报:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="pic" value=""></td>
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
						<th colspan="2">编剧:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="writer" value=""></td>
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
						<th colspan="2">官网:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="website" value=""></td>
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
						<th colspan="2">发布时间:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="pubdate" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">片长:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="runtime" value=""></td>
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
						<th colspan="2">imdb:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="imdb" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">评分:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="rate" value=""></td>
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
						<th colspan="2">Tag:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="tag" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">短评:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="shortcomment" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">Ctime:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="ctime" value=""></td>
						<td></td>
                    </tr>
					                </tbody>
			</table>
			<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3"></div>
			</form>
		</div>
	</div>
</div>