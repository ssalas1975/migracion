$(document).ready(function(){
    $('.content_anim4').waypoint(function () {

        setTimeout(function () { $('.content_anim4').addClass('animated fadeIn active') }, 0);
    }, { offset: '50%' });
});
