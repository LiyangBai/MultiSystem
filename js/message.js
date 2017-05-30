window.onload=function(){
	code();
	//客户端表单验证
	var fm=document.getElementsByTagName("form")[0];
	fm.onsubmit=function(){
		//信息内容验证
		if (fm.content.value.length>200) {
			alert('密码不能大于200位！');
			fm.content.focus();
			return false;
		}
		//验证码
		if (fm.code.value.length!=4) {
			alert('验证码不能小于4位！');
			fm.code.value="";
			fm.code.focus();
			return false;
		}
		return true;
	};
};