$(document).ready(function() {
	setTimeout(muevecirculo1, 500);
	setTimeout(muevecirculo2, 700);
	setTimeout(muevecirculo3, 400);
	setTimeout(muevetexto1, 2500);
});
		

	function muevecirculo1() {
		var y = $(this).scrollTop();
		var principito = $('#principito');
		principito.css({
            "margin-top":  (y*0.8) + "px", "top": -((y*1.05)) + "px"
        });

		var elem = $("#circulo1");
		elem.animate({marginLeft:"+=1000px"}, 1500 );
	}
	function muevecirculo2() {
		var elem = $("#circulo2");
		elem.animate({marginLeft:"-=3500px"}, 1200 );
	}
	function muevecirculo3() {
		var elem = $("#circulo3");
		elem.animate({marginLeft:"+=1500px"}, 2000 );
	}
	function muevetexto1() {
		var elem = $("#texto1");
		elem.animate({opacity: 1, marginLeft:"+=300px"}, 1500 );
		$('#principito').animate({ opacity: 1 }, 1500);
	}




$(window).scroll(function () {

	var y = $(this).scrollTop();

	var circulo1 = $('#circulo1');
	circulo1.css({
            "margin-top":  (y*1.4) + "px", "top": -((y*1.05)-210) + "px"
        });
	var circulo3 = $('#circulo3');
	circulo3.css({
            "margin-top":  (y*0.4) + "px", "top": -((y*1.05)-650) + "px"
        });


	if (y>50) {
		$('#bloque-texto').animate({ opacity: 1 }, 1500);
	} 
	var principito = $('#principito');
	var texto1 = $('#texto1');
	principito.css({
            "margin-top":  -(y*0.5) + "px", "position": "absolute"
            });

	texto1.css({
            "margin-top":  -(y*1.0) + "px", "position": "absolute"
        });

	var bloque = $('#bloque-texto');
	bloque.css({
            "margin-top":  -(y*1.2) + "px", "position": "absolute"
        });
	
})


