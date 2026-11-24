(function($) {

    //Hover imágenes altura en Últimas Noticias Home
	$('.ultimas-noticias-list').ready(function() {
        $('.ultimas-noticias-list .edgtf-blog-list-item-inner').each(function() {
            var heightImg = $(this).height();
            $(this).find('.edgtf-image-hover').height(heightImg);
            // var heightImg = $('.ultimas-noticias-list .edgtf-blog-list-item-inner').height();
            // $('.ultimas-noticias-list .edgtf-image-hover').height(heightImg);
        });
	});    

    //Sliders de elementos de portfolio
    var sliderPortfolio = $('.portfolio-owl');

    function resizeWindow() {
        var evt = document.createEvent('UIEvents');
        evt.initUIEvent('resize', true, false,window,0);
        window.dispatchEvent(evt);
    }


    var fixOwl = function(){
        var $stage = $('.owl-stage'),
            stageW = $stage.width(),
            $el = $('.owl-item'),
            elW = 0,
            widthImg = $('.prensa-items .edgtf-portfolio-item').width();
        $el.each(function() {
            // elW += $(this).width()+ +($(this).css("margin-right").slice(0, -2));
            $(this).width(widthImg);
        });
        if ( elW > stageW ) {
            $stage.width(elW);
        }
    };

    if (sliderPortfolio.length) {
        sliderPortfolio.each(function(){
            // var sliderPortfolio = $(this);
            // sliderPortfolio.waitForImages(function(){
                sliderPortfolio.owlCarousel({
                    items:2,
                    autoplay : 2000,
                    autoWidth: true,
                    loop: true,
                    // transitionStyle: 'simpleSlide',
                    transitionStyle : 'fade',
                    nav: true,
                    dots: false,
                    navText: [
                        '<span class="edgtf-prev-icon"><i class="icon-arrows-left"></i></span>',
                        '<span class="edgtf-next-icon"><i class="icon-arrows-right"></i></span>'
                    ],
                    onInitialized: fixOwl,
                    onRefreshed: fixOwl
                });
            // });
        });

        setTimeout(function(){ resizeWindow(); }, 2000);

        $('.dropbtn').click(function() {
            $('.dropdown-content').slideToggle(300);
            return false;
        });


    }  
})(jQuery);




(function($){
    $('.dropbtn').click(function() {
        $('.dropdown-content').slideToggle(300);
        return false;
    });
    $(document).click(function() {
        if( $('.dropdown-content').is(":visible") == true){
          $('.dropdown-content').slideToggle(300);
        }
    });
})(jQuery);
