<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','manage_set');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否管理员
_manage_login();
//修改系统
if ($_GET['action']=='set') {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		$_clean=array();
		$_clean['webname']=$_POST['webname'];
		$_clean['artical']=$_POST['artical'];
		$_clean['blog']=$_POST['blog'];
		$_clean['photo']=$_POST['photo'];
		$_clean['skin']=$_POST['skin'];
		$_clean['string']=$_POST['string'];
		$_clean['post']=$_POST['post'];
		$_clean['re']=$_POST['re'];
		$_clean['code']=$_POST['code'];
		$_clean['register']=$_POST['register'];
		$_clean=_mysql_string($_clean);
		//写入数据库
		_query("UPDATE tg_system SET 
			tg_webname='{$_clean['webname']}',tg_artical='{$_clean['artical']}',
			tg_blog='{$_clean['blog']}',tg_photo='{$_clean['photo']}',
			tg_skin='{$_clean['skin']}',tg_string='{$_clean['string']}',
			tg_post='{$_clean['post']}',tg_re='{$_clean['re']}',
			tg_code='{$_clean['code']}',tg_register='{$_clean['register']}' 
			WHERE tg_id=1 LIMIT 1");
		//判断是否修改成功
		if (_affected_rows()==1) {
			_close();
			_location('Modify success!','manage.php');
		}else{
			_close();
			_location('Modify fail!','manage_set.php');
		}
	}else{
		_alert_back('System error!');
	}
}
//读取系统
if (!!$_rows=_fetch_array("SELECT
	tg_webname,tg_artical,tg_blog,tg_photo,tg_skin,tg_string,tg_post,tg_re,tg_code,tg_register FROM tg_system WHERE tg_id=1 LIMIT 1")) {
	$_html=array();
	$_html['webname']=$_rows['tg_webname'];
	$_html['artical']=$_rows['tg_artical'];
	$_html['blog']=$_rows['tg_blog'];
	$_html['photo']=$_rows['tg_photo'];
	$_html['skin']=$_rows['tg_skin'];
	$_html['string']=$_rows['tg_string'];
	$_html['post']=$_rows['tg_post'];
	$_html['re']=$_rows['tg_re'];
	$_html['code']=$_rows['tg_code'];
	$_html['register']=$_rows['tg_register'];
	$_html=_html($_html);
	//文章
	if ($_html['artical']==10) {
		$_html['artical_html']='<select name="artical"><option value="10" selected="selected">每页10篇</option><option value="15">每页15篇</option></select>';
	}else if ($_html['artical']==15) {
		$_html['artical_html']='<select name="artical"><option value="10">每页10篇</option><option value="15" selected="selected">每页15篇</option></select>';
	}
	//博客
	if ($_html['blog']==8) {
		$_html['blog_html']='<select name="blog"><option value="8" selected="selected">每页8人</option><option value="12">每页12人</option></select>';
	}else if ($_html['blog']==12) {
		$_html['blog_html']='<select name="blog"><option value="8">每页8人</option><option value="12" selected="selected">每页12人</option></select>';
	}
	//相册
	if ($_html['photo']==8) {
		$_html['photo_html']='<select name="photo"><option value="8" selected="selected">每页8张</option><option value="12">每页12张</option></select>';
	}else if ($_html['photo']==12) {
		$_html['photo_html']='<select name="photo"><option value="8">每页8张</option><option value="12" selected="selected">每页12张</option></select>';
	}
	//皮肤
	if ($_html['skin']==1) {
		$_html['skin_html']='<select name="skin"><option value="1" selected="selected">皮肤一</option><option value="2">皮肤二</option><option value="3">皮肤三</option></select>';
	}else if ($_html['skin']==2) {
		$_html['skin_html']='<select name="skin"><option value="1">皮肤一</option><option value="2" selected="selected">皮肤2</option><option value="3">皮肤三</option></select>';
	}else if ($_html['skin']==3) {
		$_html['skin_html']='<select name="skin"><option value="1">皮肤一</option><option value="2">皮肤二</option><option value="3" selected="selected">皮肤三</option></select>';
	}
	//发帖限制
	if ($_html['post']==30) {
		$_html['post_html']='<input type="radio" name="post" value="30" checked="checked">30秒<input type="radio" name="post" value="60">1分钟<input type="radio" name="post" value="180">3分钟';
	}else if ($_html['post']==60) {
		$_html['post_html']='<input type="radio" name="post" value="30">30秒<input type="radio" name="post" value="60" checked="checked">1分钟<input type="radio" name="post" value="180">3分钟';
	}else if ($_html['post']==180) {
		$_html['post_html']='<input type="radio" name="post" value="30">30秒<input type="radio" name="post" value="60">1分钟<input type="radio" name="post" value="180" checked="checked">3分钟';
	}
	//回帖限制
	if ($_html['re']==15) {
		$_html['re_html']='<input type="radio" name="re" value="15" checked="checked">15秒<input type="radio" name="re" value="30">30秒<input type="radio" name="re" value="45">45秒';
	}else if ($_html['re']==30) {
		$_html['re_html']='<input type="radio" name="re" value="15">15秒<input type="radio" name="re" value="30" checked="checked">30秒<input type="radio" name="re" value="45">45秒';
	}else if ($_html['re']==45) {
		$_html['re_html']='<input type="radio" name="re" value="15">15秒<input type="radio" name="re" value="30">30秒<input type="radio" name="re" value="45" checked="checked">45秒';
	}
	//验证码
	if ($_html['code']==1) {
		$_html['code_html']='<input type="radio" name="code" value="1" checked="checked">启用<input type="radio" name="code" value="0">禁用';
	}else{
		$_html['code_html']='<input type="radio" name="code" value="1">启用<input type="radio" name="code" value="0" checked="checked">禁用';
	}
	//注册
	if ($_html['register']==1) {
		$_html['register_html']='<input type="radio" name="register" value="1" checked="checked">启用<input type="radio" name="register" value="0">禁用';
	}else{
		$_html['register_html']='<input type="radio" name="register" value="1">启用<input type="radio" name="register" value="0" checked="checked">禁用';
	}
}else{
	_alert_back('读取系统表错误,请联系管理员检查!');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="member">
		<?php 
			require ROOT_PATH.'includes/manage.inc.php';
		?>
		<div id="member_main">
			<h2>系统设置</h2>
			<form method="post" action="?action=set">
			<dl>
				<dd>• 网&nbsp;&nbsp;&nbsp;站&nbsp;&nbsp;&nbsp;名&nbsp;&nbsp;&nbsp;称：<input type="text" class="webname" name="webname" value="<?php echo $_html['webname']; ?>"></dd>
				<dd>• 文章每页列表数：<?php echo $_html['artical_html']; ?></dd>
				<dd>• 博客每页列表数：<?php echo $_html['blog_html']; ?></dd>
				<dd>• 相册每页列表数：<?php echo $_html['photo_html']; ?></dd>
				<dd>• 站点&nbsp;默认&nbsp;皮肤&nbsp;：<?php echo $_html['skin_html']; ?></dd>
				<dd>• 非法&nbsp;字符&nbsp;限制&nbsp;：<input type="text" class="string" name="string" value="<?php echo $_html['string']; ?>"> (*请用|隔开)</dd>
				<dd>• 每次&nbsp;发帖&nbsp;限制&nbsp;：<?php echo $_html['post_html'] ?></dd>
				<dd>• 每次&nbsp;回帖&nbsp;限制&nbsp;：<?php echo $_html['re_html'] ?></dd>
				<dd>• 是否启用验证码：<?php echo $_html['code_html'] ?></dd>
				<dd>• 是否&nbsp;开放&nbsp;注册&nbsp;：<?php echo $_html['register_html'] ?></dd>
				<dd><input type="submit" class="submit" value="修改系统设置"></dd>
			</dl>
			</form>
		</div>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>