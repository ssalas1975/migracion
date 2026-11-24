	$(document).ready(function() {
		colocar();
		//AnimateRotate(-270);
		AnimateRotate2(-270);
		setTimeout(muevecirculo1, 1000);
		setTimeout(muevecirculo2, 1200);
		//setTimeout(muestrahola, 2500);
		setTimeout(muestratitulo1, 2750);
		setTimeout(muestratitulo2, 2950);
		setTimeout(muestratitulo3, 3150);
		setTimeout(cambiohola1, 3000);
		//setTimeout(cambiohola2, 6300);
		//setTimeout(cambiohola1, 9300);
	});
	function colocar() {
		var elem = $("#circulo2");
		var elem2 = $("#circulo1");
		var elem3 = $("#circulo3");
		var elem4 = $("#circulo4");
		var elem5 = $("#circulo5");
		elem.css({height: '100vh', top: -($(window).height()), left: '50%', marginLeft: -($(window).height()/2)});
		var separa=$(window).width()/2;
		//elem2.css({height: $(window).height(), left: '0px', top: -($(window).height())});
		//elem3.css({width: $(window).width()*2, left: '0%', top: '0px', position: "absolute", height: $(window).width()*2, backgroundPositionY: "50%", backgroundSize: "100%", marginTop: -$(window).width()});
		elem4.css({width: $(window).width(), left: '0px', top: '0px', position: "absolute", height: $(window).width(), marginTop: -($(window).width()/2)});
		elem5.css({width: $(window).height(), left: ($(window).width()-$(window).height())/2, top: -$(window).height(), position: "absolute", height: $(window).height(), marginTop: -($(window).height()/2)});
	}
	function muestrahola() {
		$("#hola").fadeIn(1500);
	}

	function cambiohola1() {
		$("#hola").attr("src", "/wp-content/themes/Moka/img/hola-02.svg"); 
		$("#hola").fadeIn();  
		

	}
	function cambiohola2() {
		$("#hola").fadeOut(function() { 
			$(this).load(function() { $(this).fadeIn(); }); 
			$(this).attr("src", "/wp-content/themes/Moka/img/hola-01.svg"); 
		}); 

	}
	function AnimateRotate(d){
		var elem = $("#circulo1");

		$({deg: 0}).animate({deg: d}, {
			duration: 1000,
			step: function(now){
				elem.css({
					transform: "rotate(" + now + "deg)"
				});
			}
		});
		
	}

	function AnimateRotate2(d){
		var elem = $("#circulo4");

		$({deg: 0}).animate({deg: d}, {
			duration: 1000,
			step: function(now){
				elem.css({
					transform: "rotate(" + now + "deg)"
				});
			}
		});
		
	}
	function muevecirculo1() {
		var elem = $("#circulo4");
		elem.animate({marginTop: $(window).height()}, 1500 );
		
	}
	function muevecirculo2() {
		var elem = $("#circulo5");
		elem.animate({marginTop: $(window).height()}, 1200 );
		if ($(window).height()>$(window).width()) {
			elem.css({width: '100%'});
		}
		
	}

	function muestratitulo1() {
			//$("#titulo1").animate({left:"+=100px"}, 1000 );
			//$('#titulo1').fadeIn('slow').animate({left:"+=100"}, 1000);
			$('#titulo1').animate({ opacity: 1, left: "+=50px" }, 700);
		}
		function muestratitulo2() {
			$('#titulo2').animate({ opacity: 1, left: "+=50px" }, 700);
		}
		function muestratitulo3() {
			$('#titulo3').animate({ opacity: 1, left: "+=50px" }, 700);
			$('#encabezado').animate({ opacity: 1}, 1500);
			$('#arrow').animate({ opacity: 1}, 1500);
		}


		$(window).scroll(function () {
			var $horizontal = $('#seccion1');
			var s = $(this).scrollTop();

			$horizontal.css({
				'background-position': -(s-800) + "px 0px"
			});

			//var t2 = $('#seccion1a').parent().offset().top;

			///if (s>(t2*0.5)) {
			//	$('#seccion1a').animate({ opacity: 1 }, 1500);
			//	$('#mosca').attr("src", "/wp-content/themes/Moka/img/mosca.gif"); 
			//} 
			//var t3 = $('#seccion2a').parent().offset().top;
			//if (s>(t3*0.6)) {
			//	$('#seccion2a').animate({ opacity: 1 }, 1500);
			//} 




		})

		$(document).scroll(function () {
			var y = $(this).scrollTop();

			var bottom_of_window = $(window).scrollTop();
			$("div#proy").each(function(i,obj) {
			//console.log($(obj).offset().top);
			//console.log($(document).scrollTop());

			if (($(document).scrollTop()*1.5)+(1-(i*200)) > $(obj).offset().top ) {
				$(obj).animate({ opacity: 1, marginTop:"0px"}, 1000);
			} 
		});	


			var slogan = $('#slogan');
			//console.log(y+($(window).height()/1.5) + " > " + slogan.parent().offset().top);
			if ((y+($(window).height()))>(slogan.parent().offset().top)) {
				slogan.animate({ opacity: 1 }, 1500);
			}
			//console.log($(document).scrollTop());
			var yy=(y-(slogan.parent().offset().top))+ (slogan.height());
			

			
			var ancho = window.innerWidth;
			var slogan3 = $('#seccion1a');
			var yy3=0;
			//console.log(ancho);

			if (ancho>=480 && ancho<=1024) {
				yy3=((y-(slogan3.parent().offset().top))- (slogan3.height()/2))+300;
			} else {
				yy3=((y-(slogan3.parent().offset().top))- (slogan3.height()/2))+800;
			}

			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				if ((y+($(window).height()/2))>(slogan3.parent().offset().top)) {
				slogan3.animate({ opacity: 1 }, 1500);
				}	
			
				

				var slogan2 = $('#seccion2a');
			if ((y+($(window).height()/2))>(slogan2.parent().offset().top)) {
				slogan2.animate({ opacity: 1 }, 1500);
			}
			var yy2=(y-(slogan2.parent().offset().top))- (slogan2.height()/2);
			

			if ((y+($(window).height()))>(slogan.parent().offset().top)) {
				slogan.animate({ opacity: 1 }, 1500);
			}
			//console.log($(document).scrollTop());
			var yy=(y-(slogan.parent().offset().top))+ (slogan.height()/2);
			

			} else {
			if ((y+($(window).height()/2))>(slogan3.parent().offset().top)) {
				slogan3.animate({ opacity: 1 }, 1500);
			}
			//var yy3=(y-(slogan3.parent().offset().top))+ (slogan3.height());
			

			var slogan2 = $('#seccion2a');
			if ((y+($(window).height()/2))>(slogan2.parent().offset().top)) {
				slogan2.animate({ opacity: 1 }, 1500);
			}
			var yy2=(y-(slogan2.parent().offset().top))+ (slogan2.height());
			
		}
		})

