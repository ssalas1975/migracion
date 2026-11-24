jQuery(document).ready(function() {

  jQuery('.dformats-list li').click(function(event) {
    if(jQuery(this).hasClass('active')) {
      jQuery(this).removeClass('active');
      jQuery(this).find('.dformats-info').slideUp();

    } else {
      jQuery('.dformats-list li').removeClass('active');
      jQuery('.dformats-list li .dformats-info').slideUp();
      jQuery(this).addClass('active');
      jQuery(this).find('.dformats-info').slideDown();
	
      jQuery('.dformats-right img').attr('src', jQuery(this).data("img"));
	  jQuery('.dformats-right img').attr('alt', jQuery(this).data("alt"));
    }
   });

});