<?php 
/*
*Author:��С��
*Date:2017-5-8
*/
session_start();
//���������
for($i=0;$i<4;$i++){
	$_nmsg.=dechex(mt_rand(0,15));
}
//������뱣����session��
$_SESSION['code']=$_nmsg;
//���������ͼ��
$_width=75;
$_height=25;
$_img=imagecreatetruecolor($_width,$_height);
//��ɫ
$_white=imagecolorallocate($_img, 255, 255, 255);
//���
imagefill($_img,0,0,$_white);
// //��ɫ�߿�
// $_black=imagecolorallocate($_img, 0, 0, 0);
// imagerectangle($_img,0,0,$_width-1,$_height-1,$_black);
//�������6������
for($i=0;$i<6;$i++){
	$_rnd_color=imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
	imageline($_img,mt_rand(0,$_width),mt_rand(0,$_height),mt_rand(0,$_width),mt_rand(0,$_height),$_rnd_color);
}
//���ѩ��
for($i=0;$i<100;$i++){
	$_rnd_color=imagecolorallocate($_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
	imagestring($_img,2,mt_rand(1,$_width),mt_rand(1,$_height),'*', $_rnd_color);
}
//�����֤��
for ($i=0; $i <strlen($_SESSION['code']) ; $i++) { 
	$_rnd_color=imagecolorallocate($_img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,100));
	imagestring($_img,5,$i*$_width/strlen($_SESSION['code'])+mt_rand(1,10),mt_rand(1,$_height/2),$_SESSION['code'][$i],$_rnd_color);
}
//���ͼ��
header('Content-Type:image/png');
imagepng($_img);
//����ͼ��
imagedestroy($_img);
?>