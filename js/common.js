$.fn.navigation = function(options) {
	var $this = this;
	var config = $.extend(
		{
			header		: $("#header"),
		   	depth1		: $(".lnb_1"),
		   	depth2		: $(".bg_navi"),
		   	depth2_item	: $(".bg_navi").find("a"),
           	speed 		: 500,
           	logo 		: $(".logo_m"),
        	basic_logo 	: $("#basic_logo")
		},
		options
	);

	return this.each(function() {
		// 각각에 메뉴에 오버하거나 포커스가 가면 네비열림
		$this.each(function() {
			var is_nav = false;
			config.depth1.on("focusin mouseover", function(e) {
				var target = $(e.currentTarget);
				var idx = target.index() + 1;
				config.depth1.removeClass("on");
				$(".lnb" + idx).addClass("on");

				if (is_nav == true) {
					return;
				};

                config.depth2.css({"height":"300px","background":"#222"/*, "border-bottom":"1px solid #eee"*/}).slideDown(config.speed);
                config.depth2.find("li.bgn").hide();
				config.depth2.find("li.bgn" + idx).stop().show();
				config.depth1.find("ul").slideDown(config.speed, function() {is_nav = false;});
			})
		})

		// 헤더영역에서 마우스가 나가면 네비게이션닫힘
		config.header.on('mouseleave', function() {
            config.depth1.removeClass("on");
			config.depth2.stop().slideUp();
			config.depth1.find("ul").stop().slideUp();
		});

		// 네비닫기 버튼을 클릭하면 네비게이션닫힘
		config.depth2_item.on('click', function(){
			config.depth1.removeClass("on");
			config.depth1.find("ul").stop().slideUp();
			config.depth2.stop().slideUp();
		});
	});
};

$(document).ready(function() {
	// 모바일 메뉴바
	$(".mobile_menu_area").css( "display","none");
	$(".menu_btn").click(function () {$(".mobile_menu_area").slideToggle("fast");});
	$(".menu_close").click(function () {$(".mobile_menu_area").slideToggle("fast");});

	$(".mobile_lnb .depth1").click(function () {
		var vol = $(".mobile_lnb .depth1").index(this);
		var height_tmp = $(".mobile_lnb .depth2 li").css("height").replace("px","");
		var obj = $(".mobile_lnb .depth2:eq(" + vol + ")");
		var len = $(".mobile_lnb .depth2:eq(" + vol + ") li").length;
		var check = ( obj.css("height").replace("px","") * 1 );
		if (check == 0) {
			obj.animate( {height:((height_tmp * len) + (len - 1)) + "px"} ,'500');
			$(".mobile_lnb .depth2").not(":eq(" + vol + ")").animate( {height:0} ,'500');
		} else {
			obj.animate( {height:0} ,'500');
		}
	});
});

//익스플로러버전
function getInternetExplorerVersion() {
    var rv = -1; // Return value assumes failure.
    if (navigator.appName == 'Microsoft Internet Explorer') {
		var ua = navigator.userAgent;
		var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");

    	if (re.exec(ua) != null) {
     		if (ua.match("Trident\/([4]\.[0])")) {
      			rv = parseFloat(8);
			} else if (ua.match("Trident\/([5]\.[0])")) {
				rv = parseFloat(9);
			} else if (ua.match("Trident\/([6]\.[0])")) {
				rv = parseFloat(10);
			} else {
				rv = parseFloat(RegExp.$1);
			}
		}
	}

    if (rv!=-1) {
    	return rv;
    } else {
    	return "";
    }
}