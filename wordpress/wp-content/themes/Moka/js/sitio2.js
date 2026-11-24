
    //new WOW().init();

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
