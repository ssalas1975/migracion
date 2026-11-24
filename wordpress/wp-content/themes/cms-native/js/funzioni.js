



var hah = false;
var quitapone;
var hideTwitterAttempts = 0;



$(document).ready(inicia);
function inicia(){
	
		
		 
		 
	  $('.open-btn').click(abrecierra);

	  $('.close-btn').click(cierra);
	  $('.moduloemergente').click(cierra);	  
	  $('#navigation_trigger').click(menulateral);
	  $(".zoom").evenZoom();

	  if ($('.fullimg.principal').length > 0) {
	  
	      $('article').removeClass("noticia");
	      $('article').addClass("encabecadofullimg noticia");
	  }
	 

	   
}


var isiPad = navigator.userAgent.match(/iPad/i) != null;
var isiPhone = navigator.userAgent.match(/iPhone/i) != null;
var isiPod = navigator.userAgent.match(/iPod/i) != null;







function abrecierra(){
	
	$('html').toggleClass('show-nav');
	
	}


function cierra(){
	
	$('html').removeClass('show-nav');
	
	}

function menulateral(){
	$('body').toggleClass('has-sidebar');
	}
	

/*
var $post = $(".animated-t");
setInterval(function(){
   $post.removeClass("animated-t");
}, 8200);

var $post2 = $(".animated-t");
setInterval(function(){
    $post.addClass("animated-t");
}, 8255);

*/

/*********  SCROLL BAR POSITION *********/

$(function () {

    var scroll = {
        $body: $('html, body'),
        $scrollbar: $(".scrollbar"),
        $bar: $(".bar"),
        documentheight: document.body.scrollHeight,
        scrollbarwidth: $(".scrollbar").width(),
        toscroll: 0,
        oldscrolbarwidth: 0,
    }

    scroll.documentheight = document.body.scrollHeight;
    scroll.toscroll = (scroll.documentheight - window.innerHeight) / scroll.scrollbarwidth;
    $(scroll.$bar).css("width", $(window).scrollTop() / scroll.toscroll);

    window.addEventListener('scroll', function () {
        scroll.toscroll = (scroll.documentheight - window.innerHeight) / scroll.scrollbarwidth;
        $(scroll.$bar).stop().animate({ width: $(window).scrollTop() / scroll.toscroll }, 1000, 'swing', function () { });
    });

    $(scroll.$scrollbar).click(function (e) {
        scroll.oldscrolbarwidth = $(".scrollbar").width();
        scroll.toscroll = (scroll.documentheight - window.innerHeight) / scroll.scrollbarwidth;
        scroll.$body.stop().animate({ scrollTop: e.pageX * scroll.toscroll }, 1000, 'swing', function () { });
    });

    $(window).resize(function () {
        scroll.scrollbarwidth = $(".scrollbar").width();
        scroll.documentheight = document.body.scrollHeight;
        scroll.toscroll = (scroll.documentheight - window.innerHeight) / scroll.scrollbarwidth;
        $(scroll.$bar).css("width", $(window).scrollTop() / scroll.toscroll);
    });

});


