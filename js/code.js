function code(){
	var code=document.getElementById("code");
	if (code!=null) {
		code.onclick=function(){
			this.src='code.php?tm='+Math.random();
		};
	}
}