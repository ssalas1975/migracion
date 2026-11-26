
var detail = false;
var section = "";
var sc = false;
var previous_section = "";
var menu_opened = false;

// Update css real h
let root = document.documentElement;
root.style.setProperty('--newvh', window.innerHeight + "px");

$(window).resize(function(){
  root.style.setProperty('--newvh', window.innerHeight + "px");
});

// Menu

function menuchange () {
	if(menu_opened == false) {
		menu_opened = true;
		$(".resp_top_bar, .resp_menu_btn_inside_wrapper, .resp_menu_dark, .resp_menu_line").addClass("menu_opened");
	} else {
		menu_opened = false;
		$(".resp_top_bar, .resp_menu_btn_inside_wrapper, .resp_menu_dark, .resp_menu_line").removeClass("menu_opened");
	}
}

$(".resp_menu_btn, .resp_menu_btn_inside, .resp_menu_dark").click(function(){
	menuchange();
})

// load

$(window).load(function(){ $("body").removeClass('loading'); });

//

function check_section() {
	if(section != previous_section) {
		return true;
	} else {
		return false;
	}
}

class Swipe {
    constructor(element) {
        this.xDown = null;
        this.yDown = null;
        this.element = typeof(element) === 'string' ? document.querySelector(element) : element;

        this.element.addEventListener('touchstart', function(evt) {
            this.xDown = evt.touches[0].clientX;
            this.yDown = evt.touches[0].clientY;
        }.bind(this), false);
    }

    onLeft(callback) {
        this.onLeft = callback;
        return this;
    }

    onRight(callback) {
        this.onRight = callback;
        return this;
    }

    onUp(callback) {
        this.onUp = callback;
        return this;
    }

    onDown(callback) {
        this.onDown = callback;
        return this;
    }

    handleTouchMove(evt) {
        if ( ! this.xDown || ! this.yDown ) {
            return;
        }

        var xUp = evt.touches[0].clientX;
        var yUp = evt.touches[0].clientY;

        this.xDiff = this.xDown - xUp;
        this.yDiff = this.yDown - yUp;

        if ( Math.abs( this.xDiff ) > Math.abs( this.yDiff ) ) { // Most significant.
            if ( this.xDiff > 0 ) {
                this.onLeft();
            } else {
                this.onRight();
            }
        } else {
            if ( this.yDiff > 0 ) {
                this.onUp();
            } else {
                this.onDown();
            }
        }

        // Reset values.
        this.xDown = null;
        this.yDown = null;
    }

    run() {
        this.element.addEventListener('touchmove', function(evt) {
            this.handleTouchMove(evt).bind(this);
        }.bind(this), false);
    }

}

var swiper = new Swipe('#content');
swiper.onLeft(function() {
	if(detail == false && sc == false && section != "start") {
		gotodetail();
	}
});
swiper.onRight(function() {
	if(detail == true && sc == false && section != "start") {
		gotodetail();
	}
});

var vel = 500;

swiper.onUp(function() {
	if(section == "start") {
		$('html, body').animate({scrollTop: window.innerHeight},1000);
	} else if(section == "fot") {
		$('html, body').animate({scrollTop: $("#ff_rel").offset().top}, vel);
	} else if(section == "rel") {
		$('html, body').animate({scrollTop: $("#ff_mus").offset().top}, vel);
	} else if(section == "mus") {
		$('html, body').animate({scrollTop: $("#ff_cor").offset().top}, vel);
	} else if(section == "cor") {
		$('html, body').animate({scrollTop: $("#ff_sponsors").offset().top}, vel);
	}
	$('.sponsors_content').on('scroll', function() {
	    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
	    	console.log("Show previous");
	        $('html, body').animate({scrollTop: $("#ff_previous").offset().top}, vel);
	    }
	})
});
swiper.onDown(function() {
	if(section == "start") {
		$('html, body').animate({scrollTop: 0},10);
	} else if(section == "fot") {
		$('html, body').animate({scrollTop: 0},1000);
	} else if(section == "rel") {
		$('html, body').animate({scrollTop: $("#ff_fot").offset().top}, vel);
	} else if(section == "mus") {
		$('html, body').animate({scrollTop: $("#ff_rel").offset().top}, vel);
	} else if(section == "cor") {
		$('html, body').animate({scrollTop: $("#ff_mus").offset().top}, vel);
	} else if(section == "spo" && ($("#firsttitle").offset().top - $(window).scrollTop()) >= 0) {
		$('html, body').animate({scrollTop: $("#ff_cor").offset().top}, vel);
	} else if(section == "prev" && ($(".first_year").offset().top - $(window).scrollTop()) >= 0) {
		$('html, body').animate({scrollTop: $("#ff_sponsors").offset().top}, vel);
	}
});
swiper.run();

function action_scroll() {

	function removeall() {
		$(".figure, .sub_detail, .sponsors_content, .previous_events_content").removeClass("up");
		$(".figure, .sub_detail, .sponsors_content, .previous_events_content").removeClass("down");
		$(".figure, .menu_btn, .sub_detail, .sponsors_content, .previous_events_content").removeClass("active");
		$(".detail_arrow").removeClass("hide");
	}

	if($(window).scrollTop() < window.innerHeight/1.5){
		previous_section = section;
		section = "start";
		if(check_section()) {
			removeall();
			$(".f_fot, .f_rel, .f_mus, .f_cor, #detail_fot, #detail_rel, #detail_mus, #detail_cor").addClass("down");
			$(".detail_arrow").addClass("hide");
			if(detail == true) {
				$(".moving_sentence").removeClass("displaced");
				$(".detail_wrapper, .detail_arrow").removeClass("active");
				detail = false;
			}
		}
	} else if($(window).scrollTop() > $("#ff_fot").offset().top - window.innerHeight/2 && $(window).scrollTop() < $("#ff_fot").offset().top + window.innerHeight/2) {
		previous_section = section;
		section = "fot";
		if(check_section()) {
			removeall();
			$(".f_fot, #mb_1, #detail_fot").addClass("active");
			$(".f_rel, .f_mus, .f_cor, #detail_rel, #detail_mus, #detail_cor").addClass("down");
		}
	} else if($(window).scrollTop() > $("#ff_rel").offset().top - window.innerHeight/2 && $(window).scrollTop() < $("#ff_rel").offset().top + window.innerHeight/2) {
		removeall();
		previous_section = section;
		section = "rel";
		$(".f_rel, #mb_2, #detail_rel").addClass("active");
		$(".f_fot, #detail_fot").addClass("up");
		$(".f_mus, .f_cor, #detail_mus, #detail_cor").addClass("down");
	} else if($(window).scrollTop() > $("#ff_mus").offset().top - window.innerHeight/2 && $(window).scrollTop() < $("#ff_mus").offset().top + window.innerHeight/2) {
		previous_section = section;
		section = "mus";if(check_section()) {
			removeall();
			$(".f_mus, #mb_3, #detail_mus").addClass("active");
			$(".f_fot, .f_rel, #detail_fot, #detail_rel").addClass("up");
			$(".f_cor, #detail_cor").addClass("down");
		}
	} else if($(window).scrollTop() > $("#ff_cor").offset().top - window.innerHeight/2 && $(window).scrollTop() < $("#ff_cor").offset().top + window.innerHeight/2) {
		previous_section = section;
		section = "cor";
		if(check_section()) {
			removeall();
			$(".f_cor, #mb_4, #detail_cor").addClass("active");
			$(".f_fot, .f_rel, .f_mus, #detail_fot, #detail_rel, #detail_mus").addClass("up");
			closespecific();
		}
	} else if($(window).scrollTop() > $("#ff_sponsors").offset().top - window.innerHeight/2 && $(window).scrollTop() < $("#ff_sponsors").offset().top + window.innerHeight/2) {
		// Patrocinadores
		previous_section = section;
		section = "spo";
		if(check_section()) {
			removeall();
			$("#mb_5").addClass("active");
			setTimeout(function(){
				$(".sponsors_content").addClass("active");
			}, 500);
			$(".previous_events_content, .previous_events_wrapper").addClass("down");
		}
	} else if($(window).scrollTop() > $("#ff_previous").offset().top - window.innerHeight/2 && $(window).scrollTop() < $("#ff_previous").offset().top + window.innerHeight/2) {
		// Eventos anteriores
		previous_section = section;
		section = "prev";
		if(check_section()) {
			removeall();
			$("#mb_6").addClass("active");
			setTimeout(function(){
				$(".previous_events_content").addClass("active");
			}, 500);
			$(".sponsors_content, .sponsors_wrapper").addClass("up");
			$(".previous_events_content, .previous_events_wrapper").removeClass("down");
		}
	}

	if($(window).scrollTop() > $("#ff_sponsors").offset().top - window.innerHeight/2) {
		$(".f_fot, .f_rel, .f_mus, #detail_fot, #detail_rel, #detail_mus, .f_cor").addClass("up");
		if(sc != true) { gotodetail(); }
		$(".detail_arrow").addClass("hide");
		$(".sponsors_content").removeClass("down");
		$(".specific_bg").addClass("active");
		sc = true;
	} else {
		$(".sponsors_content").removeClass("active");
		$(".sponsors_content").addClass("down");
		$(".specific_bg").removeClass("active");
		sc = false;
	}
}

action_scroll();

jQuery(document).ready(function () {
	$(window).scroll(function(){
		action_scroll();
	});
});

$(".start").click(function(){
	$('html, body').animate({scrollTop: window.innerHeight},1000);
})

function closespecific () {
	if(sc==true) {gotodetail()};
	$(".specific_content").removeClass("active");
	$("body").removeClass("noscroll");
	sc = false;
}

function gotodetail () {
	if(detail == false) {
		$(".moving_sentence").addClass("displaced");
		$(".detail_wrapper, .detail_arrow").addClass("active");
		detail = true;
	} else if(detail == true){
		$(".moving_sentence").removeClass("displaced");
		$(".detail_wrapper, .detail_arrow").removeClass("active");
		detail = false;
	}
}

// Detect scroll wheel direction

// window.addEventListener("wheel", function(e) {
//     var dir = Math.sign(e.deltaY);
//     console.log(dir);
// });

$(".figure_wrapper, .detail_arrow").click(function(){
	gotodetail();
})

$("a").on('click',function(event){
	if(this.hash!=="" && this.hash!=="#sponsors" && this.hash!=="#previous"){
		event.preventDefault();
		var hash=this.hash;
		$('html, body').animate({scrollTop:$(hash).offset().top},200,function(){window.location.hash=hash;});
	} else if(this.hash == "#sponsors") {
		$('html, body').animate({scrollTop: $("#ff_sponsors").offset().top},200);
	} else if(this.hash == "#previous") {
		$('html, body').animate({scrollTop: $("#ff_previous").offset().top},200);
	}
});


















