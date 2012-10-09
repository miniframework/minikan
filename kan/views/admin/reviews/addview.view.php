<?php $this->layout("admin_main");?>
<div class="container">
<h3 class="marginbot">添加豆瓣评论<a href="<?php echo $this->createUrl("admin","reviews","list");?>" class="sgbtn">返回豆瓣评论列表</a></h3>
	<div class="mainbox">
		<div id="custom">
			<form action="<?php echo $this->createUrl("admin","reviews","add");?>" method="post">
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
						<th colspan="2">评论id:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="reviewid" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">作者:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="author" value=""></td>
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
						<th colspan="2">发布时间:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="published" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">更新时间:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="updated" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">内容:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="summary" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">评分:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="rating" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">投票:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="vote" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">评论数:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="comment" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">没用:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="useless" value=""></td>
						<td></td>
                    </tr>
					                    <tr>
						<th colspan="2">抓取时间:</th>
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