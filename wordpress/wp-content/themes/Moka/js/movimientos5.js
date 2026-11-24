
$(document).ready(function () {

	$(document).scroll(function () {
	var y = $(this).scrollTop();
	

	var t2 = $('.about-parte1').offset().top;
	var t3 = $('.about-parte2').offset().top;
	var t4 = $('.about-parte3').offset().top;
	var t5 = $('.about-parte4').offset().top;

	//console.log("proy1-"+t2);
	//console.log("proy2-"+t3);
	//console.log("proy3-"+t4);
	//console.log("proy4-"+t5);

 	if (($(document).scrollTop()) > (t2/3)) {
            $('.about-parte1').animate({ opacity: 1, marginTop:"0px"}, 1000);
        } 
    if (($(document).scrollTop()) > (t3/3)) {
            $('.about-parte2').animate({ opacity: 1, marginTop:"0px"}, 1000);
        } 
    if (($(document).scrollTop()) > (t4/3)) {
            $('.about-parte3').animate({ opacity: 1, marginTop:"0px"}, 1000);
        } 
    if (($(document).scrollTop()) > (t5/3)) {
            $('.about-parte4').animate({ opacity: 1, marginTop:"0px"}, 1000);
        } 

    
    });

})


