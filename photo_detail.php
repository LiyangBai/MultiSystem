<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','photo_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否登录
if (!$_COOKIE['username']) {
	_alert_back('Please login!');
}
//图片评论
if ($_GET['action']=='rephoto') {
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//接收数据
		$_clean=array();
		$_clean['sid']=$_POST['id'];
		$_clean['title']=$_POST['title'];
		$_clean['content']=$_POST['content'];
		$_clean['username']=$_COOKIE['username'];
		$_clean=_mysql_string($_clean);
		//写入数据库
		_query("INSERT INTO tg_photo_comment (tg_sid,tg_title,tg_content,tg_username,tg_date) VALUES ('{$_clean['sid']}','{$_clean['title']}','{$_clean['content']}','{$_clean['username']}',NOW())");
		//是否写入数据成功
		if (_affected_rows()==1) {
			_query("UPDATE tg_photo SET tg_commentcount=tg_commentcount+1 WHERE tg_id='{$_clean['sid']}'");
			_close();
			_location('Comment success!','photo_detail.php?id='.$_clean['sid']);
		}else{
			_close();
			_alert_back('Comment fail!','photo_detail.php');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
//判断ID
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT
		tg_id,tg_name,tg_url,tg_username,tg_content,tg_sid,tg_readcount,tg_commentcount,tg_date
		FROM tg_photo WHERE tg_id='{$_GET['id']}' LIMIT 1")) {
		//防止加密相册图片穿插访问
		if (!isset($_SESSION['admin'])) {
			if (!!$_dirs=_fetch_array("SELECT tg_type,tg_id,tg_name FROM tg_dir WHERE tg_id='{$_rows['tg_sid']}'")) {
				if (!empty($_dirs['tg_type'])&&	$_COOKIE['photo'.$_dirs['tg_id']]!=$_dirs['tg_name']) {
					_alert_back('ILLEGAL ACCESS!');
				}
			}
		}
		//累加阅读量
		_query("UPDATE tg_photo SET tg_readcount=tg_readcount+1 WHERE tg_id='{$_GET['id']}'");
		$_html=array();
		$_html['id']=$_rows['tg_id'];
		$_html['name']=$_rows['tg_name'];
		$_html['img_url']=$_rows['tg_url'];
		$_html['username']=$_rows['tg_username'];
		$_html['content']=$_rows['tg_content'];
		$_html['sid']=$_rows['tg_sid'];
		$_html['readcount']=$_rows['tg_readcount'];
		$_html['commentcount']=$_rows['tg_commentcount'];
		$_html['date']=$_rows['tg_date'];
		$_html=_html($_html);
		//全局id做带参分页
		global $_id;
		$_id='id='.$_html['id'].'&';
		//读取评论
		_page("SELECT tg_id FROM tg_photo_comment WHERE tg_sid='{$_html['id']}'",10);
		$_result=_query("
			SELECT tg_username,tg_title,tg_content,tg_date FROM tg_photo_comment 
			WHERE tg_sid='{$_html['id']}' ORDER BY tg_date ASC LIMIT $_pagenum,$_pagesize");
		//读出用户信息
		if (!!$_rows=_fetch_array("
		SELECT tg_id,tg_sex,tg_face,tg_email,tg_url 
		FROM tg_user WHERE tg_username='{$_html['username']}'")) {
			$_html['userid']=$_rows['tg_id'];
			$_html['sex']=$_rows['tg_sex'];
			$_html['face']=$_rows['tg_face'];
			$_html['email']=$_rows['tg_email'];
			$_html['url']=$_rows['tg_url'];
			$_html=_html($_html);
			//上一页,同一相册中ID比自己大的ID中的最小的
			$_html['preid']=_fetch_array("SELECT min(tg_id) AS id FROM tg_photo WHERE tg_sid='{$_html['sid']}' AND tg_id>'{$_html['id']}'");
			if (!empty($_html['preid']['id'])) {
				$_html['preid']='<a href="photo_detail.php?id='.$_html['preid']['id'].'#pre">上一页</a>';
			}else{
				$_html['preid']='<span>第一张</span>';
			}
			//下一张,同一相册中ID比自己小的ID中的最大的
			$_html['nexid']=_fetch_array("SELECT max(tg_id) AS id FROM tg_photo WHERE tg_sid='{$_html['sid']}' AND tg_id<'{$_html['id']}'");
			if (!empty($_html['nexid']['id'])) {
				$_html['nexid']='<a href="photo_detail.php?id='.$_html['nexid']['id'].'#nex">下一页</a>';
			}else{
				$_html['nexid']='<span>最后一张</span>';
			}
		}
		else{
			//查找失败
		}
	}else{
		_alert_back('Album does not exist!');
	}
}else{
	_alert_back('ILLEGAL ACCESS!');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/artical.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="photo">
		<h2>图片详情</h2>
		<dl class="detail">
			<a name="pre"></a><a name="nex"></a>
			<dd class="name"><?php echo $_html['name']; ?></dd>
			<dt><?php echo $_html['preid']; ?><img src="<?php echo $_html['img_url']; ?>" alt="<?php echo $_html['name']; ?>"><?php echo $_html['nexid']; ?></dt>
			<dd>浏览(<strong><?php echo $_html['readcount']; ?></strong>)评论(<strong><?php echo $_html['commentcount']; ?></strong>) 上传时间：<?php echo $_html['date']; ?> 上传者：<?php echo $_html['username']; ?></dd>
			<?php if ($_html['content']!='') {?>
			<dd>简介：<?php echo $_html['content']; ?></dd>
			<?php } ?>
			<dd class="list"><a href="photo_show.php?id=<?php echo $_html['sid'];?>">[返回列表]</a></dd>
		</dl>
		<?php 
			$_i=1;
			while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
				$_html['username']=$_rows['tg_username'];
				$_html['title']=$_rows['tg_title'];
				$_html['content']=$_rows['tg_content'];
				$_html['date']=$_rows['tg_date'];
				$_html=_html($_html);
		?>
		<p class="line"></p>
		<div class="re">
			<dl>
				<dd class="user"><?php echo $_html['username']; ?>(<?php echo $_html['sex']; ?>)</dd>
				<dt><img src="<?php echo $_html['face']; ?>" alt="<?php echo $_html['username']; ?>"></dt>
				<dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']; ?>">发消息</a></dd>
				<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']; ?>">加为好友</a></dd>
				<dd class="guest">写留言</dd>
				<dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']; ?>">给他送花</a></dd>
				<dd class="email">邮件：<a href="mailto:<?php echo $_html['email']; ?>"><?php echo $_html['email']; ?></a></dd>
				<dd class="url">网址：<a href="<?php echo $_html['url']; ?>" target="_black"><?php echo $_html['url']; ?></a></dd>
			</dl>
			<div class="content">
				<div class="user">
					<span><?php echo ($_i+($_page-1)*$_pagesize); ?>楼</span><p><?php echo $_html['username']; ?>|回复于：<?php echo $_html['date']; ?></p>
				</div>
				<h3><i class="ans">re：<?php echo $_html['title']; ?></i></h3>
				<div class="detail">
					<?php echo ubb($_html['content']); ?>			
				</div>
			</div>
		</div>
		<?php 
			$_i++;
			}
			_free_result($_result);
			//设置分页类型,1表示数字分页,2表示文本分页
			_paging(1);
		?>
		<?php if (isset($_COOKIE['username'])) {?>
		<p class="line"></p>	
		<form method="post" action="?action=rephoto">
			<input type="hidden" name="id" value="<?php echo $_html['id']; ?>">
			<dl class="rephoto">
				<dd>回&nbsp;&nbsp;&nbsp;复：<input type="text" name="title" class="text" value="<?php echo $_html['name']; ?>"> (*必填，2-40位)</dd>
				<dd id="q">贴&nbsp;&nbsp;&nbsp;图：<a href="javascript:;">Q图系列[1]</a>&nbsp;<a href="javascript:;">Q图系列[2]</a>&nbsp;<a href="javascript:;">Q图系列[3]</a></dd>
				<dd>
					<?php include ROOT_PATH.'includes/ubb.inc.php'; ?>
					<textarea name="content" rows="9"></textarea>
				</dd>
				<dd>验&nbsp;证&nbsp;码&nbsp;：
					<input type="text" name="code" class="text code">
					<img src="code.php" alt="验证码" id="code">
					<input type="submit" class="submit" value="发表">
				</dd>
			</dl>
		</form>
		<?php } ?>
	</div>	
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>