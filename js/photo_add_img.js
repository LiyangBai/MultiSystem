window.onload=function(){
	var fm=document.getElementsByTagName("form")[0];
	var up=document.getElementById("up");
	up.onclick=function(){
		centerWindow("upimg.php?dir="+this.title,"up",400,400);
	};
	fm.onsubmit=function(){
		if (fm.name.value.length<2||fm.name.value.length>20) {
			alert('图片名不能小于2位或大于20位！');
			fm.name.value="";
			fm.name.focus();
			return false;
		}
		if (fm.url.value==' ') {
			alert('图片地址不能为空！');
			fm.url.focus();
			return false;
		}
		return true;
	};
};
function centerWindow(url,name,height,width){
	var top=(screen.height-height)/2;
	var left=(screen.width-width)/2;
	window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
}