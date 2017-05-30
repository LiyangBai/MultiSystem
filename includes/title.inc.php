<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}
//防止非HTML页面调用
if(!defined('SCRIPT')){
	exit('No permissions!');
}
?>
<title><?php echo $_system['webname']; ?></title>
<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" type="text/css" href="styles/<?php echo $_system['skin']; ?>/basic.css">
	<link rel="stylesheet" type="text/css" href="styles/<?php echo $_system['skin']; ?>/<?php echo SCRIPT; ?>.css">