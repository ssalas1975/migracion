var isiPad = navigator.userAgent.match(/iPad/i) != null;
var isiPhone = navigator.userAgent.match(/iPhone/i) != null;
var isiPod = navigator.userAgent.match(/iPod/i) != null;
var browserWidthResize = jQuery
(window).width();
  
if (browserWidthResize < 800) {
  var swiperF = new Swiper('.foto.swiper-container', {
        pagination: '.swiper-pagination',
		
        slidesPerView: 1,
		centeredSlides: true,
        spaceBetween: 0,
		
		nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
		autoplay: false,
		keyboardControl: true,
		loop: true
		
    });
	
  }
  
  else{
	  if(isiPhone == false ){
	  var swiperF = new Swiper('.foto.swiper-container', {
        pagination: '.swiper-pagination',
		
        slidesPerView: 2,
		centeredSlides: true,
        spaceBetween: 0,
		
		nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
		autoplay: false,
		keyboardControl: true,
		loop: true
	  });}
	  
	  }

jQuery(window).resize(function() {   
      var browserWidthResize = jQuery(window).width();  // New width
                 if (browserWidthResize < 800) {
                        swiperF.params.slidesPerView=1

                 } else { 
                      swiperF.params.slidesPerView=2
                 }
       

        });   


 var isiPhone = navigator.userAgent.match(/iPhone/i) != null;
  
  if(isiPhone == true ){
	
	 
  var swiperF = new Swiper('.foto.swiper-container', {
        pagination: '.swiper-pagination',
		
        slidesPerView: 1,
		centeredSlides: true,
        spaceBetween: 0,
		speed:1500,
		nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
		autoplay: 3000,
		keyboardControl: true,
		autoplayDisableOnInteraction:true,
		loop: true
		
   
	  
	  });
  }
  