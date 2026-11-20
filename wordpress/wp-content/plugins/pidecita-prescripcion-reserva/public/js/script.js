jQuery(document).ready(function(){
    jQuery('.carousel.mobile').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: false,
        autoplaySpeed: 2000,
        variableWidth: true,
        dots: true,
        arrows: false,
        infinite: false,
        responsive: [
            {
              breakpoint: 600,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
    });
  });
