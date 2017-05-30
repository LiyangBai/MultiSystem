<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}
?>
<script src="js/skin.js" type="text/javascript"></script>
<div id="header">
	<h1><a href="index.php">多用户留言系统</a></h1>
	<ul>
	    <li><a href="index.php">首页</a></li>    
	    <?php
	    	if(isset($_COOKIE['username'])){
	    		echo '<li><a href="member.php">'.$_COOKIE['username'].'•个人中心</a>'.$_message_html.$_flower_html.'</li>';
	    		echo "\n";
	    	}else{
	    		echo '<li><a href="register.php">注册</a></li>';
	    		echo "\n\t\t";
	    		echo '<li><a href="login.php">登录</a></li>';
	    		echo "\n";
	    	}
	    ?>
	    <li><a href="blog.php">博友</a></li>
	    <li><a href="photo.php">相册</a></li>
	    <li class="skin" onmouseover="inskin()" onmouseout="outskin()">
	    	<a href="javascript:;">风格</a>
	    	<dl id="skin">
	    		<dd><a href="skin.php?id=1">皮肤一</a></dd>
	    		<dd><a href="skin.php?id=2">皮肤二</a></dd>
	    		<dd><a href="skin.php?id=3">皮肤三</a></dd>
	    	</dl>
	    </li>
	    <?php
	    	if (isset($_COOKIE['username'])&&isset($_SESSION['admin'])) {
	    		echo '<li><a href="manage.php" class="manage">管理</a></li>';
	    	}
	    	if(isset($_COOKIE['username'])){	    		
	    		echo '<li><a href="logout.php">退出</a></li>';
	    	}
	    ?>
	</ul>
</div>