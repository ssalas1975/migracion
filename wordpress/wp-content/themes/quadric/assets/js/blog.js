(function($) {
    "use strict";


    var blog = {};
    edgtf.modules.blog = blog;

    blog.edgtfInitAudioPlayer = edgtfInitAudioPlayer;

    $(document).ready(function() {
        edgtfInitAudioPlayer();
		edgtfInitPrint();
        edgtfInitBlogMasonry();
        edgtfInitBlogMasonryLoadMore();
    });

    function edgtfInitAudioPlayer() {

        var players = $('audio.edgtf-blog-audio');

        players.mediaelementplayer({
            audioWidth: '100%'
        });
    }

	function edgtfInitPrint() {

		var element = $('.edgtf-print-post');

		element.on( 'click', function(e) {
			e.preventDefault();
			window.print();
		});
    }


    function edgtfInitBlogMasonry() {

        if($('.edgtf-blog-holder.edgtf-blog-type-masonry').length) {

            var container = $('.edgtf-blog-holder.edgtf-blog-type-masonry');


				container.isotope({
					itemSelector: 'article',
					resizable: false,
					masonry: {
						columnWidth: '.edgtf-blog-masonry-grid-sizer',
						gutter: '.edgtf-blog-masonry-grid-gutter'
					}
				});

            var filters = $('.edgtf-filter-blog-holder');
            $('.edgtf-filter').click(function() {
                var filter = $(this);
                var selector = filter.attr('data-filter');
                filters.find('.edgtf-active').removeClass('edgtf-active');
                filter.addClass('edgtf-active');
                container.isotope({filter: selector});
                return false;
            });

			container.waitForImages(function(){
				container.animate({opacity: "1"}, 300, function() {
					container.isotope('layout');
				});
			});
        }
    }

    function edgtfInitBlogMasonryLoadMore() {

        if($('.edgtf-blog-holder.edgtf-blog-type-masonry').length) {

            var container = $('.edgtf-blog-holder.edgtf-blog-type-masonry');

            if(container.hasClass('edgtf-masonry-pagination-infinite-scroll')) {
                container.infinitescroll({
                        navSelector: '.edgtf-blog-infinite-scroll-button',
                        nextSelector: '.edgtf-blog-infinite-scroll-button a',
                        itemSelector: 'article',
                        loading: {
                            finishedMsg: edgtfQuadricGlobalVars.vars.edgtfFinishedMessage,
                            msgText: edgtfQuadricGlobalVars.vars.edgtfMessage
                        }
                    },
                    function(newElements) {
                        container.append(newElements).isotope('appended', $(newElements));
                        edgtf.modules.blog.edgtfInitAudioPlayer();
                        edgtf.modules.common.edgtfOwlSlider();
                        edgtf.modules.common.edgtfFluidVideo();
                        setTimeout(function() {
                            container.isotope('layout');
                        }, 400);
                    }
                );
            } else if(container.hasClass('edgtf-masonry-pagination-load-more')) {
                var i = 1;
                $('.edgtf-blog-load-more-button a').on('click', function(e) {
                    e.preventDefault();

                    var button = $(this);

                    var link = button.attr('href');
                    var content = '.edgtf-masonry-pagination-load-more';
                    var anchor = '.edgtf-blog-load-more-button a';
                    var nextHref = $(anchor).attr('href');
                    $.get(link + '', function(data) {
                        var newContent = $(content, data).wrapInner('').html();
                        nextHref = $(anchor, data).attr('href');
                        container.append(newContent).isotope('reloadItems').isotope({sortBy: 'original-order'});
                        edgtf.modules.blog.edgtfInitAudioPlayer();
                        edgtf.modules.common.edgtfOwlSlider();
                        edgtf.modules.common.edgtfFluidVideo();
                        setTimeout(function() {
                            $('.edgtf-masonry-pagination-load-more').isotope('layout');
                        }, 400);
                        if(button.parent().data('rel') > i) {
                            button.attr('href', nextHref); // Change the next URL
                        } else {
                            button.parent().remove();
                        }
                    });
                    i++;
                });
            }
        }
    }




})(jQuery);