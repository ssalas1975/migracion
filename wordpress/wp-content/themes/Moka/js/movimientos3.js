$(document).ready(function() {
	setTimeout(muestra1, 500);
});


function muestra1() {
		var elem = $("#center1");
		elem.animate({opacity: 1}, 1000 );
	}



$(document).scroll(function () {

	var y = $(this).scrollTop();

	

	var t4 = $('#center4').offset().top;
	if (y>(t4*0.3)) {
		$('#center4').animate({ opacity: 1 }, 1000);
	} 

	var t5 = $('#center5').offset().top;
	if (y>(t5*0.4)) {
		$('#center5').animate({ opacity: 1 }, 1000);
	} 
	var t6 = $('#center6').offset().top;
	if (y>(t6*0.5)) {
		$('#center6').animate({ opacity: 1 }, 1000);
	} 

	
})


