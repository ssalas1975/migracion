
$(document).ready(function () {

	var bottom_of_window = $(window).scrollTop();
	$("div#proy").each(function(i,obj) {
		console.log($(obj).offset().top);
		console.log($(document).scrollTop());
        
        if (($(document).scrollTop()*3)+(400-(i*200)) > $(obj).offset().top ) {
            $(obj).animate({ opacity: 1, marginTop:"0px" }, 1000);
        } 
    });	

$(window).scroll(function () {
	var y = $(this).scrollTop();

	

	

 	var bottom_of_window = $(window).scrollTop();
	$("div#proy").each(function(i,obj) {
		//console.log($(obj).offset().top);
		//console.log($(document).scrollTop());
        
        if (($(document).scrollTop()*3)+(400-(i*200)) > $(obj).offset().top ) {
            $(obj).animate({ opacity: 1, marginTop:"0px"}, 1000);
        } 
    });	

    
}); })


