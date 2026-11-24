(function($) {
    'use strict';

    var shortcodes = {};

    edgtf.modules.shortcodes = shortcodes;

    shortcodes.edgtfInitCounter = edgtfInitCounter;
    shortcodes.edgtfInitProgressBars = edgtfInitProgressBars;
    shortcodes.edgtfInitCountdown = edgtfInitCountdown;
    shortcodes.edgtfInitMessages = edgtfInitMessages;
    shortcodes.edgtfInitMessageHeight = edgtfInitMessageHeight;
    shortcodes.edgtfInitTestimonials = edgtfInitTestimonials;
    shortcodes.edgtfInitCarousels = edgtfInitCarousels;
    shortcodes.edgtfInitPieChart = edgtfInitPieChart;
    shortcodes.edgtfInitPieChartDoughnut = edgtfInitPieChartDoughnut;
    shortcodes.edgtfInitTabs = edgtfInitTabs;
    shortcodes.edgtfInitTabIcons = edgtfInitTabIcons;
    shortcodes.edgtfInitBlogListMasonry = edgtfInitBlogListMasonry;
    shortcodes.edgtfCustomFontResize = edgtfCustomFontResize;
    shortcodes.edgtfInitImageGallery = edgtfInitImageGallery;
    shortcodes.edgtfInitImageGalleryCarousel = edgtfInitImageGalleryCarousel;
    shortcodes.edgtfInitAccordions = edgtfInitAccordions;
    shortcodes.edgtfShowGoogleMap = edgtfShowGoogleMap;
    shortcodes.edgtfInitPortfolioListMasonry = edgtfInitPortfolioListMasonry;
    shortcodes.edgtfInitPortfolio = edgtfInitPortfolio;
    shortcodes.edgtfInitPortfolioMasonryFilter = edgtfInitPortfolioMasonryFilter;
    shortcodes.edgtfInitPortfolioSlider = edgtfInitPortfolioSlider;
    shortcodes.edgtfInitPortfolioLoadMore = edgtfInitPortfolioLoadMore;
    shortcodes.edgtfInitZigZagSeparator = edgtfInitZigZagSeparator;
    shortcodes.edgtfInitSvgIcons = edgtfInitSvgIcons;
    shortcodes.edgtfSetRevealingBackgrounds = edgtfSetRevealingBackgrounds;
    shortcodes.edgtfInitContentSlider = edgtfInitContentSlider;
    shortcodes.edgtfInitZoomingSlider = edgtfInitZoomingSlider;
    shortcodes.edgtfInitTeamSlider = edgtfInitTeamSlider;
	shortcodes.edgtfCheckSliderForHeaderStyle = edgtfCheckSliderForHeaderStyle;

    $(document).ready(function() {
        edgtfInitCounter();
        edgtfInitProgressBars();
        edgtfInitCountdown();
        edgtfIcon().init();
        edgtfIconSlider().init();
        edgtfInitMessages();
        edgtfInitMessageHeight();
        edgtfInitTestimonials();
        edgtfInitCarousels();
        edgtfInitPieChart();
        edgtfInitPieChartDoughnut();
		edgtfInitTabs();
        edgtfInitTabIcons();
        edgtfButton().init();
        edgtfInitBlogListMasonry();
		edgtfCustomFontResize();
        edgtfInitImageGallery();
        edgtfInitAccordions();
        edgtfShowGoogleMap();
        edgtfInitPortfolioListMasonry();
        edgtfInitPortfolio();
        edgtfInitPortfolioMasonryFilter();
        edgtfInitPortfolioSlider();
        edgtfInitPortfolioLoadMore();
        edgtfInitZigZagSeparator();
        edgtfSetRevealingBackgrounds();
        edgtfInitSvgIcons();
        edgtfInitContentSlider();
        edgtfInitTeamSlider();
        edgtfInitZoomingSlider();
        edgtfInitImageGalleryCarousel();
		edgtfSlider().init();
    });
    
    $(window).resize(function(){
        edgtfInitBlogListMasonry();
		edgtfCustomFontResize();     
    });

    /**
     * Counter Shortcode
     */
    function edgtfInitCounter() {

        var counters = $('.edgtf-counter');


        if (counters.length) {
            counters.each(function() {
                var counter = $(this);
                counter.appear(function() {
                    counter.parent().addClass('edgtf-counter-holder-show');

                    //Counter zero type
                    if (counter.hasClass('zero')) {
                        var max = parseFloat(counter.text());
                        counter.countTo({
                            from: 0,
                            to: max,
                            speed: 1500,
                            refreshInterval: 100
                        });
                    } else {
                        counter.absoluteCounter({
                            speed: 2000,
                            fadeInDelay: 1000
                        });
                    }

                },{accX: 0, accY: edgtfQuadricGlobalVars.vars.edgtfElementAppearAmount});
            });
        }

    }
    
        /*
    **	Horizontal progress bars shortcode
    */
    function edgtfInitProgressBars(){
        
        var progressBar = $('.edgtf-progress-bar');
        
        if(progressBar.length){
            
            progressBar.each(function() {
                
                var thisBar = $(this);
                
                thisBar.appear(function() {
                    edgtfInitToCounterProgressBar(thisBar);
                    if(thisBar.find('.edgtf-floating.edgtf-floating-inside') !== 0){
                        var floatingInsideMargin = thisBar.find('.edgtf-progress-content').height();
                        floatingInsideMargin += parseFloat(thisBar.find('.edgtf-progress-title-holder').css('padding-bottom'));
                        floatingInsideMargin += parseFloat(thisBar.find('.edgtf-progress-title-holder').css('margin-bottom'));
                        thisBar.find('.edgtf-floating-inside').css('margin-bottom',-(floatingInsideMargin)+'px');
                    }
                    var percentage = thisBar.find('.edgtf-progress-content').data('percentage'),
                        progressContent = thisBar.find('.edgtf-progress-content'),
                        progressNumber = thisBar.find('.edgtf-progress-number');

                    progressContent.css('width', '0%');
                    progressContent.animate({'width': percentage+'%'}, 1500);
                    progressNumber.css('left', '0%');
                    progressNumber.animate({'left': percentage+'%'}, 1500);

                });
            });
        }
    }
    /*
    **	Counter for horizontal progress bars percent from zero to defined percent
    */
    function edgtfInitToCounterProgressBar(progressBar){
        var percentage = parseFloat(progressBar.find('.edgtf-progress-content').data('percentage'));
        var percent = progressBar.find('.edgtf-progress-number .edgtf-percent');
        if(percent.length) {
            percent.each(function() {
                var thisPercent = $(this);
                thisPercent.parents('.edgtf-progress-number-wrapper').css('opacity', '1');
                thisPercent.countTo({
                    from: 0,
                    to: percentage,
                    speed: 1500,
                    refreshInterval: 50
                });
            });
        }
    }
    
    /*
    **	Function to close message shortcode
    */
    function edgtfInitMessages(){
        var message = $('.edgtf-message');
        if(message.length){
            message.each(function(){
                var thisMessage = $(this);
                thisMessage.find('.edgtf-close').click(function(e){
                    e.preventDefault();
                    $(this).parent().parent().fadeOut(500);
                });
            });
        }
    }
    
    /*
    **	Init message height
    */
   function edgtfInitMessageHeight(){
       var message = $('.edgtf-message.edgtf-with-icon');
       if(message.length){
           message.each(function(){
               var thisMessage = $(this);
               var textHolderHeight = thisMessage.find('.edgtf-message-text-holder').height();
               var iconHolderHeight = thisMessage.find('.edgtf-message-icon-holder').height();
               
               if(textHolderHeight > iconHolderHeight) {
                   thisMessage.find('.edgtf-message-icon-holder').height(textHolderHeight);
               } else {
                   thisMessage.find('.edgtf-message-text-holder').height(iconHolderHeight);
               }
           });
       }
   }

    /**
     * Countdown Shortcode
     */
    function edgtfInitCountdown() {

        var countdowns = $('.edgtf-countdown'),
            year,
            month,
            day,
            hour,
            minute,
            timezone,
            monthLabel,
            dayLabel,
            hourLabel,
            minuteLabel,
            secondLabel;

        if (countdowns.length) {

            countdowns.each(function(){

                //Find countdown elements by id-s
                var countdownId = $(this).attr('id'),
                    countdown = $('#'+countdownId),
                    digitFontSize,
                    labelFontSize;

                //Get data for countdown
                year = countdown.data('year');
                month = countdown.data('month');
                day = countdown.data('day');
                hour = countdown.data('hour');
                minute = countdown.data('minute');
                timezone = countdown.data('timezone');
                monthLabel = countdown.data('month-label');
                dayLabel = countdown.data('day-label');
                hourLabel = countdown.data('hour-label');
                minuteLabel = countdown.data('minute-label');
                secondLabel = countdown.data('second-label');
                digitFontSize = countdown.data('digit-size');
                labelFontSize = countdown.data('label-size');


                //Initialize countdown
                countdown.countdown({
                    until: new Date(year, month - 1, day, hour, minute, 44),
                    labels: ['Years', monthLabel, 'Weeks', dayLabel, hourLabel, minuteLabel, secondLabel],
                    format: 'ODHMS',
                    timezone: timezone,
                    padZeroes: true,
                    onTick: setCountdownStyle
                });

                function setCountdownStyle() {
                    countdown.find('.countdown-amount').css({
                        'font-size' : digitFontSize+'px',
                        'line-height' : digitFontSize+'px'
                    });
                    countdown.find('.countdown-period').css({
                        'font-size' : labelFontSize+'px'
                    });
                }

            });

        }

    }

    /**
     * Object that represents icon shortcode
     * @returns {{init: Function}} function that initializes icon's functionality
     */
    var edgtfIcon = edgtf.modules.shortcodes.edgtfIcon = function() {
        //get all icons on page
        var icons = $('.edgtf-icon-shortcode');

        /**
         * Function that triggers icon animation and icon animation delay
         */
        var iconAnimation = function(icon) {
            if(icon.hasClass('edgtf-icon-animation')) {
                icon.appear(function() {
                    icon.parent('.edgtf-icon-animation-holder').addClass('edgtf-icon-animation-show');
                }, {accX: 0, accY: edgtfQuadricGlobalVars.vars.edgtfElementAppearAmount});
            }
        };

        /**
         * Function that triggers icon hover color functionality
         */
        var iconHoverColor = function(icon) {
            if(typeof icon.data('hover-color') !== 'undefined') {
                var changeIconColor = function(event) {
                    event.data.icon.css('color', event.data.color);
                };

                var iconElement = icon.find('.edgtf-icon-element');
                var hoverColor = icon.data('hover-color');
                var originalColor = iconElement.css('color');

                if(hoverColor !== '') {
                    icon.on('mouseenter', {icon: iconElement, color: hoverColor}, changeIconColor);
                    icon.on('mouseleave', {icon: iconElement, color: originalColor}, changeIconColor);
                }
            }
        };

        /**
         * Function that triggers icon holder background color hover functionality
         */
        var iconHolderBackgroundHover = function(icon) {
            if(typeof icon.data('hover-background-color') !== 'undefined') {
                var changeIconBgColor = function(event) {
                    event.data.icon.css('background-color', event.data.color);
                };

                var hoverBackgroundColor = icon.data('hover-background-color');
                var originalBackgroundColor = icon.css('background-color');

                if(hoverBackgroundColor !== '') {
                    icon.on('mouseenter', {icon: icon, color: hoverBackgroundColor}, changeIconBgColor);
                    icon.on('mouseleave', {icon: icon, color: originalBackgroundColor}, changeIconBgColor);
                }
            }
        };

        /**
         * Function that initializes icon holder border hover functionality
         */
        var iconHolderBorderHover = function(icon) {
            if(typeof icon.data('hover-border-color') !== 'undefined') {
                var changeIconBorder = function(event) {
                    event.data.icon.css('border-color', event.data.color);
                };

                var hoverBorderColor = icon.data('hover-border-color');
                var originalBorderColor = icon.css('border-color');

                if(hoverBorderColor !== '') {
                    icon.on('mouseenter', {icon: icon, color: hoverBorderColor}, changeIconBorder);
                    icon.on('mouseleave', {icon: icon, color: originalBorderColor}, changeIconBorder);
                }
            }
        };

        return {
            init: function() {
                if(icons.length) {
                    icons.each(function() {
                        iconAnimation($(this));
                        iconHoverColor($(this));
                        iconHolderBackgroundHover($(this));
                        iconHolderBorderHover($(this));
                    });

                }
            }
        };
    };

    /**
     * Init testimonials shortcode
     */
    function edgtfInitTestimonials(){

        var testimonial = $('.edgtf-testimonials');
        if(testimonial.length){
            testimonial.each(function(){

                var thisTestimonial = $(this);

                thisTestimonial.appear(function() {
                    thisTestimonial.css('visibility','visible');
                },{accX: 0, accY: edgtfQuadricGlobalVars.vars.edgtfElementAppearAmount});

                var interval = 5000;
                var controlNav = true;
                var directionNav = false;
                var animationSpeed = 600;
                if(typeof thisTestimonial.data('animation-speed') !== 'undefined' && thisTestimonial.data('animation-speed') !== false) {
                    animationSpeed = thisTestimonial.data('animation-speed');
                }

                thisTestimonial.owlCarousel({
                    items: 1,
                    loop:true,
                    autoplay: true,
                    autoplayTimeout: interval,
                    navigation: directionNav,
                    //transitionStyle : 'fade', //fade, fadeUp, backSlide, goDown
                    animateIn:'fadeIn',
                    animateOut:'fadeOut',
                    autoHeight: true,
                    pagination: controlNav,
                    autoplaySpeed: animationSpeed,
                });

            });

        }

    }

    /**
     * Init Carousel shortcode
     */
    function edgtfInitCarousels() {

        var carouselHolders = $('.edgtf-carousel-holder'),
            carousel,
            numberOfItems,
            navigation,
            bulletsNavigation;

        if (carouselHolders.length) {
            carouselHolders.each(function(){
                carousel = carouselHolders.children('.edgtf-carousel');
                numberOfItems = carousel.data('items');
                navigation = (carousel.data('navigation') == 'yes') ? true : false;
                bulletsNavigation = (carousel.data('bullets-navigation') == 'yes') ? true : false;
				console.log(bulletsNavigation);

                carousel.owlCarousel({
                    loop:true,
                    autoplay: true,
                    autoplayTimeout: 3000,
                    items: numberOfItems,
                    responsiveClass:true,
                    responsive:{
                        0:{
                            items:1,
                        },
                        480:{
                            items:2,
                        },
                        768:{
                            items:3,
                        },
                        1024:{
                            items:numberOfItems,
                        }
                    },
                    dots: bulletsNavigation,
                    nav: navigation,
                    autoplaySpeed: 600,
                    navText: [
                        '<span class="edgtf-prev-icon"><i class="icon-arrows-left"></i></span>',
                        '<span class="edgtf-next-icon"><i class="icon-arrows-right"></i></span>'
                    ]
                });

            });
        }

    }

    /**
     * Init Pie Chart and Pie Chart With Icon shortcode
     */
    function edgtfInitPieChart() {

        var pieCharts = $('.edgtf-pie-chart-holder, .edgtf-pie-chart-with-icon-holder');

        if (pieCharts.length) {

            pieCharts.each(function () {               

                var pieChart = $(this),
                    percentageHolder = pieChart.children('.edgtf-percentage, .edgtf-percentage-with-icon'),
                    barColor,
                    trackColor,
                    lineWidth,
                    size = 145;
                    
                percentageHolder.appear(function() {
                    initToCounterPieChart(pieChart);
                    percentageHolder.css('opacity', '1');                    
                    percentageHolder.easyPieChart({
                        barColor: barColor,
                        trackColor: trackColor,
                        scaleColor: false,
                        lineCap: 'butt',
                        lineWidth: lineWidth,
                        animate: 1500,
                        size: size
                    });
                },{accX: 0, accY: edgtfQuadricGlobalVars.vars.edgtfElementAppearAmount});

            });

        }

    }

    /*
     **	Counter for pie chart number from zero to defined number
     */
    function initToCounterPieChart( pieChart ){

        pieChart.css('opacity', '1');
        var counter = pieChart.find('.edgtf-to-counter'),
            max = parseFloat(counter.text());
        counter.countTo({
            from: 0,
            to: max,
            speed: 1500,
            refreshInterval: 50
        });

    }

    /**
     * Init Pie Chart shortcode
     */
    function edgtfInitPieChartDoughnut() {

        var pieCharts = $('.edgtf-pie-chart-doughnut-holder, .edgtf-pie-chart-pie-holder');

        pieCharts.each(function(){

            var pieChart = $(this),
                canvas = pieChart.find('canvas'),
                chartID = canvas.attr('id'),
                chart = document.getElementById(chartID).getContext('2d'),
                data = [],
                jqChart = $(chart.canvas); //Convert canvas to JQuery object and get data parameters

            for (var i = 1; i<=10; i++) {

                var chartItem,
                    value = jqChart.data('value-' + i),
                    color = jqChart.data('color-' + i);
                
                if (typeof value !== 'undefined' && typeof color !== 'undefined' ) {
                    chartItem = {
                        value : value,
                        color : color
                    };
                    data.push(chartItem);
                }

            }

            if (canvas.hasClass('edgtf-pie')) {
                new Chart(chart).Pie(data,
                    {segmentStrokeColor : 'transparent'}
                );
            } else {
                new Chart(chart).Doughnut(data,
                    {segmentStrokeColor : 'transparent'}
                );
            }

        });

    }

    /*
    **	Init tabs shortcode
    */
    function edgtfInitTabs(){

       var tabs = $('.edgtf-tabs');
        if(tabs.length){
            tabs.each(function(){
                var thisTabs = $(this);

                if(thisTabs.hasClass('edgtf-horizontal')){
                    thisTabs.tabs();
                }
                else if(thisTabs.hasClass('edgtf-vertical')){
                    thisTabs.tabs().addClass( 'ui-tabs-vertical ui-helper-clearfix' );
                    thisTabs.find('.edgtf-tabs-nav > ul >li').removeClass( 'ui-corner-top' ).addClass( 'ui-corner-left' );
                }
            });
        }
    }

    /*
    **	Generate icons in tabs navigation
    */
    function edgtfInitTabIcons(){

        var tabContent = $('.edgtf-tab-container');
        if(tabContent.length){

            tabContent.each(function(){
                var thisTabContent = $(this);

                var id = thisTabContent.attr('id');
                var icon = '';
                if(typeof thisTabContent.data('icon-html') !== 'undefined' || thisTabContent.data('icon-html') !== 'false') {
                    icon = thisTabContent.data('icon-html');
                }

                var tabNav = thisTabContent.parents('.edgtf-tabs').find('.edgtf-tabs-nav > li > a[href="#'+id+'"]');

                if(typeof(tabNav) !== 'undefined') {
                    tabNav.children('.edgtf-icon-frame').append(icon);
                }
            });
        }
    }

    /**
     * Button object that initializes whole button functionality
     * @type {Function}
     */
    var edgtfButton = edgtf.modules.shortcodes.edgtfButton = function() {
        //all buttons on the page
        var buttons = $('.edgtf-btn');

        /**
         * Initializes button hover color
         * @param button current button
         */
        var buttonHoverColor = function(button) {
            if(typeof button.data('hover-color') !== 'undefined') {
                var changeButtonColor = function(event) {
                    event.data.button.css('color', event.data.color);
                };

                var originalColor = button.css('color');
                var hoverColor = button.data('hover-color');

                button.on('mouseenter', { button: button, color: hoverColor }, changeButtonColor);
                button.on('mouseleave', { button: button, color: originalColor }, changeButtonColor);
            }
        };



        /**
         * Initializes button hover background color
         * @param button current button
         */
        var buttonHoverBgColor = function(button) {
            if(typeof button.data('hover-bg-color') !== 'undefined') {
                var changeButtonBg = function(event) {
                    event.data.button.css('background-color', event.data.color);
                };

                var originalBgColor = button.css('background-color');
                var hoverBgColor = button.data('hover-bg-color');

                button.on('mouseenter', { button: button, color: hoverBgColor }, changeButtonBg);
                button.on('mouseleave', { button: button, color: originalBgColor }, changeButtonBg);
            }
        };

        /**
         * Initializes button border color
         * @param button
         */
        var buttonHoverBorderColor = function(button) {
            if(typeof button.data('hover-border-color') !== 'undefined') {
                var changeBorderColor = function(event) {
                    event.data.button.css('border-color', event.data.color);
                };

                var originalBorderColor = button.css('borderTopColor');
                var hoverBorderColor = button.data('hover-border-color');

                button.on('mouseenter', { button: button, color: hoverBorderColor }, changeBorderColor);
                button.on('mouseleave', { button: button, color: originalBorderColor }, changeBorderColor);
            }

            if(typeof button.data('text-hover-border-color') !== 'undefined') {
                var changeTextBorderColor = function(event) {
                     event.data.button.find('.edgtf-btn-text').css('border-color', event.data.color);
                };

                var originalTextBorderColor = button.find('.edgtf-btn-text').css('border-color');
                var hoverTextBorderColor = button.data('text-hover-border-color');

                button.on('mouseenter', { button: button, color: hoverTextBorderColor }, changeTextBorderColor);
                button.on('mouseleave', { button: button, color: originalTextBorderColor }, changeTextBorderColor);
            }
        };

        return {
            init: function() {
                if(buttons.length) {
                    buttons.each(function() {
                        buttonHoverColor($(this));
                        buttonHoverBgColor($(this));
                        buttonHoverBorderColor($(this));
                    });
                }
            }
        };
    };
    
    /*
    **	Init blog list masonry type
    */
    function edgtfInitBlogListMasonry(){
        var blogList = $('.edgtf-blog-list-holder.edgtf-masonry .edgtf-blog-list');
        if(blogList.length) {
            blogList.each(function() {
                var thisBlogList = $(this);
                thisBlogList.animate({opacity: 1});
                thisBlogList.isotope({
                    itemSelector: '.edgtf-blog-list-masonry-item',
                    masonry: {
                        columnWidth: '.edgtf-blog-list-masonry-grid-sizer',
                        gutter: '.edgtf-blog-list-masonry-grid-gutter'
                    }
                });
            });

        }
    }

	/*
	**	Custom Font resizing
	*/
	function edgtfCustomFontResize(){
		var customFont = $('.edgtf-custom-font-holder');
		if (customFont.length){
			customFont.each(function(){
				var thisCustomFont = $(this);
				var fontSize;
				var lineHeight;
				var coef1 = 1;
				var coef2 = 1;

				if (edgtf.windowWidth < 1200){
					coef1 = 0.8;
				}

				if (edgtf.windowWidth < 1000){
					coef1 = 0.7;
				}

				if (edgtf.windowWidth < 768){
					coef1 = 0.6;
					coef2 = 0.7;
				}

				if (edgtf.windowWidth < 600){
					coef1 = 0.5;
					coef2 = 0.6;
				}

				if (edgtf.windowWidth < 480){
					coef1 = 0.4;
					coef2 = 0.5;
				}

				if (typeof thisCustomFont.data('font-size') !== 'undefined' && thisCustomFont.data('font-size') !== false) {
					fontSize = parseInt(thisCustomFont.data('font-size'));

					if (fontSize > 70) {
						fontSize = Math.round(fontSize*coef1);
					}
					else if (fontSize > 35) {
						fontSize = Math.round(fontSize*coef2);
					}

					thisCustomFont.css('font-size',fontSize + 'px');
				}

				if (typeof thisCustomFont.data('line-height') !== 'undefined' && thisCustomFont.data('line-height') !== false) {
					lineHeight = parseInt(thisCustomFont.data('font-size'));

					if (lineHeight > 70 && edgtf.windowWidth < 1200) {
						lineHeight = '1.2em';
					}
					else if (lineHeight > 35 && edgtf.windowWidth < 768) {
						lineHeight = '1.2em';
					}
					else{
						lineHeight += 'px';
					}

					thisCustomFont.css('line-height', lineHeight);
				}
			});
		}
	}

    /*
     **	Show Google Map
     */
    function edgtfShowGoogleMap(){

        if($('.edgtf-google-map').length){
            $('.edgtf-google-map').each(function(){

                var element = $(this);

                var customMapStyle;
                if(typeof element.data('custom-map-style') !== 'undefined') {
                    customMapStyle = element.data('custom-map-style');
                }

                var colorOverlay;
                if(typeof element.data('color-overlay') !== 'undefined' && element.data('color-overlay') !== false) {
                    colorOverlay = element.data('color-overlay');
                }

                var saturation;
                if(typeof element.data('saturation') !== 'undefined' && element.data('saturation') !== false) {
                    saturation = element.data('saturation');
                }

                var lightness;
                if(typeof element.data('lightness') !== 'undefined' && element.data('lightness') !== false) {
                    lightness = element.data('lightness');
                }

                var zoom;
                if(typeof element.data('zoom') !== 'undefined' && element.data('zoom') !== false) {
                    zoom = element.data('zoom');
                }

                var pin;
                if(typeof element.data('pin') !== 'undefined' && element.data('pin') !== false) {
                    pin = element.data('pin');
                }

                var mapHeight;
                if(typeof element.data('height') !== 'undefined' && element.data('height') !== false) {
                    mapHeight = element.data('height');
                }

                var uniqueId;
                if(typeof element.data('unique-id') !== 'undefined' && element.data('unique-id') !== false) {
                    uniqueId = element.data('unique-id');
                }

                var scrollWheel;
                if(typeof element.data('scroll-wheel') !== 'undefined') {
                    scrollWheel = element.data('scroll-wheel');
                }
                var addresses;
                if(typeof element.data('addresses') !== 'undefined' && element.data('addresses') !== false) {
                    addresses = element.data('addresses');
                }

                var map = "map_"+ uniqueId;
                var geocoder = "geocoder_"+ uniqueId;
                var holderId = "edgtf-map-"+ uniqueId;

                edgtfInitializeGoogleMap(customMapStyle, colorOverlay, saturation, lightness, scrollWheel, zoom, holderId, mapHeight, pin,  map, geocoder, addresses);
            });
        }

    }
    /*
     **	Init Google Map
     */
    function edgtfInitializeGoogleMap(customMapStyle, color, saturation, lightness, wheel, zoom, holderId, height, pin,  map, geocoder, data){

        var mapStyles = [
            {
                stylers: [
                    {hue: color },
                    {saturation: saturation},
                    {lightness: lightness},
                    {gamma: 1}
                ]
            }
        ];

        var googleMapStyleId;

        if(customMapStyle){
            googleMapStyleId = 'edgtf-style';
        } else {
            googleMapStyleId = google.maps.MapTypeId.ROADMAP;
        }

        var qoogleMapType = new google.maps.StyledMapType(mapStyles,
            {name: "Edge Google Map"});

        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);

        if (!isNaN(height)){
            height = height + 'px';
        }

        var myOptions = {

            zoom: zoom,
            scrollwheel: wheel,
            center: latlng,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            scaleControl: false,
            scaleControlOptions: {
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            streetViewControl: false,
            streetViewControlOptions: {
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            panControl: false,
            panControlOptions: {
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            mapTypeControl: false,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'edgtf-style'],
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: google.maps.ControlPosition.LEFT_CENTER
            },
            mapTypeId: googleMapStyleId
        };

        map = new google.maps.Map(document.getElementById(holderId), myOptions);
        map.mapTypes.set('edgtf-style', qoogleMapType);

        var index;

        for (index = 0; index < data.length; ++index) {
            edgtfInitializeGoogleAddress(data[index], pin, map, geocoder);
        }

        var holderElement = document.getElementById(holderId);
        holderElement.style.height = height;
    }
    /*
     **	Init Google Map Addresses
     */
    function edgtfInitializeGoogleAddress(data, pin,  map, geocoder){
        if (data === '')
            return;
        var contentString = '<div id="content">'+
            '<div id="siteNotice">'+
            '</div>'+
            '<div id="bodyContent">'+
            '<p>'+data+'</p>'+
            '</div>'+
            '</div>';
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });
        geocoder.geocode( { 'address': data}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    icon:  pin,
                    title: data['store_title']
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });

                google.maps.event.addDomListener(window, 'resize', function() {
                    map.setCenter(results[0].geometry.location);
                });

            }
        });
    }

    function edgtfInitAccordions(){
        var accordion = $('.edgtf-accordion-holder');
        if(accordion.length){
            accordion.each(function(){

               var thisAccordion = $(this);

				if(thisAccordion.hasClass('edgtf-accordion')){

					thisAccordion.accordion({
						animate: "swing",
						collapsible: true,
						active: 0,
						icons: "",
						heightStyle: "content"
					});
				}

				if(thisAccordion.hasClass('edgtf-toggle')){

					var toggleAccordion = $(this);
					var toggleAccordionTitle = toggleAccordion.find('.edgtf-title-holder');
					var toggleAccordionContent = toggleAccordionTitle.next();

					toggleAccordion.addClass("accordion ui-accordion ui-accordion-icons ui-widget ui-helper-reset");
					toggleAccordionTitle.addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom");
					toggleAccordionContent.addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").hide();

					toggleAccordionTitle.each(function(){
						var thisTitle = $(this);
						thisTitle.hover(function(){
							thisTitle.toggleClass("ui-state-hover");
						});

						thisTitle.on('click',function(){
							thisTitle.toggleClass('ui-accordion-header-active ui-state-active ui-state-default ui-corner-bottom');
							thisTitle.next().toggleClass('ui-accordion-content-active').slideToggle(400);
						});
					});
				}
            });
        }
    }

    function edgtfInitImageGallery() {

        var galleries = $('.edgtf-image-gallery');

        if (galleries.length) {
            galleries.each(function () {
                var gallery = $(this).children('.edgtf-image-gallery-slider'),
                    autoplay = gallery.data('autoplay'),
                    animation = (gallery.data('animation') == 'slide') ? false : gallery.data('animation'),
                    navigation = (gallery.data('navigation') == 'yes'),
                    animationIn = false,
                    animationOut = false,
                    pagination = (gallery.data('pagination') == 'yes');


                if(animation){
                    animationIn = animation.concat('In');
                    animationOut = animation.concat('Out');
                }

                gallery.owlCarousel({
                    items: 1,
                    loop:true,
                    autoplay: true,
                    autoplayTimeout:autoplay * 1000,
                    nav: navigation,
                    animateOut: animationOut,
                    animateIn: animationIn,
                    autoHeight: true,
                    dots: pagination,
                    smartSpeed: 1000,
                    navText: [
                        '<span class="edgtf-prev-icon"><i class="icon-arrows-left"></i></span>',
                        '<span class="edgtf-next-icon"><i class="icon-arrows-right"></i></span>'
                    ]
                });
            });
        }

    }

    function edgtfInitImageGalleryCarousel() {

        var galleries = $('.edgtf-image-gallery.carousel');

        if (galleries.length) {
            galleries.each(function () {
                var gallery = $(this).children('.edgtf-image-gallery-carousel'),
                    autoplay = gallery.data('autoplay'),                    
                    navigation = (gallery.data('navigation') == 'yes'),
                    pagination = (gallery.data('pagination') == 'yes');            

                gallery.owlCarousel({
                    items: 1,
                    loop:true,
                    autoplay: true,
                    autoplayTimeout:autoplay * 1000,
                    nav: false,
                    autoHeight: true,
                    dots: pagination,
                    smartSpeed: 600,                   
                });

                var navPrev = $(this).parent().find('.edgtf-image-gallery-nav span.edgtf-prev-icon');
                var navNext = $(this).parent().find('.edgtf-image-gallery-nav span.edgtf-next-icon');
                navPrev.click(function() {
                    gallery.trigger('prev.owl.carousel', [600]);
                });
                navNext.click(function() {
                    gallery.trigger('next.owl.carousel', [600]);
                });
            });
        }

    }

    /**
     * Initializes portfolio list
     */
    function edgtfInitPortfolio(){
        var portList = $('.edgtf-portfolio-list-holder-outer');
        if(portList.length){
            portList.each(function(){
                var thisPortList = $(this);
                if((thisPortList.hasClass('edgtf-ptf-standard') || thisPortList.hasClass('edgtf-ptf-gallery') || thisPortList.hasClass('edgtf-ptf-gallery-with-space')) && !thisPortList.hasClass('edgtf-portfolio-slider-holder')){
                    thisPortList.appear(function(){
                        edgtfInitPortMixItUp(thisPortList);
                    });
                }    
            });
        }
    }
    /**
     * Initializes mixItUp function for specific container
     */
    function edgtfInitPortMixItUp(container){
        var filterClass = '';
        if(container)
        if(container.hasClass('edgtf-ptf-has-filter')){
            filterClass = container.find('.edgtf-portfolio-filter-holder-inner ul li').data('class');
            filterClass = '.'+filterClass;
        }
        
        var holderInner = container.find('.edgtf-portfolio-list-holder');
        holderInner.mixItUp({
            callbacks: {
                onMixLoad: function(){
                    holderInner.find('article').css('visibility','visible');
                },
                onMixStart: function(){
                    holderInner.find('article').css('visibility','visible');
                },
                onMixBusy: function(){
                    holderInner.find('article').css('visibility','visible');
                } 
            },           
            selectors: {
                filter: filterClass
            },
            animation: {
                effects: 'fade',
                duration: 500
            }
            
        });
        
    }
     /*
    **	Init portfolio list masonry type
    */
    function edgtfInitPortfolioListMasonry(){
        var portList = $('.edgtf-portfolio-list-holder-outer');
        if(portList.length && (portList)){
            portList.each(function() {
                var thisPortList = $(this);                
                if(thisPortList.hasClass('edgtf-ptf-masonry') || thisPortList.hasClass('edgtf-ptf-masonry-with-space')){
                    var thisPortListInner = thisPortList.find('.edgtf-portfolio-list-holder');
                    var size = thisPortList.find('.edgtf-portfolio-list-masonry-grid-sizer').width();
                    edgtfResizeMasonry(size,thisPortListInner);                    
                    edgtfInitMasonry(thisPortListInner);
                    $(window).resize(function(){
                        size = thisPortList.find('.edgtf-portfolio-list-masonry-grid-sizer').width();
                        edgtfResizeMasonry(size,thisPortListInner);
                        edgtfInitMasonry(thisPortListInner);
                    });
                }
            });
        }
    }

    function edgtfInitMasonry(container){
        
        container.isotope({
            itemSelector: '.edgtf-portfolio-item',
            masonry: {
                columnWidth: '.edgtf-portfolio-list-masonry-grid-sizer',
                gutter: '.edgtf-portfolio-list-masonry-grid-gutter'
            }
        });
        container.animate({opacity: 1});
    }
    
    function edgtfResizeMasonry(size,container){
        
        var defaultMasonryItem = container.find('.edgtf-default-masonry-item');
        var largeWidthMasonryItem = container.find('.edgtf-large-width-masonry-item');
        var largeHeightMasonryItem = container.find('.edgtf-large-height-masonry-item');
        var largeWidthHeightMasonryItem = container.find('.edgtf-large-width-height-masonry-item');

        defaultMasonryItem.css('height', size);
        largeWidthMasonryItem.css('height', size);
        largeWidthHeightMasonryItem.css('height', Math.round(2*size));
        largeHeightMasonryItem.css('height', Math.round(2*size));
        
        if(edgtf.windowWidth < 600){
            largeWidthMasonryItem.css('height',Math.round(size/2));
            largeWidthHeightMasonryItem.css('height', size);
        }
    }

    /**
     * Initializes portfolio masonry filter
     */
    function edgtfInitPortfolioMasonryFilter(){
        
        var filterHolder = $('.edgtf-portfolio-filter-holder.edgtf-masonry-filter');
        
        if(filterHolder.length){
            filterHolder.each(function(){
               
                var thisFilterHolder = $(this);
                
                var portfolioIsotopeAnimation = null;
                
                var filter = thisFilterHolder.find('ul li').data('class');
                
                thisFilterHolder.find('.filter:first').addClass('current');
                
                thisFilterHolder.find('.filter').click(function(){

                    var currentFilter = $(this);
                    clearTimeout(portfolioIsotopeAnimation);

                    $('.isotope, .isotope .isotope-item').css('transition-duration','0.8s');

                    portfolioIsotopeAnimation = setTimeout(function(){
                        $('.isotope, .isotope .isotope-item').css('transition-duration','0s'); 
                    },700);

                    var selector = $(this).attr('data-filter');
                    thisFilterHolder.siblings('.edgtf-portfolio-list-holder-outer').find('.edgtf-portfolio-list-holder').isotope({ filter: selector });

                    thisFilterHolder.find('.filter').removeClass('current');
                    currentFilter.addClass('current');

                    return false;

                });
                
            });
        }
    }
    /**
     * Initializes portfolio slider
     */
    
    function edgtfInitPortfolioSlider(){
        var portSlider = $('.edgtf-portfolio-list-holder-outer.edgtf-portfolio-slider-holder');
        if(portSlider.length){
            portSlider.each(function(){
                var thisPortSlider = $(this);
                var sliderWrapper = thisPortSlider.children('.edgtf-portfolio-list-holder');
                var numberOfItems = thisPortSlider.data('items');
                var navigation = false;              

                sliderWrapper.owlCarousel({
                    loop:true,
                    responsiveClass:true,
                    responsive:{
                        0:{
                            items:1,
                        },
                        480:{
                            items:2,
                        },
                        768:{
                            items:3,
                        },
                        1024:{
                            items:numberOfItems,
                        }
                    },
                    autoplay:false,              
                    items: numberOfItems,
                    dots: true,
                    nav: navigation,
                    smartSpeed: 600,                    
                    animateOut:'fadeOut',
                    animateIn:'fadeIn',
                    navText: [
                        '<span class="edgtf-prev-icon"><i class="icon-arrows-left"></i></span>',
                        '<span class="edgtf-next-icon"><i class="icon-arrows-right"></i></span>'
                    ]
                });
            });
        }
    }
    /**
     * Initializes portfolio load more function
     */
    function edgtfInitPortfolioLoadMore(){
        var portList = $('.edgtf-portfolio-list-holder-outer.edgtf-ptf-load-more');
        if(portList.length){
            portList.each(function(){
                
                var thisPortList = $(this);
                var thisPortListInner = thisPortList.find('.edgtf-portfolio-list-holder');
                var nextPage; 
                var maxNumPages;
                var loadMoreButton = thisPortList.find('.edgtf-ptf-list-load-more a');
                
                if (typeof thisPortList.data('max-num-pages') !== 'undefined' && thisPortList.data('max-num-pages') !== false) {  
                    maxNumPages = thisPortList.data('max-num-pages');
                }
                
                loadMoreButton.on('click', function (e) {  
                    var loadMoreDatta = edgtfGetPortfolioAjaxData(thisPortList);
                    nextPage = loadMoreDatta.nextPage;
                    e.preventDefault();
                    e.stopPropagation(); 
                    if(nextPage <= maxNumPages){
                        var ajaxData = edgtfSetPortfolioAjaxData(loadMoreDatta);
                        $.ajax({
                            type: 'POST',
                            data: ajaxData,
                            url: edgtCoreAjaxUrl,
                            success: function (data) {
                                nextPage++;
                                thisPortList.data('next-page', nextPage);
                                var response = $.parseJSON(data);
                                var responseHtml = edgtfConvertHTML(response.html); //convert response html into jQuery collection that Mixitup can work with
                                thisPortList.waitForImages(function(){    
                                    setTimeout(function() {                                        
                                        if(thisPortList.hasClass('edgtf-ptf-masonry') || thisPortList.hasClass('edgtf-ptf-masonry-with-space')){
                                            var size = thisPortList.find('.edgtf-portfolio-list-masonry-grid-sizer').width();                                                     
                                            thisPortListInner.append( responseHtml ).isotope( 'appended', responseHtml ).isotope('layout');
                                            edgtfResizeMasonry(size,thisPortListInner);
                                            edgtfInitMasonry(thisPortListInner);
                                        }else{
                                            thisPortListInner.mixItUp('append',responseHtml);    
                                        }
                                    },400);                                    
                                });                           
                            }
                        });
                    }
                    if(nextPage === maxNumPages){
                        loadMoreButton.hide();
                    }
                });
                
            });
        }
    }
    
    function edgtfConvertHTML ( html ) {
        var newHtml = $.trim( html ),
                $html = $(newHtml ),
                $empty = $();

        $html.each(function ( index, value ) {
            if ( value.nodeType === 1) {
                $empty = $empty.add ( this );
            }
        });

        return $empty;
    };
    /**
     * Initializes portfolio load more data params
     * @param portfolio list container with defined data params
     * return array
     */
    function edgtfGetPortfolioAjaxData(container){
        var returnValue = {};
        
        returnValue.type = '';
        returnValue.columns = '';
        returnValue.gridSize = '';
        returnValue.orderBy = '';
        returnValue.order = '';
        returnValue.number = '';
        returnValue.imageSize = '';
        returnValue.filter = '';
        returnValue.filterOrderBy = '';
        returnValue.category = '';
        returnValue.selectedProjectes = '';
        returnValue.showLoadMore = '';
        returnValue.titleTag = '';
        returnValue.nextPage = '';
        returnValue.maxNumPages = '';
        
        if (typeof container.data('type') !== 'undefined' && container.data('type') !== false) {
            returnValue.type = container.data('type');
        }
        if (typeof container.data('grid-size') !== 'undefined' && container.data('grid-size') !== false) {                    
            returnValue.gridSize = container.data('grid-size');
        }
        if (typeof container.data('columns') !== 'undefined' && container.data('columns') !== false) {                    
            returnValue.columns = container.data('columns');
        }
        if (typeof container.data('order-by') !== 'undefined' && container.data('order-by') !== false) {                    
            returnValue.orderBy = container.data('order-by');
        }
        if (typeof container.data('order') !== 'undefined' && container.data('order') !== false) {                    
            returnValue.order = container.data('order');
        }
        if (typeof container.data('number') !== 'undefined' && container.data('number') !== false) {                    
            returnValue.number = container.data('number');
        }
		if (typeof container.data('image-size') !== 'undefined' && container.data('image-size') !== false) {
            returnValue.imageSize = container.data('image-size');
        }
        if (typeof container.data('filter') !== 'undefined' && container.data('filter') !== false) {                    
            returnValue.filter = container.data('filter');
        }
        if (typeof container.data('filter-order-by') !== 'undefined' && container.data('filter-order-by') !== false) {                    
            returnValue.filterOrderBy = container.data('filter-order-by');
        }
        if (typeof container.data('category') !== 'undefined' && container.data('category') !== false) {                    
            returnValue.category = container.data('category');
        }
        if (typeof container.data('selected-projects') !== 'undefined' && container.data('selected-projects') !== false) {                    
            returnValue.selectedProjectes = container.data('selected-projects');
        }
        if (typeof container.data('show-load-more') !== 'undefined' && container.data('show-load-more') !== false) {                    
            returnValue.showLoadMore = container.data('show-load-more');
        }
        if (typeof container.data('title-tag') !== 'undefined' && container.data('title-tag') !== false) {                    
            returnValue.titleTag = container.data('title-tag');
        }
        if (typeof container.data('next-page') !== 'undefined' && container.data('next-page') !== false) {                    
            returnValue.nextPage = container.data('next-page');
        }
        if (typeof container.data('max-num-pages') !== 'undefined' && container.data('max-num-pages') !== false) {                    
            returnValue.maxNumPages = container.data('max-num-pages');
        }
        return returnValue;
    }
     /**
     * Sets portfolio load more data params for ajax function
     * @param portfolio list container with defined data params
     * return array
     */
    function edgtfSetPortfolioAjaxData(container){
        var returnValue = {
            action: 'edgt_core_portfolio_ajax_load_more',
            type: container.type,
            columns: container.columns,
            gridSize: container.gridSize,
            orderBy: container.orderBy,
            order: container.order,
            number: container.number,
            imageSize: container.imageSize,
            filter: container.filter,
            filterOrderBy: container.filterOrderBy,
            category: container.category,
            selectedProjectes: container.selectedProjectes,
            showLoadMore: container.showLoadMore,
            titleTag: container.titleTag,
            nextPage: container.nextPage
        };
        return returnValue;
    }

    /**
     * Initializes Zig Zag Separator
     */
    function edgtfInitZigZagSeparator(){

        if($('.edgtf-zig-zag-separator-holder').length && $('.edgtf-no-animations-on-touch').length === 0){
            $('.edgtf-zig-zag-separator-holder').each(function(){
                var separator = $(this);
                separator.appear(function() {
                    separator.addClass('edgtf-zig-zag-separator-on');
                    edgtf.modules.common.edgtfCheckSVG(separator);
                },{accX: 0, accY: edgtfQuadricGlobalVars.vars.edgtfElementAppearAmount});
            });
        }

    }

    /**
     * Initializes SVG Icons
     */
    function edgtfInitSvgIcons(){

        if($('.edgtf-svg-icons').length && $('.edgtf-no-animations-on-touch').length === 0){
            $('.edgtf-svg-icons').each(function(){
                var icon = $(this);
                icon.appear(function() {
                    icon.addClass('edgtf-svg-icons-on');
                    edgtf.modules.common.edgtfCheckSVG(icon);
                },{accX: 0, accY: edgtfQuadricGlobalVars.vars.edgtfElementAppearAmount});
            });
        }

    }



    /**
     *	Holder with Revealing Background Script
     */
    function edgtfSetRevealingBackgrounds() {

        if ($('.edgtf-curtain-holder').length) {
            window.ech = new function() {
                this.$targets = $('.edgtf-curtain-holder');

                this.init = function() {
                    this.$targets
                        .mouseenter(function(e) {
                            ech.handleMouseenter($(this),e);
                        })
                        .mouseleave(function(e) {
                            ech.handle_mouseleave($(this),e);
                        })
                    ;
                };

                this.handleMouseenter = function($target,event) {
                    var offset = $target.offset();
                    var t = event.pageY - offset.top;
                    var b = $target.outerHeight() - t;
                    var l = event.pageX - offset.left;
                    var r = $target.outerWidth() - l;
                    var dir;
                    if (t <= b && t <= l && t <= r) {
                        dir = 'top';
                    }
                    else if (b < t && b <= l && b <= r) {
                        dir = 'bottom';
                    }
                    else if (l <= r && l < t && l < b) {
                        dir = 'left';
                    }
                    else if (r < l && r < t && r < b) {
                        dir = 'right';
                    }
                    else {
                        dir = "instant";
                    }
                    ech.makeChange($target,'in',dir);
                };

                this.handle_mouseleave = function($target,event) {
                    var offset = $target.offset();
                    var t = event.pageY - offset.top;
                    var b = $target.outerHeight();
                    var l = event.pageX - offset.left;
                    var r = $target.outerWidth();
                    var dir;
                    if (t <= 0 && t <= l && t <= r - l) {
                        dir = 'top';
                    }
                    else if (b <= t && b - t <= l && b - t <= r - l) {
                        dir = 'bottom';
                    }
                    else if (l <= 0 && l < t && l < b - t) {
                        dir = 'left';
                    }
                    else if (r <= l && r - l < t && r - l < b - t) {
                        dir = 'right';
                    }
                    else {
                        dir = "instant";
                    }
                    ech.makeChange($target,'out',dir);
                };

                this.makeChange = function($target,inout,dir) {
                    var opposite = {'top': 'bottom', 'bottom': 'top', 'left': 'right', 'right': 'left'};
                    var defaultImage = $target.is('.edgtf-default-image');
                    var duration = (dir=="instant") ? 0 : 300;
                    $target
                        .find('.edgtf-curtain.' + ((defaultImage) ? 'edgtf-curtain-'+dir : 'edgtf-curtain-'+opposite[dir]))
                        .addClass((inout==((defaultImage) ? "in" : "out")) ? 'edgtf-pulled' : '')
                        .removeClass((inout==((defaultImage) ? "out" : "in")) ? 'edgtf-pulled' : '')
                        .css({
                            '-webkit-transition': 'all '+duration+'ms ease 0ms',
                            '-transition': 'all '+duration+'ms ease 0ms',
                        })
                        .siblings()
                        .addClass((inout==((defaultImage) ? "in" : "out")) ? 'edgtf-pulled' : '')
                        .removeClass((inout==((defaultImage) ? "out" : "in")) ? 'edgtf-pulled' : '')
                        .css({
                            '-webkit-transition': 'all 0ms ease ' + ((inout==((defaultImage) ? "in" : "out")) ? duration : 0) + 'ms',
                            '-transition': 'all 0ms ease ' + ((inout==((defaultImage) ? "in" : "out")) ? duration : 0) + 'ms',
                        })
                    ;
                };
            };

            ech.init();
        }
    }


    /*
    * Init Icon Slider
    */

    var edgtfIconSlider = edgtf.modules.shortcodes.edgtfIconSlider = function() {

        var slidesWrapper = $('.edgtf-icon-slider-container');

        var initIconSlider = function(sliderWrapper){   

            var controlsHolder = sliderWrapper.find('.edgtf-icon-slider-controls-holder');
            var initialIconColor = sliderWrapper.find('.edgtf-icon-slider-nav-icon:not(.active)').css('color');
            var slider = sliderWrapper.find('.edgtf-icon-slider-container-inner');
            
            slider.owlCarousel({
                items: 1,
                nav: false,
                dots: true,
                dotsContainer: '.edgtf-icon-slider-controls-holder-inner',
                onInitialized: function(){
                    controlsHolder.show();
                    initIconSliderStyle(initialIconColor, controlsHolder);
                },
                onTranslated: function(){
                    initIconSliderStyle(initialIconColor, controlsHolder);
                }
            });
        }

        var initIconSliderStyle = function(initialColor, controls){
            var iconActiveColor = controls.data('icon-active-color');
            
            if(iconActiveColor != "" && typeof(iconActiveColor) != 'undefined'){
                var icons = controls.find('.edgtf-icon-slider-nav-icon');
                var hoverOutColor = '';
                icons.each(function(){
                    if($(this).hasClass('active')){
                        $(this).css('color',iconActiveColor);
                    }else{
                        $(this).css('color',initialColor);
                    }
                });

                icons.hover(
                    function(){
                        hoverOutColor = $(this).css('color');
                        $(this).css('color',iconActiveColor);
                    },
                    function(){
                        if($(this).hasClass('active')){ // if the icon is hovered before it was active
                            hoverOutColor = iconActiveColor;
                        }   
                        $(this).css('color',hoverOutColor);
                    }
                );
            }
        }

        return {
            init: function(){
                if(slidesWrapper.length){
                    slidesWrapper.each(function(){
                        initIconSlider($(this));
                    });
                }
            }
        }
    }

    function edgtfInitContentSlider(){
        var slidesWrapper = $('.edgtf-content-slider-container');
        if(slidesWrapper.length){
            slidesWrapper.each(function(){
                var index = 1;

                /* after slider initialization add dots number */

                $(this).on('initialized.owl.carousel',function(event){
                    $(this).find('.owl-dot').each(function(){
                        $(this).text(index++);
                    });
                });

                $(this).owlCarousel({
                    items:1,
                    dots:true,
                    nav:false        
                });

            })
        }
    }

    function edgtfInitTeamSlider(){
        var slidesWrapper = $('.edgtf-team-slider-container');
        if(slidesWrapper.length){
            slidesWrapper.each(function(){
                $(this).owlCarousel({
                    loop:true,
                    items:1,
                    dots:true,
                    nav:true,
                    navText:['<i class="icon-arrows-left">','<i class="icon-arrows-right"></i>']        
                });
            })
        }
    }

    function edgtfInitZoomingSlider(){
        var sliders = $('.edgtf-zooming-slider');
        if(sliders.length){
            var flag = 0;
            var item = '';
            var indexOfLastItem = ''; // last item in a loop; before it moves to the first slide; next-to-last clone
            var numOfClones = ''; // last item in a loop; before it moves to the first slide; next-to-last clone
            var stage = '';
            var posX = '';

            sliders.on('initialized.owl.carousel',function(e){
                var items = $(this).find('div.owl-item');
                numOfClones = (items.length-e.item.count)/2;
                indexOfLastItem = e.item.count+numOfClones;
                item = items.eq(numOfClones).find('.edgtf-zooming-slider-slide');
                posX = -item.width()*numOfClones;
                stage = $('.owl-stage');
            });


            sliders.on('resized.owl.carousel',function(e){
                posX = -item.width()*numOfClones;
            });

            sliders.on('translate.owl.carousel',function(e){
                if(e.item.index == indexOfLastItem){
                    item.addClass('active');
                    flag = 1;
                }else{
                    item.removeClass('active');
                }
            });

            sliders.on('translated.owl.carousel',function(e){
                if(flag){
                    stage.css({
                        transform: "translate3d("+posX+", 0, 0)",
                        transition: "0s",
                        "transition-delay": '500ms', //css transition-duration of each slide (zooming animation)
                    });
                    flag = 0;
                }
            });

            sliders.owlCarousel({
                dots:true,
                nav:false,
                loop:true,
                autoplay:true,
                autoplayTimeout:5000,
                center:false,
                autoplayHoverPause:true,
                items:1,
                mouseDrag:false,
                touchDrag:false,
                pullDrag:false
            });
        }
    }

    if ($('.edgtf-floating-slider').length) {
        var edgtfFS = {};

        edgtfFS.$slider = $('.edgtf-floating-slider');
        edgtfFS.change_duration = edgtfFS.$slider.data('changeduration') / 1000; // in seconds
        edgtfFS.zoom_out = edgtfFS.$slider.data('zoomout');
        edgtfFS.hide_slides = edgtfFS.$slider.attr('data-nav-hide').length ? edgtfFS.$slider.attr('data-nav-hide').split(',') : [];
        for (var i=0; i<edgtfFS.hide_slides.length; i++) edgtfFS.hide_slides[i] = parseInt(edgtfFS.hide_slides[i],10);
        edgtfFS.$slides = edgtfFS.$slider.find('.edgtf-fs-slide');
        edgtfFS.$front = edgtfFS.$slider.find('.edgtf-fs-front-content-inner');
        edgtfFS.$background = edgtfFS.$slider.find('.edgtf-fs-background-elements');
        edgtfFS.$nav = edgtfFS.$slider.siblings('.edgtf-fs-nav');
        edgtfFS.$nav_buttons = edgtfFS.$nav.find('.nav_but');
        edgtfFS.$nav_arrows = edgtfFS.$nav.find('.nav_arr');
        edgtfFS.background = {
            dir: "",
            zigzag: "",
            randsize: "",
            floater: {
                width: 0,
                height: 0,
                src: "",
                speed: 0,
            },
        };
        edgtfFS.changing = false;

        edgtfFS.change_slide = function(dir) {
            var change = (dir=='next' ? 1 : -1);
            var curr = edgtfFS.$slides.filter('.current').index() + 1;
            edgtfFS.showSlideContent((curr-1 + change + edgtfFS.$slides.length)%edgtfFS.$slides.length + 1);
            edgtfFS.$nav_buttons.eq((curr-1 + change + edgtfFS.$slides.length)%edgtfFS.$slides.length).addClass('current').siblings().removeClass('current');
        };

        edgtfFS.init = function() {
            edgtfFS.$nav_buttons.click(function() {
                edgtfFS.showSlideContent(parseInt($(this).children('span').html(),10), true);
                $(this).addClass('current').siblings().removeClass('current');
            });
            edgtfFS.$nav_arrows.click(function() {
                var dir = $(this).is('.prev') ? 'prev' : 'next'; 
                edgtfFS.change_slide(dir);
            });
            $(window).on("resize orientationchange", function() {
                edgtfFS.$front.height($(window).height() - ($('.edgtf-mobile-header').is(':visible') ? $('.edgtf-mobile-header-inner').height() : 0));
            });
            edgtfFS.$slider.on("mousewheel", edgtfFS.handle_scroll);
            edgtfFS.$slider.on("DOMMouseScroll", edgtfFS.handle_scroll);
            $(window).on("keydown", edgtfFS.handle_keys);
            $(window).on("touchstart", edgtfFS.handle_touchstart);
            $(window).on("touchmove", edgtfFS.handle_touchmove);
            edgtfFS.$front.height($(window).height() - ($('.edgtf-mobile-header').is(':visible') ? $('.edgtf-mobile-header-inner').height() : 0));
            edgtfFS.dimensionFrontContent();
            var big_dim = Math.max(edgtfFS.$slider.outerHeight(), edgtfFS.$slider.outerWidth()) * 3;
            edgtfFS.$slider.find('.edgtf-fs-background-elements-inner').css({
                'width': big_dim + 'px',
                'height': big_dim + 'px',
                'margin-left': -big_dim/2 + 'px',
                'margin-top': -big_dim/2 + 'px',
            });
            edgtfFS.$nav_buttons.eq(0).click(); 
        };

        edgtfFS.floatBackgroundElements = function() {
            clearTimeout(edgtfFS.float_timeout);
            edgtfFS.$background.find('.edgtf-fs-background-elements-inner').css({
                '-webkit-transform':'',
                'transform':'',
                '-webkit-transition': 'none',
                'transition': 'none',
                'left': edgtfFS.$background.width() / 2 + 'px',
                'top': edgtfFS.$background.height() / 2 + 'px'
            }).find('.floater').remove();
            if (edgtfFS.background.floater) {
                var floater = {
                    width: edgtfFS.background.floater.width, //$floater.width(),
                    height: edgtfFS.background.floater.height, //$floater.height(),
                    src: edgtfFS.background.floater.src, //$floater.find('img').attr('src'),
                };
                var dir = edgtfFS.background.dir; //$('.edgtf-fs-background-elements').data('dir'); // Direction
                var zigzag = (edgtfFS.background.zigzag == "yes") ? -1 : 1; // Movement in opposite directions
                var randsize = (edgtfFS.background.randsize == "yes") ? 1 : 0; // Random scales of background element
                var final_offset = {
                    x: 0,
                    y: 0,
                }
                var big_dimension = edgtfFS.$background.find('.edgtf-fs-background-elements-inner').width();
                var max_offset = big_dimension / 2;
                var distance = {
                    x: 0,
                    y: 0,
                }
                var path_rotation = 0;
                var change_duration = edgtfFS.change_duration;
                
                switch (dir) {
                    case "top-bottom": case "bottom-top":
                    distance.x = 1 * floater.height;
                    distance.y = 1 * floater.width;
                    path_rotation = 90;
                    break;

                    case "left-right": case "right-left":
                    distance.x = 1 * floater.width;
                    distance.y = 1 * floater.height;
                    path_rotation = 0;
                    break;

                    case 'top-left-bottom-right':
                    case 'bottom-right-top-left':
                        max_offset *= 1.44;
                        path_rotation = -45;
                        distance.x = 1 * Math.pow(floater.width*floater.width + floater.height*floater.height, 0.5);
                        distance.y = 1 * Math.pow(floater.width*floater.width + floater.height*floater.height, 0.5);
                    break;

                    case 'top-right-bottom-left':
                    case 'bottom-left-top-right':
                        max_offset *= 1.44;
                        path_rotation = 45;
                        distance.x = 1 * Math.pow(floater.width*floater.width + floater.height*floater.height, 0.5);
                        distance.y = 1 * Math.pow(floater.width*floater.width + floater.height*floater.height, 0.5);
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
                        }
                        floaters_html +=
                            '<div class="floater" style="top: '+coords[j][i].y+'%; left: '+coords[j][i].x+'%; -webkit-transform: translate(-50%,-50%) scale('+(randsize ? (Math.random()*0.6+0.4) : 1)+'); transform: translate(-50%,-50%) scale('+(randsize ? (Math.random()*0.6+0.4) : 1)+'); ">' +
                                '<img src="'+floater.src+'">' +
                            '</div>'
                        ;
                    }
                    edgtfFS.$background.find('.edgtf-fs-background-elements-inner.fs-bei-'+(j+1)).html(floaters_html);
                }
                // Positioning the background elements - End

                if (dir == "top-bottom" || dir == "top-right-bottom-left" || dir == "top-left-bottom-right") final_offset.y = 1;
                else if (dir == "bottom-top" || dir == "bottom-left-top-right" || dir == "bottom-right-top-left") final_offset.y = -1;
                else final_offset.y = 0;
                if (dir == "left-right" || dir == "top-left-bottom-right" || dir == "bottom-left-top-right") final_offset.x = 1;
                else if (dir == "right-left" || dir == "top-right-bottom-left" || dir == "bottom-right-top-left") final_offset.x = -1;
                else final_offset.x = 0;
                var shift_x = big_dimension/3*final_offset.x, shift_y = big_dimension/3*final_offset.y;
                var transition_duration = Math.round(Math.pow(shift_x*shift_x + shift_y*shift_y, 0.5) / edgtfFS.background.floater.speed);
                
                if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
                    edgtfFS.$background.find('.edgtf-fs-background-elements-inner.fs-bei-1').addClass('running').find('.floater').animate({
                        'top': '+='+shift_y+'px',
                        'left': '+='+shift_x+'px'
                    }, transition_duration*1000, 'linear');
                    edgtfFS.$background.find('.edgtf-fs-background-elements-inner.fs-bei-2').addClass('running').find('.floater').animate({
                        'top': '+='+(shift_y*zigzag)+'px',
                        'left': '+='+(shift_x*zigzag)+'px'
                    }, transition_duration*1000, 'linear');
                }
                else {
                    edgtfFS.$background.find('.edgtf-fs-background-elements-inner').css({
                        '-webkit-transition': '-webkit-transform '+transition_duration+'s ease-out, opacity '+change_duration+'s ease, filter '+change_duration+'s ease', /* IE has problems with cubic-bezier(0, 0.15, 0, 0) easing on transform */
                        'transition': 'transform '+transition_duration+'s ease-out, opacity '+change_duration+'s ease, filter '+change_duration+'s ease', /* IE has problems with cubic-bezier(0, 0.15, 0, 0) easing on transform */
                    });
                    edgtfFS.$background.find('.edgtf-fs-background-elements-inner.fs-bei-1').addClass('running').css({
                        '-webkit-transform':'translate('+shift_x+'px, '+shift_y+'px)',
                        'transform':'translate('+shift_x+'px, '+shift_y+'px)',
                    });
                    edgtfFS.$background.find('.edgtf-fs-background-elements-inner.fs-bei-2').addClass('running').css({
                        '-webkit-transform': 'translate('+(shift_x*zigzag)+'px, '+(shift_y*zigzag)+'px)',
                        'transform': 'translate('+(shift_x*zigzag)+'px, '+(shift_y*zigzag)+'px)',
                    }).one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function() {
                        //if (!edgtfFS.changing) edgtfFS.floatBackgroundElements();
                    });
                }


                edgtfFS.float_timeout = setTimeout(function() {
                    if (!edgtfFS.changing) {
                        edgtfFS.unfloatBackgroundElements();
                        setTimeout(function() {
                            edgtfFS.floatBackgroundElements();
                        }, change_duration*1000);
                    }
                },(transition_duration)*1000);
            }
        };

        edgtfFS.unfloatBackgroundElements = function() {
            var change_duration = edgtfFS.change_duration;
            edgtfFS.$background.find('.edgtf-fs-background-elements-inner.running').removeClass('running').css({
                '-webkit-transform': 'translate(0,0)',
                'transform': 'translate(0,0)',
                '-webkit-transition': '-webkit-transform 0s linear '+change_duration+'s, opacity '+change_duration+'s ease, filter '+change_duration+'s ease',
                'transition': 'transform 0s linear '+change_duration+'s, opacity '+change_duration+'s ease, filter '+change_duration+'s ease',
            });
        };

        edgtfFS.dimensionFrontContent = function() {
            edgtfFS.$slides.each(function() {
                $(this).find('.fs-container').each(function() {
                    var container = $(this);
                    container.css({
                        'width': container.data("width") + '%',
                        'height': container.attr('data-height') ? (container.data("height") + '%') : '',
                        'max-height': container.attr('data-max-height') ? (container.data("max-height") + '%') : '',
                        'left': container.data("left") + '%',
                        '-webkit-transform': 'translate(-50%,100%) scale('+edgtfFS.zoom_out+')',
                        'transform': 'translate(-50%,100%) scale('+edgtfFS.zoom_out+')',
                    });
                });
            });
        };

        edgtfFS.showSlideContent = function(slide_no, jump, direction) {
            edgtfFS.changing = true;
            if (edgtfFS.$slides.eq(slide_no-1).is('.current')) return;
            
            edgtfFS.unfloatBackgroundElements();

            if (edgtfFS.hide_slides.indexOf(slide_no) < 0) {
                edgtfFS.$nav.fadeIn(edgtfFS.change_duration*1000);
                $('.edgtf-page-header').animate({opacity: 1}, edgtfFS.change_duration*1000);
            }
            else {
                edgtfFS.$nav.fadeOut(edgtfFS.change_duration*1000);
                $('.edgtf-page-header').animate({opacity: 0}, edgtfFS.change_duration*1000);
            }
            var nav_but = edgtfFS.$nav_buttons.eq(slide_no-1).addClass('current seen');
            nav_but.prevAll().addClass('seen');
            nav_but.nextAll().removeClass('seen');
            
            if (!jump) direction = (direction=="next") ? 1 : -1;
            for (var i=1; i<=edgtfFS.$slides.length; i++) {
                var this_slide = edgtfFS.$slides.eq(i-1);
                var target = i==slide_no;
                var current = this_slide.is('.current') && !target;
                var target = i==slide_no;
                /*
                var offsets = [
                    [50],
                    [45, 55],
                    [50, 45, 55],
                    [45, 50, 45, 55],
                ];
                */
                var no_of_containers = this_slide.find('.fs-container').length;
                if (target) {
                    edgtfFS.background = (this_slide.attr('data-floatersrc')) ? 
                    {
                        dir: this_slide.data('dir'),
                        zigzag: this_slide.data('zigzag'),
                        randsize: this_slide.data('randsize'),
                        floater: {
                            width: this_slide.data('floaterwidth'),
                            height: this_slide.data('floaterheight'),
                            src: this_slide.data('floatersrc'),
                            speed: this_slide.data('floaterspeed'),
                        },
                    } 
                    :
                    {};
                }
                
                this_slide
                .addClass( (target) ? 'current' : '' )
                .removeClass( (current) ? 'current' : '' )
                .find('.fs-container').each(function() {
                    var this_container = $(this);
                    this_container.css({
                        'opacity': (target || current) ? '1' : '0',
                        'filter': 'alpha(opacity=' + ((target || current) ? '100' : '0') + ')',
                        '-webkit-transform': 'translate(-50%, -'+ (50 + (!target) * ((i<slide_no)?50:-50)) + '%) scale('+((target)*(1-edgtfFS.zoom_out)+edgtfFS.zoom_out)+')', 
                        'transform': 'translate(-50%,-'+ (50 + (!target) * ((i<slide_no)?50:-50)) + '%) scale('+((target)*(1-edgtfFS.zoom_out)+edgtfFS.zoom_out)+')', 
                        '-webkit-transition-property': '-webkit-transform, top',
                        'transition-property': 'transform, top',
                        '-webkit-transition-duration': (current || target) ? edgtfFS.change_duration+'s' : '0s',
                        'transition-duration': (current || target) ? edgtfFS.change_duration+'s' : '0s',
                        '-webkit-transition-timing-function': 'ease',
                        'transition-timing-function': 'ease',
                        //'-webkit-transition-delay': ((target) ? edgtfFS.change_duration/2 : 0) + this_container.index() * edgtfFS.change_duration/2 / no_of_containers +'s',
                        //'transition-delay': ((target) ? edgtfFS.change_duration/2 : 0) + this_container.index() * edgtfFS.change_duration/2 / no_of_containers +'s',
                        //'-webkit-transition-delay': ((target) ? edgtfFS.change_duration/2 : 0) + ((!target) ? this_container.index() : no_of_containers - 1 - this_container.index()) * edgtfFS.change_duration/2 / no_of_containers +'s',
                        //'transition-delay': ((target) ? edgtfFS.change_duration/2 : 0) + ((!target) ? this_container.index() : no_of_containers - 1 - this_container.index()) * edgtfFS.change_duration/2 / no_of_containers +'s',
                        '-webkit-transition-delay': ((target) ? edgtfFS.change_duration/2 : 0) + ((!target) ? no_of_containers - 1 - this_container.index() : this_container.index()) * edgtfFS.change_duration/2 / no_of_containers +'s',
                        'transition-delay': ((target) ? edgtfFS.change_duration/2 : 0) + ((!target) ? no_of_containers - 1 - this_container.index() : this_container.index()) * edgtfFS.change_duration/2 / no_of_containers +'s',
                        'top': ((target) ? this_container.data("top") : 50) + (!target) * ((i<slide_no)?-55:55) + '%',
                    });
                });
            }
            setTimeout(function() {
                edgtfFS.floatBackgroundElements();
                edgtfFS.changing = false;
            }, edgtfFS.change_duration*1000*2);
        };

        edgtfFS.handle_scroll = function(e) {
            if (!edgtfFS.changing) {
                e.preventDefault();
                var e = window.event || e.originalEvent;
                var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
                edgtfFS.change_slide(delta == 1 ? 'prev' : 'next');
            }
        };

        edgtfFS.handle_keys = function(e) {
            if (!edgtfFS.changing) {
                if (e.keyCode == 38 || e.keyCode == 33) {
                    e.preventDefault();
                    edgtfFS.change_slide('prev');
                }
                else if (e.keyCode == 40 || e.keyCode == 34) {
                    e.preventDefault();
                    edgtfFS.change_slide('next');
                }
            }
        };

        edgtfFS.handle_touchstart = function(e) {
            if (!edgtfFS.changing) {
                edgtfFS.touch_start = e.originalEvent.touches[0].pageY;
            }
        };

        edgtfFS.handle_touchmove = function(e) {
            if (!edgtfFS.changing) {
                if ($(e.target).parents('.edgtf-floating-slider').length) {
                    console.log('Na njemu');
                    e.preventDefault();
                }
                if (!edgtfFS.touch_start) {
                    return;
                } else if (edgtfFS.touch_start > e.originalEvent.touches[0].pageY) {
                    edgtfFS.change_slide('next');
                } else if (edgtfFS.touch_start < e.originalEvent.touches[0].pageY) {
                    edgtfFS.change_slide('prev');
                }
            }
        };

        edgtfFS.init();

        shortcodes.edgtfFloatingSlider = edgtfFS; 
    }

	/**
	 * Slider object that initializes whole slider functionality
	 * @type {Function}
	 */
	var edgtfSlider = edgtf.modules.shortcodes.edgtfSlider = function() {

		//all sliders on the page
		var sliders = $('.edgtf-slider .carousel');
		//image regex used to extract img source
		var imageRegex = /url\(["']?([^'")]+)['"]?\)/;
		//default responsive breakpoints set
		var responsiveBreakpointSet = [1600,1200,900,650,500,320];
		//var init for coefficiens array
		var coefficientsGraphicArray;
		var coefficientsTitleArray;
		var coefficientsSubtitleArray;
		var coefficientsTextArray;
		var coefficientsButtonArray;
		//var init for slider elements responsive coefficients
		var sliderGraphicCoefficient;
		var sliderTitleCoefficient;
		var sliderSubtitleCoefficient;
		var sliderTextCoefficient;
		var sliderButtonCoefficient;
		var sliderTitleCoefficientLetterSpacing;
		var sliderSubtitleCoefficientLetterSpacing;
		var sliderTextCoefficientLetterSpacing;

		/*** Functionality for translating image in slide - START ***/

		var matrixArray = { zoom_center : '1.2, 0, 0, 1.2, 0, 0', zoom_top_left: '1.2, 0, 0, 1.2, -150, -150', zoom_top_right : '1.2, 0, 0, 1.2, 150, -150', zoom_bottom_left: '1.2, 0, 0, 1.2, -150, 150', zoom_bottom_right: '1.2, 0, 0, 1.2, 150, 150'};

		// regular expression for parsing out the matrix components from the matrix string
		var matrixRE = /\([0-9epx\.\, \t\-]+/gi;

		// parses a matrix string of the form "matrix(n1,n2,n3,n4,n5,n6)" and
		// returns an array with the matrix components
		var parseMatrix = function (val) {
			return val.match(matrixRE)[0].substr(1).
				split(",").map(function (s) {
					return parseFloat(s);
				});
		};

		// transform css property names with vendor prefixes;
		// the plugin will check for values in the order the names are listed here and return as soon as there
		// is a value; so listing the W3 std name for the transform results in that being used if its available
		var transformPropNames = [
			"transform",
			"-webkit-transform"
		];

		var getTransformMatrix = function (el) {
			// iterate through the css3 identifiers till we hit one that yields a value
			var matrix = null;
			transformPropNames.some(function (prop) {
				matrix = el.css(prop);
				return (matrix !== null && matrix !== "");
			});

			// if "none" then we supplant it with an identity matrix so that our parsing code below doesn't break
			matrix = (!matrix || matrix === "none") ?
				"matrix(1,0,0,1,0,0)" : matrix;
			return parseMatrix(matrix);
		};

		// set the given matrix transform on the element; note that we apply the css transforms in reverse order of how its given
		// in "transformPropName" to ensure that the std compliant prop name shows up last
		var setTransformMatrix = function (el, matrix) {
			var m = "matrix(" + matrix.join(",") + ")";
			for (var i = transformPropNames.length - 1; i >= 0; --i) {
				el.css(transformPropNames[i], m + ' rotate(0.01deg)');
			}
		};

		// interpolates a value between a range given a percent
		var interpolate = function (from, to, percent) {
			return from + ((to - from) * (percent / 100));
		};

		$.fn.transformAnimate = function (opt) {
			// extend the options passed in by caller
			var options = {
				transform: "matrix(1,0,0,1,0,0)"
			};
			$.extend(options, opt);

			// initialize our custom property on the element to track animation progress
			this.css("percentAnim", 0);

			// supplant "options.step" if it exists with our own routine
			var sourceTransform = getTransformMatrix(this);
			var targetTransform = parseMatrix(options.transform);
			options.step = function (percentAnim, fx) {
				// compute the interpolated transform matrix for the current animation progress
				var $this = $(this);
				var matrix = sourceTransform.map(function (c, i) {
					return interpolate(c, targetTransform[i],
						percentAnim);
				});

				// apply the new matrix
				setTransformMatrix($this, matrix);

				// invoke caller's version of "step" if one was supplied;
				if (opt.step) {
					opt.step.apply(this, [matrix, fx]);
				}
			};

			// animate!
			return this.stop().animate({ percentAnim: 100 }, options);
		};

		/*** Functionality for translating image in slide - END ***/


		/**
		 * Calculate heights for slider holder and slide item, depending on window width, but only if slider is set to be responsive
		 * @param slider, current slider
		 * @param defaultHeight, default height of slider, set in shortcode
		 * @param responsive_breakpoint_set, breakpoints set for slider responsiveness
		 * @param reset, boolean for reseting heights
		 */
		var setSliderHeight = function(slider, defaultHeight, responsive_breakpoint_set, reset) {
			var sliderHeight = defaultHeight;
			if(!reset) {
				if(edgtf.windowWidth > responsive_breakpoint_set[0]) {
					sliderHeight = defaultHeight;
				} else if(edgtf.windowWidth > responsive_breakpoint_set[1]) {
					sliderHeight = defaultHeight * 0.75;
				} else if(edgtf.windowWidth > responsive_breakpoint_set[2]) {
					sliderHeight = defaultHeight * 0.6;
				} else if(edgtf.windowWidth > responsive_breakpoint_set[3]) {
					sliderHeight = defaultHeight * 0.55;
				} else if(edgtf.windowWidth <= responsive_breakpoint_set[3]) {
					sliderHeight = defaultHeight * 0.45;
				}
			}

			slider.css({'height': (sliderHeight) + 'px'});
			slider.find('.edgtf-slider-preloader').css({'height': (sliderHeight) + 'px'});
			slider.find('.edgtf-slider-preloader .edgtf-ajax-loader').css({'display': 'block'});
			slider.find('.item').css({'height': (sliderHeight) + 'px'});
		}

		/**
		 * Calculate heights for slider holder and slide item, depending on window size, but only if slider is set to be full height
		 * @param slider, current slider
		 */
		var setSliderFullHeight = function(slider) {
			var mobileHeaderHeight = edgtf.windowWidth <= 1024 ? edgtfQuadricGlobalVars.vars.edgtfMobileHeaderHeight + $('.edgtf-top-bar').height() : 0;
			slider.css({'height': (edgtf.windowHeight - mobileHeaderHeight) + 'px'});
			slider.find('.edgtf-slider-preloader').css({'height': (edgtf.windowHeight - mobileHeaderHeight) + 'px'});
			slider.find('.edgtf-slider-preloader .edgtf-ajax-loader').css({'display': 'block'});
			slider.find('.item').css({'height': (edgtf.windowHeight - mobileHeaderHeight) + 'px'});
		}

		/**
		 * Set initial sizes for slider elements and put them in global variables
		 * @param slideItem, each slide
		 * @param index, index od slide item
		 */
		var setSizeGlobalVariablesForSlideElements = function(slideItem, index) {
			window["slider_graphic_width_" + index] = [];
			window["slider_graphic_height_" + index] = [];
			//window["slider_svg_width_" + index] = [];
			//window["slider_svg_height_" + index] = [];
			window["slider_title_" + index] = [];
			window["slider_subtitle_" + index] = [];
			window["slider_text_" + index] = [];
			window["slider_button1_" + index] = [];
			window["slider_button2_" + index] = [];

			//graphic size
			window["slider_graphic_width_" + index].push(parseFloat(slideItem.find('.edgtf-thumb img').data("width")));
			window["slider_graphic_height_" + index].push(parseFloat(slideItem.find('.edgtf-thumb img').data("height")));

			//svg size
			//window["slider_svg_width_" + index].push(parseFloat(slideItem.find('.edgtf-slider-svg svg').attr("width")));
			//window["slider_svg_height_" + index].push(parseFloat(slideItem.find('.edgtf-slider-svg svg').attr("height")));

			// font-size (0)
			window["slider_title_" + index].push(parseFloat(slideItem.find('.edgtf-slide-title').css("font-size")));
			window["slider_subtitle_" + index].push(parseFloat(slideItem.find('.edgtf-slide-subtitle').css("font-size")));
			window["slider_text_" + index].push(parseFloat(slideItem.find('.edgtf-slide-text').css("font-size")));
			window["slider_button1_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(0)').css("font-size")));
			window["slider_button2_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(1)').css("font-size")));

			// line-height (1)
			window["slider_title_" + index].push(parseFloat(slideItem.find('.edgtf-slide-title').css("line-height")));
			window["slider_subtitle_" + index].push(parseFloat(slideItem.find('.edgtf-slide-subtitle').css("line-height")));
			window["slider_text_" + index].push(parseFloat(slideItem.find('.edgtf-slide-text').css("line-height")));
			window["slider_button1_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(0)').css("line-height")));
			window["slider_button2_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(1)').css("line-height")));

			// letter-spacing (2)
			window["slider_title_" + index].push(parseFloat(slideItem.find('.edgtf-slide-title').css("letter-spacing")));
			window["slider_subtitle_" + index].push(parseFloat(slideItem.find('.edgtf-slide-subtitle').css("letter-spacing")));
			window["slider_text_" + index].push(parseFloat(slideItem.find('.edgtf-slide-text').css("letter-spacing")));
			window["slider_button1_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(0)').css("letter-spacing")));
			window["slider_button2_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(1)').css("letter-spacing")));

			// margin-bottom (3)
			window["slider_title_" + index].push(parseFloat(slideItem.find('.edgtf-slide-title').css("margin-bottom")));
			window["slider_subtitle_" + index].push(parseFloat(slideItem.find('.edgtf-slide-subtitle').css("margin-bottom")));


			// slider_button padding top/bottom(3), padding left/right(4)
			window["slider_button1_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(0)').css("padding-top")));
			window["slider_button2_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(1)').css("padding-top")));

			window["slider_button1_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(0)').css("padding-left")));
			window["slider_button2_" + index].push(parseFloat(slideItem.find('.edgtf-btn:eq(1)').css("padding-left")));

		}

		/**
		 * Set responsive coefficients for slider elements
		 * @param responsiveBreakpointSet, responsive breakpoints
		 * @param coefficientsGraphicArray, responsive coeaficcients for graphic
		 * @param coefficientsTitleArray, responsive coeaficcients for title
		 * @param coefficientsSubtitleArray, responsive coeaficcients for subtitle
		 * @param coefficientsTextArray, responsive coeaficcients for text
		 * @param coefficientsButtonArray, responsive coeaficcients for button
		 */
		var setSliderElementsResponsiveCoeffeicients = function(responsiveBreakpointSet,coefficientsGraphicArray,coefficientsTitleArray,coefficientsSubtitleArray,coefficientsTextArray,coefficientsButtonArray) {

			function coefficientsSetter(graphicArray,titleArray,subtitleArray,textArray,buttonArray){
				sliderGraphicCoefficient = graphicArray;
				sliderTitleCoefficient = titleArray;
				sliderSubtitleCoefficient = subtitleArray;
				sliderTextCoefficient = textArray;
				sliderButtonCoefficient = buttonArray;
			}

			if(edgtf.windowWidth > responsiveBreakpointSet[0]) {
				coefficientsSetter(coefficientsGraphicArray[0],coefficientsTitleArray[0],coefficientsSubtitleArray[0],coefficientsTextArray[0],coefficientsButtonArray[0]);
			}else if(edgtf.windowWidth > responsiveBreakpointSet[1]){
				coefficientsSetter(coefficientsGraphicArray[1],coefficientsTitleArray[1],coefficientsSubtitleArray[1],coefficientsTextArray[1],coefficientsButtonArray[1]);
			}else if(edgtf.windowWidth > responsiveBreakpointSet[2]){
				coefficientsSetter(coefficientsGraphicArray[2],coefficientsTitleArray[2],coefficientsSubtitleArray[2],coefficientsTextArray[2],coefficientsButtonArray[2]);
			}else if(edgtf.windowWidth > responsiveBreakpointSet[3]){
				coefficientsSetter(coefficientsGraphicArray[3],coefficientsTitleArray[3],coefficientsSubtitleArray[3],coefficientsTextArray[3],coefficientsButtonArray[3]);
			}else if (edgtf.windowWidth > responsiveBreakpointSet[4]) {
				coefficientsSetter(coefficientsGraphicArray[4],coefficientsTitleArray[4],coefficientsSubtitleArray[4],coefficientsTextArray[4],coefficientsButtonArray[4]);
			}else if (edgtf.windowWidth > responsiveBreakpointSet[5]){
				coefficientsSetter(coefficientsGraphicArray[5],coefficientsTitleArray[5],coefficientsSubtitleArray[5],coefficientsTextArray[5],coefficientsButtonArray[5]);
			}else{
				coefficientsSetter(coefficientsGraphicArray[6],coefficientsTitleArray[6],coefficientsSubtitleArray[6],coefficientsTextArray[6],coefficientsButtonArray[6]);
			}

			// letter-spacing decrease quicker
			sliderTitleCoefficientLetterSpacing = sliderTitleCoefficient;
			sliderSubtitleCoefficientLetterSpacing = sliderSubtitleCoefficient;
			sliderTextCoefficientLetterSpacing = sliderTextCoefficient;
			if(edgtf.windowWidth <= responsiveBreakpointSet[0]) {
				sliderTitleCoefficientLetterSpacing = sliderTitleCoefficient/2;
				sliderSubtitleCoefficientLetterSpacing = sliderSubtitleCoefficient/2;
				sliderTextCoefficientLetterSpacing = sliderTextCoefficient/2;
			}
		}

		/**
		 * Set sizes for slider elements
		 * @param slideItem, each slide
		 * @param index, index od slide item
		 * @param reset, boolean for reseting sizes
		 */
		var setSliderElementsSize = function(slideItem, index, reset) {

			if(reset) {
				sliderGraphicCoefficient = sliderTitleCoefficient = sliderSubtitleCoefficient = sliderTextCoefficient = sliderButtonCoefficient = sliderTitleCoefficientLetterSpacing = sliderSubtitleCoefficientLetterSpacing = sliderTextCoefficientLetterSpacing = 1;
			}

			slideItem.find('.edgtf-thumb').css({
				"width": Math.round(window["slider_graphic_width_" + index][0]*sliderGraphicCoefficient) + 'px',
				"height": Math.round(window["slider_graphic_height_" + index][0]*sliderGraphicCoefficient) + 'px'
			});

			//slideItem.find('.edgtf-slider-svg svg').css({
			//	"width": Math.round(window["slider_svg_width_" + index][0]*sliderGraphicCoefficient) + 'px',
			//	"height": Math.round(window["slider_svg_height_" + index][0]*sliderGraphicCoefficient) + 'px'
			//});

			slideItem.find('.edgtf-slide-title').css({
				"font-size": Math.round(window["slider_title_" + index][0]*sliderTitleCoefficient) + 'px',
				"line-height": Math.round(window["slider_title_" + index][1]*sliderTitleCoefficient) + 'px',
				"letter-spacing": Math.round(window["slider_title_" + index][2]*sliderTitleCoefficient) + 'px',
				"margin-bottom": Math.round(window["slider_title_" + index][3]*sliderTitleCoefficient) + 'px'
			});

			slideItem.find('.edgtf-slide-subtitle').css({
				"font-size": Math.round(window["slider_subtitle_" + index][0]*sliderSubtitleCoefficient) + 'px',
				"line-height": Math.round(window["slider_subtitle_" + index][1]*sliderSubtitleCoefficient) + 'px',
				"margin-bottom": Math.round(window["slider_subtitle_" + index][3]*sliderSubtitleCoefficient) + 'px',
				"letter-spacing": Math.round(window["slider_subtitle_" + index][2]*sliderSubtitleCoefficientLetterSpacing) + 'px'
			});

			slideItem.find('.edgtf-slide-text').css({
				"font-size": Math.round(window["slider_text_" + index][0]*sliderTextCoefficient) + 'px',
				"line-height": Math.round(window["slider_text_" + index][1]*sliderTextCoefficient) + 'px',
				"letter-spacing": Math.round(window["slider_text_" + index][2]*sliderTextCoefficientLetterSpacing) + 'px'
			});

			slideItem.find('.edgtf-btn:eq(0)').css({
				"font-size": Math.round(window["slider_button1_" + index][0]*sliderButtonCoefficient) + 'px',
				"line-height": Math.round(window["slider_button1_" + index][1]*sliderButtonCoefficient) + 'px',
				"letter-spacing": Math.round(window["slider_button1_" + index][2]*sliderButtonCoefficient) + 'px',
				"padding-top": Math.round(window["slider_button1_" + index][3]*sliderButtonCoefficient) + 'px',
				"padding-bottom": Math.round(window["slider_button1_" + index][3]*sliderButtonCoefficient) + 'px',
				"padding-left": Math.round(window["slider_button1_" + index][4]*sliderButtonCoefficient) + 'px',
				//"padding-right": Math.round(window["slider_button1_" + index][4]*sliderButtonCoefficient) + 'px'
			});
			slideItem.find('.edgtf-btn:eq(0) .edgtf-btn-text').css({
				"padding-right": Math.round(window["slider_button1_" + index][4]*sliderButtonCoefficient) + 'px'
			});
			slideItem.find('.edgtf-btn:eq(1)').css({
				"font-size": Math.round(window["slider_button2_" + index][0]*sliderButtonCoefficient) + 'px',
				"line-height": Math.round(window["slider_button2_" + index][1]*sliderButtonCoefficient) + 'px',
				"letter-spacing": Math.round(window["slider_button2_" + index][2]*sliderButtonCoefficient) + 'px',
				"padding-top": Math.round(window["slider_button2_" + index][3]*sliderButtonCoefficient) + 'px',
				"padding-bottom": Math.round(window["slider_button2_" + index][3]*sliderButtonCoefficient) + 'px',
				"padding-left": Math.round(window["slider_button2_" + index][4]*sliderButtonCoefficient) + 'px',
				//"padding-right": Math.round(window["slider_button2_" + index][4]*sliderButtonCoefficient) + 'px'
			});
			slideItem.find('.edgtf-btn:eq(1) .edgtf-btn-text').css({
				"padding-right": Math.round(window["slider_button2_" + index][4]*sliderButtonCoefficient) + 'px'
			});


		}

		/**
		 * Set heights for slider and elemnts depending on slider settings (full height, responsive height od set height)
		 * @param slider, current slider
		 */
		var setHeights =  function(slider) {

			slider.find('.item').each(function (i) {
				setSizeGlobalVariablesForSlideElements($(this),i);
				setSliderElementsSize($(this), i, false);
			});

			if(slider.hasClass('edgtf-full-screen')){

				setSliderFullHeight(slider);

				$(window).resize(function() {
					setSliderElementsResponsiveCoeffeicients(responsiveBreakpointSet,coefficientsGraphicArray,coefficientsTitleArray,coefficientsSubtitleArray,coefficientsTextArray,coefficientsButtonArray);
					setSliderFullHeight(slider);
					slider.find('.item').each(function(i){
						setSliderElementsSize($(this), i, false);
					});
				});

			}else if(slider.hasClass('edgtf-responsive-height')){

				var defaultHeight = slider.data('height');
				setSliderHeight(slider, defaultHeight, responsiveBreakpointSet, false);

				$(window).resize(function() {
					setSliderElementsResponsiveCoeffeicients(responsiveBreakpointSet,coefficientsGraphicArray,coefficientsTitleArray,coefficientsSubtitleArray,coefficientsTextArray,coefficientsButtonArray);
					setSliderHeight(slider, defaultHeight, responsiveBreakpointSet, false);
					slider.find('.item').each(function(i){
						setSliderElementsSize($(this), i, false);
					});
				});

			}else {
				var defaultHeight = slider.data('height');

				slider.find('.edgtf-slider-preloader').css({'height': (slider.height()) + 'px'});
				slider.find('.edgtf-slider-preloader .edgtf-ajax-loader').css({'display': 'block'});

				edgtf.windowWidth < 1000 ? setSliderHeight(slider, defaultHeight, responsiveBreakpointSet, false) : setSliderHeight(slider, defaultHeight, responsiveBreakpointSet, true);

				$(window).resize(function() {
					setSliderElementsResponsiveCoeffeicients(responsiveBreakpointSet,coefficientsGraphicArray,coefficientsTitleArray,coefficientsSubtitleArray,coefficientsTextArray,coefficientsButtonArray);
					if(edgtf.windowWidth < 1000){
						setSliderHeight(slider, defaultHeight, responsiveBreakpointSet, false);
						slider.find('.item').each(function(i){
							setSliderElementsSize($(this),i,false);
						});
					}else{
						setSliderHeight(slider, defaultHeight, responsiveBreakpointSet, true);
						slider.find('.item').each(function(i){
							setSliderElementsSize($(this),i,true);
						});
					}
				});
			}
		}

		/**
		 * Set prev/next numbers on navigation arrows
		 * @param slider, current slider
		 * @param currentItem, current slide item index
		 * @param totalItemCount, total number of slide items
		 */
		var setPrevNextNumbers = function(slider, currentItem, totalItemCount) {
			if(currentItem == 1){
				slider.find('.left.carousel-control .prev').html(totalItemCount);
				slider.find('.right.carousel-control .next').html(currentItem + 1);
			}else if(currentItem == totalItemCount){
				slider.find('.left.carousel-control .prev').html(currentItem - 1);
				slider.find('.right.carousel-control .next').html(1);
			}else{
				slider.find('.left.carousel-control .prev').html(currentItem - 1);
				slider.find('.right.carousel-control .next').html(currentItem + 1);
			}
		}

		/**
		 * Set video background size
		 * @param slider, current slider
		 */
		var initVideoBackgroundSize = function(slider){
			var min_w = 1500; // minimum video width allowed
			var video_width_original = 1920;  // original video dimensions
			var video_height_original = 1080;
			var vid_ratio = 1920/1080;

			slider.find('.item .edgtf-video .edgtf-video-wrap').each(function(){

				var slideWidth = edgtf.windowWidth;
				var slideHeight = $(this).closest('.carousel').height();

				$(this).width(slideWidth);

				min_w = vid_ratio * (slideHeight+20);
				$(this).height(slideHeight);

				var scale_h = slideWidth / video_width_original;
				var scale_v = (slideHeight - edgtfQuadricGlobalVars.vars.edgtfMenuAreaHeight) / video_height_original;
				var scale =  scale_v;
				if (scale_h > scale_v)
					scale =  scale_h;
				if (scale * video_width_original < min_w) {scale = min_w / video_width_original;}

				$(this).find('video, .mejs-overlay, .mejs-poster').width(Math.ceil(scale * video_width_original +2));
				$(this).find('video, .mejs-overlay, .mejs-poster').height(Math.ceil(scale * video_height_original +2));
				$(this).scrollLeft(($(this).find('video').width() - slideWidth) / 2);
				$(this).find('.mejs-overlay, .mejs-poster').scrollTop(($(this).find('video').height() - slideHeight) / 2);
				$(this).scrollTop(($(this).find('video').height() - slideHeight) / 2);
			});
		}

		/**
		 * Init video background
		 * @param slider, current slider
		 */
		var initVideoBackground = function(slider) {
			$('.item .edgtf-video-wrap .video').mediaelementplayer({
				enableKeyboard: false,
				iPadUseNativeControls: false,
				pauseOtherPlayers: false,
				// force iPhone's native controls
				iPhoneUseNativeControls: false,
				// force Android's native controls
				AndroidUseNativeControls: false
			});

			$(window).resize(function() {
				initVideoBackgroundSize(slider);
			});

			//mobile check
			if(navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/)){
				$('.edgtf-slider .edgtf-mobile-video-image').show();
				$('.edgtf-slider .edgtf-video-wrap').remove();
			}
		}

		/**
		 * Svg drawing
		 * @param slide, current slide
		 */

		//var initSvgDraw = function(slide) {
        //
		//	if(slide.find('.edgtf-slider-svg').length){
		//			var svg = slide.find('.edgtf-slider-svg');
		//			edgtf.modules.common.edgtfCheckSVG(svg);
		//			svg.css('opacity',1);
		//	}
        //
		//}
		/**
		 * initiate slider
		 * @param slider, current slider
		 * @param currentItem, current slide item index
		 * @param totalItemCount, total number of slide items
		 * @param slideAnimationTimeout, timeout for slide change
		 */
		var initiateSlider = function(slider, totalItemCount, slideAnimationTimeout) {

			//set active class on first item
			slider.find('.carousel-inner .item:first-child').addClass('active');
			//check for header style
			edgtfCheckSliderForHeaderStyle($('.carousel .active'), slider.hasClass('edgtf-header-effect'));
			// setting numbers on carousel controls
			if(slider.hasClass('edgtf-slider-numbers')) {
				setPrevNextNumbers(slider, 1, totalItemCount);
			}
			// set video background if there is video slide
			if(slider.find('.item video').length){
				initVideoBackgroundSize(slider);
				initVideoBackground(slider);
			}

			//draw svg
			//initSvgDraw(slider.find('.item.active'));

			//init slider
			if(slider.hasClass('edgtf-auto-start')){
				slider.carousel({
					interval: slideAnimationTimeout,
					pause: false
				});

				//pause slider when hover slider button
				slider.find('.slide_buttons_holder .qbutton')
					.mouseenter(function() {
						slider.carousel('pause');
					})
					.mouseleave(function() {
						slider.carousel('cycle');
					});
			} else {
				slider.carousel({
					interval: 0,
					pause: false
				});
			}


			//initiate image animation
			if($('.carousel-inner .item:first-child').hasClass('edgtf-animate-image') && edgtf.windowWidth > 1000){
				slider.find('.carousel-inner .item.edgtf-animate-image:first-child .edgtf-image').transformAnimate({
					transform: "matrix("+matrixArray[$('.carousel-inner .item:first-child').data('edgtf_animate_image')]+")",
					duration: 30000,
                    complete: function () {
                        $(this).transformAnimate({
                            transform: "matrix(1, 0, 0, 1, 0, 0)",
                            duration: 30000
                        });
                    }
				});
			}

		}

		return {
			init: function() {
				if(sliders.length) {
					sliders.each(function() {
						var $this = $(this);
						var slideAnimationTimeout = $this.data('slide_animation_timeout');
						var totalItemCount = $this.find('.item').length;
						if($this.data('edgtf_responsive_breakpoints')){
							if($this.data('edgtf_responsive_breakpoints') == 'set2'){
								responsiveBreakpointSet = [1600,1300,1000,768,567,320];
							}
						}
						coefficientsGraphicArray = $this.data('edgtf_responsive_graphic_coefficients').split(',');
						coefficientsTitleArray = $this.data('edgtf_responsive_title_coefficients').split(',');
						coefficientsSubtitleArray = $this.data('edgtf_responsive_subtitle_coefficients').split(',');
						coefficientsTextArray = $this.data('edgtf_responsive_text_coefficients').split(',');
						coefficientsButtonArray = $this.data('edgtf_responsive_button_coefficients').split(',');

						setSliderElementsResponsiveCoeffeicients(responsiveBreakpointSet,coefficientsGraphicArray,coefficientsTitleArray,coefficientsSubtitleArray,coefficientsTextArray,coefficientsButtonArray);

						setHeights($this);

						/*** wait until first video or image is loaded and than initiate slider - start ***/
						if(edgtf.htmlEl.hasClass('touch')){
							if($this.find('.item:first-child .edgtf-mobile-video-image').length > 0){
								var src = imageRegex.exec($this.find('.item:first-child .edgtf-mobile-video-image').attr('style'));
							}else{
								var src = imageRegex.exec($this.find('.item:first-child .edgtf-image').attr('style'));
							}
							if(src) {
								var backImg = new Image();
								backImg.src = src[1];
								$(backImg).load(function(){
									$('.edgtf-slider-preloader').fadeOut(500);
									initiateSlider($this,totalItemCount,slideAnimationTimeout);
								});
							}
						} else {
							if($this.find('.item:first-child video').length > 0){
								$this.find('.item:first-child video').get(0).addEventListener('loadeddata',function(){
									$('.edgtf-slider-preloader').fadeOut(500);
									initiateSlider($this,totalItemCount,slideAnimationTimeout);
								});
							}else{
								var src = imageRegex.exec($this.find('.item:first-child .edgtf-image').attr('style'));
								if (src) {
									var backImg = new Image();
									backImg.src = src[1];
									$(backImg).load(function(){
										$('.edgtf-slider-preloader').fadeOut(500);
										initiateSlider($this,totalItemCount,slideAnimationTimeout);
									});
								}
							}
						}
						/*** wait until first video or image is loaded and than initiate slider - end ***/

						$this.on('slide.bs.carousel', function () {
							$this.addClass('edgtf-in-progress');
							$this.find('.active .edgtf-slider-content-outer').fadeTo(250,0);
						});
						$this.on('slid.bs.carousel', function () {
							$this.removeClass('edgtf-in-progress');
							$this.find('.active .edgtf-slider-content-outer').fadeTo(0,1);

							//initSvgDraw($this.find('.item.active'));

							// setting numbers on carousel controls
							if($this.hasClass('edgtf-slider-numbers')) {
								var currentItem = $('.item').index($('.item.active')[0]) + 1;
								setPrevNextNumbers($this, currentItem, totalItemCount);
							}

							// initiate image animation on active slide and reset all others
							$('.item.edgtf-animate-image .edgtf-image').stop().css({'transform':'', '-webkit-transform':''});
							if($('.item.active').hasClass('edgtf-animate-image') && edgtf.windowWidth > 1000){
								$('.item.edgtf-animate-image.active .edgtf-image').transformAnimate({
									transform: "matrix("+matrixArray[$('.item.edgtf-animate-image.active').data('edgtf_animate_image')]+")",
									duration: 30000,
                                    complete: function () {
                                        $(this).transformAnimate({
                                            transform: "matrix(1, 0, 0, 1, 0, 0)",
                                            duration: 30000
                                        });
                                    }
								});
							}

						});

						/* after slide transition - end */

						/* swipe functionality - start */
						$this.swipe( {
							swipeLeft: function(){ $this.carousel('next'); },
							swipeRight: function(){ $this.carousel('prev'); },
							threshold:20
						});
						/* swipe functionality - end */

					});

                    //adding parallax functionality on slider
                    if($('.no-touch .carousel').length){
                        var skrollr_slider = skrollr.init({
                            smoothScrolling: false,
                            forceHeight: false
                        });
                        skrollr_slider.refresh();
                    }

                    $(window).scroll(function(){
                        //set control class for slider in order to change header style
                        if($('.edgtf-slider .carousel').height() < edgtf.scroll){
                            $('.edgtf-slider .carousel').addClass('edgtf-disable-slider-header-style-changing');
                        }else{
                            $('.edgtf-slider .carousel').removeClass('edgtf-disable-slider-header-style-changing');
                            edgtfCheckSliderForHeaderStyle($('.edgtf-slider .carousel .active'),$('.edgtf-slider .carousel').hasClass('edgtf-header-effect'));
                        }

                        //hide slider when it is out of viewport
                        if($('.edgtf-slider .carousel').hasClass('edgtf-full-screen') && edgtf.scroll > edgtf.windowHeight && edgtf.windowWidth > 1000){
                            $('.edgtf-slider .carousel').find('.carousel-inner, .carousel-indicators').hide();
                        }else if(!$('.edgtf-slider .carousel').hasClass('edgtf-full-screen') && edgtf.scroll > $('.edgtf-slider .carousel').height() && edgtf.windowWidth > 1000){
                            $('.edgtf-slider .carousel').find('.carousel-inner, .carousel-indicators').hide();
                        }else{
                            $('.edgtf-slider .carousel').find('.carousel-inner, .carousel-indicators').show();
                        }
                    });

				}

			}
		};
	};

	/**
	 * Check if slide effect on header style changing
	 * @param slide, current slide
	 * @param headerEffect, flag if slide
	 */

	function edgtfCheckSliderForHeaderStyle(slide, headerEffect) {

		if($('.edgtf-slider .carousel').not('.edgtf-disable-slider-header-style-changing').length > 0) {

			var slideHeaderStyle = "";
			if (slide.hasClass('light')) { slideHeaderStyle = 'edgtf-light-header'; }
			if (slide.hasClass('dark')) { slideHeaderStyle = 'edgtf-dark-header'; }

			if (slideHeaderStyle !== "") {
				if (headerEffect) {
					edgtf.body.removeClass('edgtf-dark-header edgtf-light-header').addClass(slideHeaderStyle);
				}
			} else {
				if (headerEffect) {
					edgtf.body.removeClass('edgtf-dark-header edgtf-light-header').addClass(edgtf.defaultHeaderStyle);
				}

			}
		}
	}

})(jQuery);