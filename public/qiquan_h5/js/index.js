document.write("<script src='http://libs.baidu.com/jquery/2.0.0/jquery.min.js'></\script>");
//document.write("<script src='js/back.js'></\script>");
function appIp() {
	return "http://wechat.17aitec.xyz";
}

var get = {
	fontSize: function() {
		var aa = window.innerWidth;
		document.getElementsByTagName('html')[0].setAttribute("style", "font-size:" + aa / 750 * 100 + "px");
		window.onresize = function() {
			var aa = window.innerWidth;
			document.getElementsByTagName('html')[0].setAttribute("style", "font-size:" + aa / 750 * 100 + "px");
		}

	}
}

var tipsbox = {
	show: function(obj) {
		var tipsboxdiv = '\
		<div id="tipsbox">\
			<div class="bg"></div>	\
	    	<div class="tipsbox_cont">\
	    		<div class="box_close">\
	    			<img src="images/close.png"/>\
	    		</div>\
	    		<div class="cont_txt">\
	    			<div class="cont_txt_title">\
	    				' + obj.title + '\
	    			</div>\
	    			<div class="cont_txt_cont">\
	    				' + obj.conttxt + '\
	    			</div>\
	    		</div>\
	    	</div>\
	    </div>';
		$(".wrap").append(tipsboxdiv);
		if(!obj.title) {
			$(".cont_txt_title").remove();
		}
		$("#tipsbox").attr("class", "show");
		$(".box_close").on("click", function() {
			tipsbox.hide();
		})
		$(".bg").on("click", function() {
			tipsbox.hide();
		})
	},
	hide: function() {
		$("#tipsbox").remove();
	},
	alertbox: function(obj, callback) {
		var alertboxdiv = '\
			<div id="alertbox">\
				<div class="bg"></div>	\
		    	<div class="alertbox_cont">\
		    		<div class="contbox">\
		    			<div class="box_close">\
		    				' + obj.toptitle + '\
			    			<img src="images/close.png"/>\
			    		</div>\
			    		<div class="cont_txt">\
			    			<div class="cont_txt_title">\
			    				' + obj.title + '\
			    			</div>\
			    			<div class="cont_txt_cont">\
			    				' + obj.conttxt + '\
			    			</div>\
			    		</div>\
			    		<input type="button" value="确定">\
		    		</div>\
		    	</div>\
		    </div>';
		$(".wrap").append(alertboxdiv);
		if(!obj.title) {
			$(".cont_txt_title").remove();
		}

		$(".box_close img").on("click", function() {
			tipsbox.alertboxhide();
		});
		$("#alertbox .bg").on("click", function() {
			tipsbox.alertboxhide();
		});
		$(".contbox input").on("click", function(ret) {
			callback(ret);
		});
	},
	alertboxhide: function() {
		$("#alertbox").remove();
	},
}

var test = {
	email: function(value) {
		var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if(!myreg.test(value)) {
			return false;
		} else {
			return true;
		}
	},
	phone: function(Tel) {
		var myreg = /^1[345678]\d{9}$/;
		if(!myreg.test(Tel)) {
			return false;
		} else {
			return true;
		}
	}
}