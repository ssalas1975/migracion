
    //new WOW().init();

	$(document).ready(function(){
		scaleVideo();
		$(window).resize(function(){
		   scaleVideo();
		});
    
	});


	function scaleVideo(){
		 var wi=parseInt($("iframe").width());
        var hi=parseInt((wi*9)/16);
        $("iframe").height(hi);
		
	}
    $('#aspa').click(function(){
      $('#menu-principal').removeClass('show');
    });

   $('#accordion').accordion();
   
   
$('.heightacordeon').css('height','auto');

$(window).scroll(function (event) {
    var scroll = $(window).scrollTop();
	if( scroll > 100 ){
		$('.encabezado').css('background-color','#212B27');
    $("#logo").attr("src", "/wp-content/themes/Moka/img/logo-moka-01.svg");
	}else{
		$('.encabezado').css('background-color','transparent');
    $("#logo").attr("src", "/wp-content/themes/Moka/img/logo-moka-blog.svg");
	}
});


function cierracookie() {
  var a=document.getElementById("cookie");
  a.style.visibility = "hidden";
  createCookie('moka-cookie-advisor',true,365);
}




function createCookie(name, value, days) {
                var expires;

                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toGMTString();
                } else {
                    expires = "";
                }
                document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/";
}

function readCookie(name) {
                var nameEQ = escape(name) + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return unescape(c.substring(nameEQ.length, c.length));
                }
                return null;
}
$(document).ready(function(){
	if(readCookie('moka-cookie-advisor')==null){
	
		$("#cookie").show();

	}	
});
	

//$(function(){
    
//    var $window = $(window);        
    
//    var scrollTime = 0.5;           
//    var scrollDistance = 170;      
        
//    $window.on("mousewheel DOMMouseScroll", function(event){
        
//        event.preventDefault(); 
                                        
//        var delta = event.originalEvent.wheelDelta/120 || -event.originalEvent.detail/3;
//       var scrollTop = $window.scrollTop();
//        var finalScroll = scrollTop - parseInt(delta*scrollDistance);
            
//        TweenMax.to($window, scrollTime, {
//            scrollTo : { y: finalScroll, autoKill:true },
//                ease: Power1.easeOut,   
//                autoKill: true,
//                overwrite: 5                            
//            });
                    
//    });
    
//});
