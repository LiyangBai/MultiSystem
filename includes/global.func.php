<?php
/*
*Author:白小七
*Date:2017-5-8
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}
//管理员登录
function _manage_login(){
	if ((!isset($_COOKIE['username']))||(!isset($_SESSION['admin']))) {
		_alert_back("ILLEGAL ACCESS!");
	}
}
//获取执行耗时
function _runtime(){
	$_mtime=explode(' ',microtime());
	return $_mtime[0]+$_mtime[1];
}
//弹窗并返回
function _alert_back($_info){
	echo "<script type='text/javascript'>alert('".$_info."');history.back();</script>";
	exit();
}
//弹窗并关闭
function _alert_close($_info){
	echo "<script type='text/javascript'>alert('".$_info."');window.close();</script>";
	exit();
}
//弹窗并转到连接
function _location($_info,$_url){
	if (!empty($_info)) {
		echo "<script type='text/javascript'>alert('".$_info."');location.href='$_url';</script>";
		exit();
	}else{
		header('Location:'.$_url);
	}	
}
//验证码判断
function _check_code($_first_code,$_second_code){
	if($_POST['code']!=$_SESSION['code']){
		_alert_back("CODE ERROR!");
	}
}
//防止cookie伪造
function _uniqid($_mysql_uniqid,$_cookie_uniqid){
	if ($_mysql_uniqid!=$_cookie_uniqid) {
		_alert_back('Unique identifier exception!');
	}
}
//唯一标识符生成
function _sha1_uniqid(){
	return _mysql_string(sha1(uniqid(rand(),true)));
}
//判断是否登录
function _login_state(){
	if (isset($_COOKIE['username'])) {
		_alert_back('用户名已登录！');
	}
}
//清除session 
function _session_destroy(){
	if (session_start()) {
		session_destroy();
	}
}
//清除cookie
function _unsetcookie(){
	setcookie('username','',time()-1);
	setcookie('uniqid','',time()-1);
	_session_destroy();
	_location(null,'index.php');
}
//分页
function _page($_sql,$_size){
	global $_pagesize,$_pagenum,$_pageabsolute,$_num,$_page;
	if(isset($_GET['page'])){
		$_page=$_GET['page'];
		if ($_page<=0||!is_numeric($_page)) {
			$_page=1;
		}else{
			$_page=intval($_page);
		}
	}else{
		$_page=1;
	}
	$_pagesize=$_size;
	//取得所有的数据
	$_num=_num_rows(_query($_sql));
	//防止数据库清零
	if ($_num==0) {
		$_pageabsolute=1;
	}else{
		$_pageabsolute=ceil($_num/$_pagesize);
	}
	if ($_page>$_pageabsolute) {
		$_page=$_pageabsolute;
	}
	$_pagenum=($_page-1)*$_pagesize;
}
//分页类型选择
function _paging($_type){
	global $_pageabsolute,$_page,$_num,$_id;
	if ($_type==1) {
		echo '<div id="page_num">';
		echo '<ul>';
		for ($i=0; $i < $_pageabsolute; $i++) { 
			if ($_page==($i+1)) {
				echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'"class="selected">'.($i+1).'</a></li>';
			}else{
				echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a></li>';
			}
		} 
		echo '</ul>';
		echo '</div>';
	}else if ($_type==2) {
		echo '<div id="page_text">';
		echo '<ul>';
		echo '<li>'.$_page.'/'.$_pageabsolute.'页 |</li>';
		echo '<li>共有<strong>'.$_num.'</strong>条数据 |</li>';
		if ($_page==1) {
			echo "<li> 首页 |</li>";
			echo "<li> 上一页 |</li>";
		}else{
			echo '<li><a href="'.SCRIPT.'.php"> 首页 |</a></li>';
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'"> 上一页 |</a></li>';
		}
		if ($_page==$_pageabsolute) {
			echo "<li> 下页 |</li>";
			echo "<li> 尾页</li>";
		}else{
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.$_pageabsolute.'"> 尾页</a></li>';
			echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'"> 下页 |</a></li>';
		}
		echo '</ul>';
		echo '</div>';
	}else{
		_paging(2);
	}
}
//对数据库的数据过滤特殊标记
function _html($_string){
	if (is_array($_string)) {
		foreach ($_string as $_key => $_value) {
			$_string[$_key]=_html($_value);
		}
	}else{
		$_string=htmlspecialchars($_string);
	}
	return $_string;
}
//转义字符串
function _mysql_string($_string){
	//如果get_magic_quotes_gpc()开启，则不转义
	if(!GPC){
		if (is_array($_string)) {
			foreach ($_string as $_key => $_value) {
				$_string[$_key]=_mysql_string($_value);
			}
		}else{
			$_string=mysql_escape_string($_string);
		}
	}
	return $_string;
}
//显示部分内容
function _title($_string,$_strlen){
	if (mb_strlen($_string,'utf8')>$_strlen) {
		$_string=mb_substr($_string,0,$_strlen,'utf8').'...';
	}
	return $_string;
}
//生成XML
function _set_xml($_xmlfile,$_clean){
	$_fp=@fopen('new.xml','w');
	if (!$_fp) {
		exit('SYSTEM ERROR,FILE NOT EXIST!');
	}
	//锁定文件
	flock($_fp,LOCK_EX);
	$_string="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="<vip>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="\t<id>{$_clean['id']}</id>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="\t<username>{$_clean['username']}</username>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="\t<sex>{$_clean['sex']}</sex>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="\t<face>{$_clean['face']}</face>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="\t<email>{$_clean['email']}</email>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="\t<url>{$_clean['url']}</url>\r\n";
	fwrite($_fp,$_string,strlen($_string));
	$_string="</vip>";
	fwrite($_fp,$_string,strlen($_string));
	//解锁文件
	flock($_fp,LOCK_UN);
	fclose($_fp);
}
//获取XML
function _get_xml($_xmlfile){
	$_html=array();
	if (file_exists($_xmlfile)) {
		$_xml=file_get_contents($_xmlfile);
		//筛选内容
		preg_match_all('/<vip>(.*)<\/vip>/s',$_xml,$_dom);
		foreach ($_dom[1] as $_value) {
			preg_match_all('/<id>(.*)<\/id>/s',$_value,$_id);
			preg_match_all('/<username>(.*)<\/username>/s',$_value,$_username);
			preg_match_all('/<sex>(.*)<\/sex>/s',$_value,$_sex);
			preg_match_all('/<face>(.*)<\/face>/s',$_value,$_face);
			preg_match_all('/<email>(.*)<\/email>/s',$_value,$_email);
			preg_match_all('/<url>(.*)<\/url>/s',$_value,$_url);
			$_html['id']=$_id[1][0];
			$_html['username']=$_username[1][0];
			$_html['sex']=$_sex[1][0];
			$_html['face']=$_face[1][0];
			$_html['email']=$_email[1][0];
			$_html['url']=$_url[1][0];
		}
	}else{
		echo 'FILE NOT EXIST!';
	}
	return $_html;
}
//解析ubb
function ubb($_string){
	//回车转<br/>
	$_string=nl2br($_string);
	//正则替换
	$_string=preg_replace('/\[size=(.*)\](.*)\[\/size\]/U','<span style="font-size:\1px">\2</span>',$_string);
	$_string=preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>',$_string);
	$_string=preg_replace('/\[i\](.*)\[\/i\]/U','<em>\1</em>',$_string);
	$_string=preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline;">\1</span>',$_string);
	$_string=preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through;">\1</span>',$_string);
	$_string=preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color:\1">\2</span>',$_string);
	$_string=preg_replace('/\[url\](.*)\[\/url\]/U','<a href="\1" target="_blank">\1</a>',$_string);
	$_string=preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:\1"">\1</a>',$_string);
	$_string=preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" alt="图片"></img>',$_string);
	$_string=preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed style="width:480px;height:400px" src="\1" />',$_string);
	return $_string;
}
//限时判断
function _timed($_now_time,$_pre_time,$_time){
	if ($_now_time-$_pre_time<$_time) {
		_alert_back('Please take a break and then try again!','post.php');
	}
}
//缩略图
function _thumbnail($_filename,$_percent){
	//生成png标头文件
	header('Content-type:image/png');
	//截取文件后缀
	$_Suffix=explode('.',$_filename);
	//获取文件信息
	list($_width,$_height)=getimagesize($_filename);
	//生成微缩的宽高
	$_new_width=$_width*$_percent;
	$_new_height=$_height*$_percent;
	//创建一个新画布
	$_new_image=imagecreatetruecolor($_new_width,$_new_height);
	switch ($_Suffix[1]) {
		case 'jpg':
		case 'jpeg':
			$_image=imagecreatefromjpeg($_filename);
			break;
		case 'png':
			$_image=imagecreatefrompng($_filename);
			break;
		default:
			$_image=imagecreatefromgif($_filename);
			break;
	}
	//将原图采样后重新复制到新图上
	imagecopyresampled($_new_image,$_image,0,0,0,0,$_new_width,$_new_height,$_width,$_height);
	imagepng($_new_image);
	imagedestroy($_new_image);
	imagedestroy($_image);
}
//删除非空目录
function _delDir($directory){//自定义函数递归的函数整个目录  
    if(file_exists($directory)){//判断目录是否存在，如果不存在rmdir()函数会出错  
        if($dir_handle=@opendir($directory)){//打开目录返回目录资源，并判断是否成功  
            while($filename=readdir($dir_handle)){//遍历目录，读出目录中的文件或文件夹  
                if($filename!='.' && $filename!='..'){//一定要排除两个特殊的目录  
                    $subFile=$directory."/".$filename;//将目录下的文件与当前目录相连  
                    if(is_dir($subFile)){//如果是目录条件则成了  
                        _delDir($subFile);//递归调用自己删除子目录  
                    }  
                    if(is_file($subFile)){//如果是文件条件则成立  
                        unlink($subFile);//直接删除这个文件  
                    }  
                }  
            }  
            closedir($dir_handle);//关闭目录资源  
            rmdir($directory);//删除空目录
            return true;
        }  
    } 
}
?>