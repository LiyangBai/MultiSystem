<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','register');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//登录状态
_login_state();
//判断是否提交
if($_GET['action']=='register'){
	if (empty($_system['register'])) {
		exit('ILLEGAL REGISTER!');
	}
	//防止恶意注册
	_check_code($_POST['code'],$_SESSION['code']);
	//引入验证文件
	include ROOT_PATH.'includes/check.func.php';
	//创建数组存储提交的合法数据
	$_clean=array();
	//唯一标识符防止跨站攻击
	$_clean['uniqid']=_check_uniqid($_POST['uniqid'],$_SESSION['uniqid']);
	//用来激活刚才注册的用户
	$_clean['active']=_sha1_uniqid();
	$_clean['username']=_check_username($_POST['username']);
	$_clean['passward']=_check_passward($_POST['passward'],$_POST['notpassward']);
	$_clean['question']=_check_question($_POST['question']);
	$_clean['answer']=_check_answer($_POST['question'],$_POST['answer']);
	$_clean['sex']=_check_sex($_POST['sex']);
	$_clean['face']=_check_face($_POST['face']);
	$_clean['email']=_check_email($_POST['email']);
	$_clean['qq']=_check_qq($_POST['qq']);
	$_clean['url']=_check_url($_POST['url']);
	//判断用户是否已注册
	_is_repeat("SELECT tg_username FROM tg_user WHERE tg_username='{$_clean['username']}'LIMIT 1",
				'用户名已注册！');
	//新增用户,单引号中放数组时必须加上{}
	_query("
		INSERT INTO tg_user (
		tg_uniqid,tg_active,tg_username,tg_passward,tg_question,tg_answer,tg_sex,tg_face,tg_email,tg_qq,tg_url,tg_reg_time,tg_last_time,tg_last_ip
		) VALUES (
		'{$_clean['uniqid']}','{$_clean['active']}','{$_clean['username']}','{$_clean['passward']}','{$_clean['question']}','{$_clean['answer']}','{$_clean['sex']}','{$_clean['face']}','{$_clean['email']}','{$_clean['qq']}','{$_clean['url']}',NOW(),NOW(),'{$_SERVER["REMOTE_ADDR"]}'
		)"
	);
	//是否注册成功
	if (_affected_rows()==1) {
		//获取新增ID
		$_clean['id']=_insert_id();
		_close();
		//新增会员
		_set_xml('new.xml',$_clean);
		_location('Register success!','active.php?active='.$_clean['active']);
	}else{
		_close();
		_location('Register fail!','register.php');
	}
}else{
	$_SESSION['uniqid']=$_uniqid=_sha1_uniqid();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/register.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="register">
		<h2>会员注册</h2>
		<?php if (!empty($_system['register'])) { ?>
		<form method="post" name="register" action="register.php?action=register">
			<input type="hidden" name="uniqid" value="<?php echo $_uniqid; ?>">
			<dl>
				<dt>请认真填写以下页面</dt>
				<dd>用&nbsp;户&nbsp;名&nbsp;：<input type="text" name="username" class="text"> (*必填 至少两位)</dd>
				<dd>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：<input type="passward" name="passward" class="text"> (*必填 至少六位)</dd>
				<dd>密码确认：<input type="passward" name="notpassward" class="text"> (*必填 至少两位)</dd>
				<dd>密码提示：<input type="text" name="question" class="text"> (*必填 至少两位)</dd>
				<dd>密码回答：<input type="text" name="answer" class="text"> (*必填 至少两位)</dd>
				<dd>性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：&nbsp;&nbsp;<input type="radio" name="sex" value="男" checked="checked">男&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="sex" value="女">女</dd>
				<dd class="face"><input type="hidden" name="face" value="face/m04.jpg"><img src="face/m04.jpg" alt="头像选择" id="faceimg"></dd>
				<dd>电子邮件：<input type="text" name="email" class="text"> (*必填 激活账户)</dd>
				<dd>Q&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Q：<input type="text" name="qq" class="text"></dd>
				<dd>主页地址：<input type="text" name="url" class="text" value="http://"> (*必填)</dd>
				<dd>验&nbsp;证&nbsp;码&nbsp;：<input type="text" name="code" class="text code"><img src="code.php" alt="验证码" id="code"></dd>
				<dd><input type="submit" class="submit" value="注册"></dd>
			</dl>
		</form>
		<?php }else{
			echo '<h4 style="text-align:center;padding-top:20px;">本站关闭了注册功能！</h4>';
		}
		?>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>