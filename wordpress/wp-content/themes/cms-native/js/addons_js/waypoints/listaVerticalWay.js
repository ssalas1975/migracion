$(document).ready(function(){
    $('img.content_anim1 ').waypoint(function () {
        $(this).addClass('animated fadeIn active');
        }, { offset: '50%' });
});
