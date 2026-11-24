(function($) {
	"use strict";

    var common = {};
    edgtf.modules.common = common;

    common.edgtfFluidVideo = edgtfFluidVideo;
    common.edgtfPreloadBackgrounds = edgtfPreloadBackgrounds;
    common.edgtfSetFloatingBackground = edgtfSetFloatingBackground;
    common.edgtfPrettyPhoto = edgtfPrettyPhoto;
    common.edgtfCheckHeaderStyleOnScroll = edgtfCheckHeaderStyleOnScroll;
    common.edgtfInitParallax = edgtfInitParallax;
    //common.edgtfSmoothScroll = edgtfSmoothScroll;
    common.edgtfEnableScroll = edgtfEnableScroll;
    common.edgtfDisableScroll = edgtfDisableScroll;
    common.edgtfWheel = edgtfWheel;
    common.edgtfKeydown = edgtfKeydown;
    common.edgtfPreventDefaultValue = edgtfPreventDefaultValue;
    common.edgtfOwlSlider = edgtfOwlSlider;
    common.edgtfInitSelfHostedVideoPlayer = edgtfInitSelfHostedVideoPlayer;
    common.edgtfSelfHostedVideoSize = edgtfSelfHostedVideoSize;
    common.edgtfInitBackToTop = edgtfInitBackToTop;
    common.edgtfBackButtonShowHide = edgtfBackButtonShowHide;
    common.edgtfCheckSVG = edgtfCheckSVG;
	common.edgtfSmoothTransition = edgtfSmoothTransition;


	$(document).ready(function() {
		edgtfFluidVideo();
        edgtfPreloadBackgrounds();
        edgtfPrettyPhoto();
        edgtfInitElementsAnimations();
        edgtfInitAnchor().init();
        edgtfInitVideoBackground();
        edgtfInitVideoBackgroundSize();
		edgtfSetFloatingBackground();
        edgtfSetContentBottomMargin();
		edgtfFooterHeight();
        //edgtfSmoothScroll();
        edgtfOwlSlider();
        edgtfInitSelfHostedVideoPlayer();
		edgtfSelfHostedVideoSize();
        edgtfInitBackToTop();
        edgtfBackButtonShowHide();
	});

    $(window).load(function() {
        edgtfCheckHeaderStyleOnScroll(); //called on load since all content needs to be loaded in order to calculate row's position right
        edgtfInitParallax();
		edgtfSmoothTransition();
    });

	$(window).resize(function() {
		edgtfInitVideoBackgroundSize();
		edgtfSelfHostedVideoSize();
		edgtfFooterHeight();
	});

	function edgtfFluidVideo() {
        fluidvids.init({
			selector: ['iframe'],
			players: ['www.youtube.com', 'player.vimeo.com']
		});
	}

    /**
     * Init Owl Carousel
     */
    function edgtfOwlSlider() {

        var sliders = $('.edgtf-owl-slider');

        if (sliders.length) {
            sliders.each(function(){

                var slider = $(this);
                slider.waitForImages(function(){
                    slider.owlCarousel({
                        items: 1,
                        loop:true,
                        animateIn:'scaleUpIn',
                        animateOut:'scaleUpOut',
                        nav: true,
                        autoHeight: true,
                        dots: false,
                        navText: [
                            '<span class="edgtf-prev-icon"><i class="icon-arrows-left"></i></span>',
                            '<span class="edgtf-next-icon"><i class="icon-arrows-right"></i></span>'
                        ]
                    });
                });
            });
        }

    }


    /*
     *	Preload background images for elements that have 'edgtf-preload-background' class
     */
    function edgtfPreloadBackgrounds(){

        $(".edgtf-preload-background").each(function() {
            var preloadBackground = $(this);
            if(preloadBackground.css("background-image") !== "" && preloadBackground.css("background-image") != "none") {

                var bgUrl = preloadBackground.attr('style');

                bgUrl = bgUrl.match(/url\(["']?([^'")]+)['"]?\)/);
                bgUrl = bgUrl ? bgUrl[1] : "";

                if (bgUrl) {
                    var backImg = new Image();
                    backImg.src = bgUrl;
                    $(backImg).load(function(){
                        preloadBackground.removeClass('edgtf-preload-background');
                    });
                }
            }else{
                $(window).load(function(){ preloadBackground.removeClass('edgtf-preload-background'); }); //make sure that edgtf-preload-background class is removed from elements with forced background none in css
            }
        });
    }

    function edgtfPrettyPhoto() {
        var markupWhole = '<div class="pp_pic_holder"> \
                        <div class="ppt">&nbsp;</div> \
                        <div class="pp_top"> \
                            <div class="pp_left"></div> \
                            <div class="pp_middle"></div> \
                            <div class="pp_right"></div> \
                        </div> \
                        <div class="pp_content_container"> \
                            <div class="pp_left"> \
                            <div class="pp_right"> \
                                <div class="pp_content"> \
                                    <div class="pp_loaderIcon"></div> \
                                    <div class="pp_fade"> \
                                        <a href="#" class="pp_expand" title="Expand the image">Expand</a> \
                                        <div class="pp_hoverContainer"> \
                                            <a class="pp_next" href="#"><span class="icon-arrows-right"></span></a> \
                                            <a class="pp_previous" href="#"><span class="icon-arrows-left"></span></a> \
                                        </div> \
                                        <div id="pp_full_res"></div> \
                                        <div class="pp_details"> \
                                            <div class="pp_nav"> \
                                                <a href="#" class="pp_arrow_previous">Previous</a> \
                                                <p class="currentTextHolder">0/0</p> \
                                                <a href="#" class="pp_arrow_next">Next</a> \
                                            </div> \
                                            <p class="pp_description"></p> \
                                            {pp_social} \
                                            <a class="pp_close" href="#">Close</a> \
                                        </div> \
                                    </div> \
                                </div> \
                            </div> \
                            </div> \
                        </div> \
                        <div class="pp_bottom"> \
                            <div class="pp_left"></div> \
                            <div class="pp_middle"></div> \
                            <div class="pp_right"></div> \
                        </div> \
                    </div> \
                    <div class="pp_overlay"></div>';

        $("a[data-rel^='prettyPhoto']").prettyPhoto({
            hook: 'data-rel',
            animation_speed: 'normal', /* fast/slow/normal */
            slideshow: false, /* false OR interval time in ms */
            autoplay_slideshow: false, /* true/false */
            opacity: 0.80, /* Value between 0 and 1 */
            show_title: true, /* true/false */
            allow_resize: true, /* Resize the photos bigger than viewport. true/false */
            horizontal_padding: 0,
            default_width: 960,
            default_height: 540,
            counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
            theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
            hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
            wmode: 'opaque', /* Set the flash wmode attribute */
            autoplay: true, /* Automatically start videos: True/False */
            modal: false, /* If set to true, only the close button will close the window */
            overlay_gallery: false, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
            keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
            deeplinking: false,
            custom_markup: '',
            social_tools: false,
            markup: markupWhole
        });
    }

    /*
     *	Check header style on scroll, depending on row settings
     */
    function edgtfCheckHeaderStyleOnScroll(){

        if($('[data-edgtf_header_style]').length > 0 && edgtf.body.hasClass('edgtf-header-style-on-scroll')) {

            var waypointSelectors = $('.edgtf-full-width-inner > .wpb_row.edgtf-section, .edgtf-full-width-inner > .edgtf-parallax-section-holder, .edgtf-container-inner > .wpb_row.edgtf-section, .edgtf-container-inner > .edgtf-parallax-section-holder, .edgtf-portfolio-single > .wpb_row.edgtf-section');
            var changeStyle = function(element){
                (element.data("edgtf_header_style") !== undefined) ? edgtf.body.removeClass('edgtf-dark-header edgtf-light-header').addClass(element.data("edgtf_header_style")) : edgtf.body.removeClass('edgtf-dark-header edgtf-light-header').addClass(''+edgtf.defaultHeaderStyle);
            };

            waypointSelectors.waypoint( function(direction) {
                if(direction === 'down') { changeStyle($(this.element)); }
            }, { offset: 0});

            waypointSelectors.waypoint( function(direction) {
                if(direction === 'up') { changeStyle($(this.element)); }
            }, { offset: function(){
                return -$(this.element).outerHeight();
            } });
        }
    }

    /*
     *	Start animations on elements
     */
    function edgtfInitElementsAnimations(){

        var touchClass = $('.edgtf-no-animations-on-touch'),
            noAnimationsOnTouch = true,
            elements = $('.edgtf-grow-in, .edgtf-fade-in-down, .edgtf-element-from-fade, .edgtf-element-from-left, .edgtf-element-from-right, .edgtf-element-from-top, .edgtf-element-from-bottom, .edgtf-flip-in, .edgtf-x-rotate, .edgtf-z-rotate, .edgtf-y-translate, .edgtf-fade-in, .edgtf-fade-in-left-x-rotate'),
            clasess,
            animationClass;

        if (touchClass.length) {
            noAnimationsOnTouch = false;
        }

        if(elements.length > 0 && noAnimationsOnTouch){
            elements.each(function(){
				$(this).appear(function() {
					clasess = $(this).attr('class').split(/\s+/);
					animationClass = clasess[1];

					$(this).addClass(animationClass+'-on');
				},{accX: 0, accY: edgtfQuadricGlobalVars.vars.edgtfElementAppearAmount});
            });
        }

    }


/*
 **	Sections with parallax background image
 */
function edgtfInitParallax(){

    if($('.edgtf-parallax-section-holder').length){
        $('.edgtf-parallax-section-holder').each(function() {

            var parallaxElement = $(this);
            if(parallaxElement.hasClass('edgtf-full-screen-height-parallax')){
                parallaxElement.height(edgtf.windowHeight);
                parallaxElement.find('.edgtf-parallax-content-outer').css('padding',0);
            }
            var speed = parallaxElement.data('edgtf-parallax-speed')*0.4;
            parallaxElement.parallax("50%", speed);
        });
    }
}

/*
 **	Anchor functionality
 */
var edgtfInitAnchor = edgtf.modules.common.edgtfInitAnchor = function() {

    /**
     * Set active state on clicked anchor
     * @param anchor, clicked anchor
     */
    var setActiveState = function(anchor){

        $('.edgtf-main-menu .edgtf-active-item, .edgtf-mobile-nav .edgtf-active-item, .edgtf-vertical-menu .edgtf-active-item, .edgtf-fullscreen-menu .edgtf-active-item').removeClass('edgtf-active-item');
        anchor.parent().addClass('edgtf-active-item');

        $('.edgtf-main-menu a, .edgtf-mobile-nav a, .edgtf-vertical-menu a, .edgtf-fullscreen-menu a').removeClass('current');
        anchor.addClass('current');
    };

    /**
     * Check anchor active state on scroll
     */
    var checkActiveStateOnScroll = function(){

        $('[data-edgtf-anchor]').waypoint( function(direction) {
            if(direction === 'down') {
                setActiveState($("a[href='"+window.location.href.split('#')[0]+"#"+$(this.element).data("edgtf-anchor")+"']"));
            }
        }, { offset: '50%' });

        $('[data-edgtf-anchor]').waypoint( function(direction) {
            if(direction === 'up') {
                setActiveState($("a[href='"+window.location.href.split('#')[0]+"#"+$(this.element).data("edgtf-anchor")+"']"));
            }
        }, { offset: function(){
            return -($(this.element).outerHeight() - 150);
        } });

    };

    /**
     * Check anchor active state on load
     */
    var checkActiveStateOnLoad = function(){
        var hash = window.location.hash.split('#')[1];

        if(hash !== "" && $('[data-edgtf-anchor="'+hash+'"]').length > 0){
            //triggers click which is handled in 'anchorClick' function
            $("a[href='"+window.location.href.split('#')[0]+"#"+hash).trigger( "click" );
        }
    };

    /**
     * Calculate header height to be substract from scroll amount
     * @param anchoredElementOffset, anchorded element offest
     */
    var headerHeihtToSubtract = function(anchoredElementOffset){

        if(edgtf.modules.header.behaviour == 'edgtf-sticky-header-on-scroll-down-up') {
            (anchoredElementOffset > edgtf.modules.header.stickyAppearAmount) ? edgtf.modules.header.isStickyVisible = true : edgtf.modules.header.isStickyVisible = false;
        }

        if(edgtf.modules.header.behaviour == 'edgtf-sticky-header-on-scroll-up') {
            (anchoredElementOffset > edgtf.scroll) ? edgtf.modules.header.isStickyVisible = false : '';
        }

        var headerHeight = edgtf.modules.header.isStickyVisible ? edgtfQuadricGlobalVars.vars.edgtfStickyHeaderTransparencyHeight : edgtfPerPageVars.vars.edgtfHeaderTransparencyHeight;

        return headerHeight;
    };

    /**
     * Handle anchor click
     */
    var anchorClick = function() {
        edgtf.document.on("click", ".edgtf-main-menu a, .edgtf-vertical-menu a, .edgtf-fullscreen-menu a, .edgtf-btn, .edgtf-anchor, .edgtf-mobile-nav a", function() {
            var scrollAmount;
            var anchor = $(this);
            var hash = anchor.prop("hash").split('#')[1];

            if(hash !== "" && $('[data-edgtf-anchor="' + hash + '"]').length > 0 && anchor.attr('href').split('#')[0] == window.location.href.split('#')[0]) {

                var anchoredElementOffset = $('[data-edgtf-anchor="' + hash + '"]').offset().top;
                scrollAmount = $('[data-edgtf-anchor="' + hash + '"]').offset().top - headerHeihtToSubtract(anchoredElementOffset);

                setActiveState(anchor);

                edgtf.html.stop().animate({
                    scrollTop: Math.round(scrollAmount)
                }, 1000, function() {
                    //change hash tag in url
                    if(history.pushState) { history.pushState(null, null, '#'+hash); }
                });
                return false;
            }
        });
    };

    return {
        init: function() {
            if($('[data-edgtf-anchor]').length) {
                anchorClick();
                checkActiveStateOnScroll();
                $(window).load(function() { checkActiveStateOnLoad(); });
            }
        }
    };

};

/*
 **	Video background initialization
 */
function edgtfInitVideoBackground(){

    $('.edgtf-section .edgtf-video-wrap .edgtf-video').mediaelementplayer({
        enableKeyboard: false,
        iPadUseNativeControls: false,
        pauseOtherPlayers: false,
        // force iPhone's native controls
        iPhoneUseNativeControls: false,
        // force Android's native controls
        AndroidUseNativeControls: false
    });

    //mobile check
    if(navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)){
        edgtfInitVideoBackgroundSize();
        $('.edgtf-section .edgtf-mobile-video-image').show();
        $('.edgtf-section .edgtf-video-wrap').remove();
    }
}

    /*
     **	Calculate video background size
     */
    function edgtfInitVideoBackgroundSize(){

        $('.edgtf-section .edgtf-video-wrap').each(function(){

            var element = $(this);
            var sectionWidth = element.closest('.edgtf-section').outerWidth();
            element.width(sectionWidth);

            var sectionHeight = element.closest('.edgtf-section').outerHeight();
            edgtf.minVideoWidth = edgtf.videoRatio * (sectionHeight+20);
            element.height(sectionHeight);

            var scaleH = sectionWidth / edgtf.videoWidthOriginal;
            var scaleV = sectionHeight / edgtf.videoHeightOriginal;
            var scale =  scaleV;
            if (scaleH > scaleV)
                scale =  scaleH;
            if (scale * edgtf.videoWidthOriginal < edgtf.minVideoWidth) {scale = edgtf.minVideoWidth / edgtf.videoWidthOriginal;}

            element.find('video, .mejs-overlay, .mejs-poster').width(Math.ceil(scale * edgtf.videoWidthOriginal +2));
            element.find('video, .mejs-overlay, .mejs-poster').height(Math.ceil(scale * edgtf.videoHeightOriginal +2));
            element.scrollLeft((element.find('video').width() - sectionWidth) / 2);
            element.find('.mejs-overlay, .mejs-poster').scrollTop((element.find('video').height() - (sectionHeight)) / 2);
            element.scrollTop((element.find('video').height() - sectionHeight) / 2);
        });

    }

    /*
     **	Set content bottom margin because of the uncovering footer
     */
    function edgtfSetContentBottomMargin(){
        var uncoverFooter = $('.edgtf-footer-uncover');

        if(uncoverFooter.length){
            $('.edgtf-content').css('margin-bottom', $('.edgtf-footer-inner').height());
        }
    }

	/*
	 **	Set footer top height
	 */
	function edgtfFooterHeight(){
		var footerTop = $('.edgtf-footer-top-holder');

		if(footerTop.length) {

			if(edgtf.windowWidth > 1024) {
				var maxHeight;

				footerTop.find('.edgtf-column').each(function () {
					var element = $(this);
					maxHeight = maxHeight > element.height() ? maxHeight : element.height();
				});

				footerTop.find('.edgtf-column-inner').height(maxHeight);
			} else {
				footerTop.find('.edgtf-column-inner').height('auto');
			}
		}
	}

	/*
	** Initiate Smooth Scroll
	*/
	function edgtfSmoothScroll(){

		if(edgtf.body.hasClass('edgtf-smooth-scroll')){

			var scrollTime = 0.4;			//Scroll time
			var scrollDistance = 300;		//Distance. Use smaller value for shorter scroll and greater value for longer scroll

			var mobile_ie = -1 !== navigator.userAgent.indexOf("IEMobile");

			var smoothScrollListener = function(event){
				event.preventDefault();

				var delta = event.wheelDelta / 120 || -event.detail / 3;
				var scrollTop = edgtf.window.scrollTop();
				var finalScroll = scrollTop - parseInt(delta * scrollDistance);

				TweenLite.to(edgtf.window, scrollTime, {
					scrollTo: {
						y: finalScroll, autoKill: !0
					},
					ease: Power1.easeOut,
					autoKill: !0,
					overwrite: 5
				});
			};

			if (!$('html').hasClass('touch') && !mobile_ie) {
				if (window.addEventListener) {
					window.addEventListener('mousewheel', smoothScrollListener, false);
					window.addEventListener('DOMMouseScroll', smoothScrollListener, false);
				}
			}
		}
	}

    function edgtfDisableScroll() {


        if (window.addEventListener) {
            window.addEventListener('DOMMouseScroll', edgtfWheel, false);
        }
        window.onmousewheel = document.onmousewheel = edgtfWheel;
        document.onkeydown = edgtfKeydown;

        if(edgtf.body.hasClass('edgtf-smooth-scroll')){
            window.removeEventListener('mousewheel', smoothScrollListener, false);
            window.removeEventListener('DOMMouseScroll', smoothScrollListener, false);
        }
    }

    function edgtfEnableScroll() {
        if (window.removeEventListener) {
            window.removeEventListener('DOMMouseScroll', edgtfWheel, false);
        }
        window.onmousewheel = document.onmousewheel = document.onkeydown = null;

        if(edgtf.body.hasClass('edgtf-smooth-scroll')){
            window.addEventListener('mousewheel', smoothScrollListener, false);
            window.addEventListener('DOMMouseScroll', smoothScrollListener, false);
        }
    }

    function edgtfWheel(e) {
        edgtfPreventDefaultValue(e);
    }

    function edgtfKeydown(e) {
        var keys = [37, 38, 39, 40];

        for (var i = keys.length; i--;) {
            if (e.keyCode === keys[i]) {
                edgtfPreventDefaultValue(e);
                return;
            }
        }
    }

    function edgtfPreventDefaultValue(e) {
        e = e || window.event;
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.returnValue = false;
    }

    function edgtfInitSelfHostedVideoPlayer() {

        var players = $('.edgtf-self-hosted-video');
            players.mediaelementplayer({
                audioWidth: '100%'
            });
    }

	function edgtfSelfHostedVideoSize(){

		$('.edgtf-self-hosted-video-holder .edgtf-video-wrap').each(function(){
			var thisVideo = $(this);

			var videoWidth = thisVideo.closest('.edgtf-self-hosted-video-holder').outerWidth();
			var videoHeight = videoWidth / edgtf.videoRatio;

			if(navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)){
				thisVideo.parent().width(videoWidth);
				thisVideo.parent().height(videoHeight);
			}

			thisVideo.width(videoWidth);
			thisVideo.height(videoHeight);

			thisVideo.find('video, .mejs-overlay, .mejs-poster').width(videoWidth);
			thisVideo.find('video, .mejs-overlay, .mejs-poster').height(videoHeight);
		});
	}

    function edgtfToTopButton(a) {
        
        var b = $("#edgtf-back-to-top");
        b.removeClass('off on');
        if (a === 'on') { b.addClass('on'); } else { b.addClass('off'); }
    }

    function edgtfBackButtonShowHide(){
        edgtf.window.scroll(function () {
            var b = $(this).scrollTop();
            var c = $(this).height();
            var d;
            if (b > 0) { d = b + c / 2; } else { d = 1; }
            if (d < 1e3) { edgtfToTopButton('off'); } else { edgtfToTopButton('on'); }
        });
    }

    function edgtfInitBackToTop(){
        var backToTopButton = $('#edgtf-back-to-top');
        backToTopButton.on('click',function(e){
            e.preventDefault();
            edgtf.html.animate({scrollTop: 0}, edgtf.window.scrollTop()/3, 'linear');
        });
    }


    function edgtfCheckSVG(element) {
        var el = element;
        var drawingEnabled = el.data('svg-drawing');
        var animationDelay;

        if(typeof el.data('animation-delay') !== 'undefined') {
            animationDelay = el.data('animation-delay');
        }

        if (drawingEnabled == 'yes') {
            edgtfDrawSVG(el, animationDelay);
        }

    }

    /**
     * Function for drawing slider svgs. Based on Codrops article 'SVG Drawing Animation'
     */
    function edgtfDrawSVG(svg, delay) {

        var animationDelay = typeof delay !== 'undefined' ? delay : 0;


        var svgs = Array.prototype.slice.call( svg.find('svg') ),
            svgArr = new Array(),
            resizeTimeout;
        // the svgs already shown...

        svgs.forEach( function( el, i ) {
            var svg = new SVGEl( el );
            svgArr[i] = svg;
            setTimeout(function( el ) {
                return function() {
                    svg.render();
                };
            }( el ), animationDelay );//0ms pause before drawing
        } );
    }

    var docElem = window.document.documentElement;
    window.requestAnimFrame = function(){

        return (
            window.requestAnimationFrame       ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame    ||
            window.oRequestAnimationFrame      ||
            window.msRequestAnimationFrame     ||
            function(/* function */ callback){
                window.setTimeout(callback, 1000 / 60);
            }
        );
    }();
    window.cancelAnimFrame = function(){

        return (
            window.cancelAnimationFrame       ||
            window.webkitCancelAnimationFrame ||
            window.mozCancelAnimationFrame    ||
            window.oCancelAnimationFrame      ||
            window.msCancelAnimationFrame     ||
            function(id){
                window.clearTimeout(id);
            }
        );
    }();
    function SVGEl( el ) {

        this.el = el;
        var frameRate = $(this.el).closest('.edgtf-svg-frames-class').data('svg-frames');
        this.image = this.el.previousElementSibling;
        this.current_frame = 0;
        this.total_frames = frameRate;//number of frames defines speed of drawing
        this.path = new Array();
        this.length = new Array();
        this.handle = 0;
        this.init();
    }
    SVGEl.prototype.init = function() {

        var self = this;
        [].slice.call( this.el.querySelectorAll( 'path' ) ).forEach( function( path, i ) {
            self.path[i] = path;
            var l = self.path[i].getTotalLength();
            self.length[i] = l;
            self.path[i].style.strokeDasharray = l + ' ' + l;
            self.path[i].style.strokeDashoffset = l;
        } );
    };
    SVGEl.prototype.render = function() {

        if( this.rendered ) return;
        this.rendered = true;
        this.draw();
    };
    SVGEl.prototype.draw = function() {

        var self = this,
            progress = this.current_frame/this.total_frames;
        if (progress > 1) {
            window.cancelAnimFrame(this.handle);
        } else {
            this.current_frame++;
            for(var j=0, len = this.path.length; j<len;j++){
                this.path[j].style.strokeDashoffset = Math.floor(this.length[j] * (1 - progress));
            }
            this.handle = window.requestAnimFrame(function() { self.draw(); });
        }
    };

	/**
	 *	Floating Background Script - start
	 */
	function edgtfSetFloatingBackground() {

		if ($('.edgtf-section .edgtf-section-background-elements').length) {
			window.qfb = new function() {
				this.$sections = $('.edgtf-section .edgtf-section-background-elements');

				this.init = function() {
					qfb.$sections.each(function() {
						var big_dim = Math.max($(this).outerHeight(), $(this).outerWidth()) * 3;
						$(this).find('.edgtf-section-background-elements-inner').css({
							'width': big_dim + 'px',
							'height': big_dim + 'px',
							'margin-left': -big_dim/2 + 'px',
							'margin-top': -big_dim/2 + 'px',
						});
						qfb.floatBackgroundElements($(this));
					});
				};

				this.floatBackgroundElements = function($section) {
					$section.find('.floater').remove();
					var floater = {
						width: $section.data('floaterwidth'),
						height: $section.data('floaterheight'),
						src: $section.data('floatersrc'),
						speed: $section.data('floaterspeed'),
					};
					var dir = $section.data('dir'); // Direction
					var zigzag = ($section.data('zigzag') == "yes") ? -1 : 1; // Movement in opposite directions
					var randsize = ($section.data('randsize') == "yes") ? 1 : 0; // Random scales of background element
					var final_offset = {
						x: 0,
						y: 0,
					};
					var big_dimension = $section.find('.edgtf-section-background-elements-inner').width();
					var max_offset = big_dimension / 2;
					var distance = {
						x: 0,
						y: 0,
					};
					var path_rotation = 0;
					var change_duration = qfb.change_duration;

					switch (dir) {
						case "top-bottom": case "bottom-top":
						distance.x = 2 * floater.height;
						distance.y = 1.4 * floater.width;
						path_rotation = 90;
						break;

						case "left-right": case "right-left":
						distance.x = 2 * floater.width;
						distance.y = 1.4 * floater.height;
						path_rotation = 0;
						break;

						case "top-left-bottom-right": case "bottom-right-top-left": case "top-right-bottom-left": case "bottom-left-top-right":
						distance.x = 1.7 * Math.pow(floater.width*floater.width + floater.height*floater.height, 0.5);
						distance.y = 1.1 * Math.pow(floater.width*floater.width + floater.height*floater.height, 0.5);
						max_offset *= 1.44;
						path_rotation = -45;
						case "top-right-bottom-left": case "bottom-left-top-right":
						path_rotation = 45;
						break;
					}

					// Positioning the background elements - Start
					var coords = [ [], [] ];
					for (var y = 0, i = 0; y < max_offset; y += distance.y, i++) {
						for (var x = (i%2)*distance.x/2; x < max_offset; x += distance.x) {
							coords[i%2].push({x: x, y: y});
							if (x) coords[i%2].push({x: -x, y: y});
							if (y) coords[i%2].push({x: x, y: -y});
							if (x&&y) coords[i%2].push({x: -x, y: -y});
						}
					}
					for (var j=0; j<2; j++) {
						var floaters_html = "";
						for (var i=0; i<coords[j].length; i++) {
							var r = Math.pow(coords[j][i].x*coords[j][i].x + coords[j][i].y*coords[j][i].y, 0.5);
							var f = ( (coords[j][i].y) ? (Math.atan(coords[j][i].y / coords[j][i].x) + (coords[j][i].x < 0)*Math.PI) : ((coords[j][i].x < 0) ? -Math.PI : 0) ) + path_rotation/180*Math.PI;
							coords[j][i] = {
								x: (r * Math.cos(f) + distance.x/2) / big_dimension * 100 + 50,
								y: (-r * Math.sin(f) + distance.y/2) / big_dimension * 100 + 50,
							};
							floaters_html +=
								'<div class="floater" style="top: '+coords[j][i].y+'%; left: '+coords[j][i].x+'%; -webkit-transform: translate(-50%,-50%) scale('+(randsize ? (Math.random()*0.6+0.4) : 1)+'); transform: translate(-50%,-50%) scale('+(randsize ? (Math.random()*0.6+0.4) : 1)+'); ">' +
								'<img src="'+floater.src+'">' +
								'</div>'
							;
						}
						$section.find('.edgtf-section-background-elements-inner.edgtf-s-bei-'+(j+1)).html(floaters_html);
					}
					// Positioning the background elements - End

					if (dir == "top-bottom" || dir == "top-right-bottom-left" || dir == "top-left-bottom-right") final_offset.y = 1;
					else if (dir == "bottom-top" || dir == "bottom-left-top-right" || dir == "bottom-right-top-left") final_offset.y = -1;
					else final_offset.y = 0;
					if (dir == "left-right" || dir == "top-left-bottom-right" || dir == "bottom-left-top-right") final_offset.x = 1;
					else if (dir == "right-left" || dir == "top-right-bottom-left" || dir == "bottom-right-top-left") final_offset.x = -1;
					else final_offset.x = 0;
					var shift_x = big_dimension/3*final_offset.x, shift_y = big_dimension/3*final_offset.y;
					var transition_duration = Math.round(Math.pow(shift_x*shift_x + shift_y*shift_y, 0.5) / floater.speed);
					$section.find('.edgtf-section-background-elements-inner').css({
						'-webkit-transition': '-webkit-transform '+transition_duration+'s linear',
						'transition': 'transform '+transition_duration+'s linear',
					});
					$section.find('.edgtf-section-background-elements-inner.edgtf-s-bei-1').addClass('running').css({
						'-webkit-transform':'translate('+shift_x+'px, '+shift_y+'px)',
						'transform':'translate('+shift_x+'px, '+shift_y+'px)',
					});
					$section.find('.edgtf-section-background-elements-inner.edgtf-s-bei-2').addClass('running').css({
						'-webkit-transform': 'translate('+(shift_x*zigzag)+'px, '+(shift_y*zigzag)+'px)',
						'transform': 'translate('+(shift_x*zigzag)+'px, '+(shift_y*zigzag)+'px)',
					});
				};
			};

			qfb.init();
		}
	}
	/**
	 *	Floating Background Script - end
	 */

	function edgtfSmoothTransition() {

		if (edgtf.body.hasClass('edgtf-smooth-page-transitions')) {
            $(window).focus(function() {
                $('.edgtf-wrapper-inner').fadeIn(0);
            });
			$('a').click(function(e) {
				var a = $(this);
				var hash = a.prop("hash").split('#')[1];
				if (
                    e.which == 1 && // check if the left mouse button has been pressed
                    (typeof a.data('rel') === 'undefined') && //Not pretty photo link
                    a.attr('href').indexOf(window.location.host) >= 0 && // check if the link is to the same domain
					(hash === undefined) && //Not anchor
                    (typeof a.attr('target') === 'undefined' || a.attr('target') === '_self') // check if the link opens in the same window
				) {
					e.preventDefault();
					$('.edgtf-wrapper-inner').fadeOut(1000, function() {
						window.location = a.attr('href');
					});
				}
			});
		}
	}

})(jQuery);


