window.onload=function(){
	code();
	//客户端表单验证
	var fm=document.getElementsByTagName("form")[0];
	fm.onsubmit=function(){
		//密码验证
		if (fm.passward.value!='') {
			if (fm.passward.value.length<6) {
				alert('密码不能小于6位！');
				fm.passward.value="";
				fm.passward.focus();
				return false;
			}
		}
		//邮箱验证
		if (!/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(fm.email.value)) {
			alert('邮箱格式错误！');
			fm.email.focus();
			return false;
		}
		//qq验证
		if (fm.qq.value!='') {
			if (!/^[1-9]{1}[\d]{4,9}$/.test(fm.qq.value)) {
				alert('QQ长度错误！');
				fm.qq.value="";
				fm.qq.focus();
				return false;
			}
		}
		//网址验证
		if (fm.url.value!='') {
			if (!/^http(s)?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)) {
				alert('网址格式错误！');
				fm.url.value="";
				fm.url.focus();
				return false;
			}
		}
		//验证码
		if (fm.code.value.length!=4) {
			alert('验证码不能小于4位！');
			fm.code.value="";
			fm.code.focus();
			return false;
		}
		return true;
	}
}