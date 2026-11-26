/*
Author: Adrian Mato
Author URI: http://web.ontuts.com
*/

//variable global para controles dropdown

$(".frame").hide();
$(".2008m").hide();
hideOptions($(this.document));

//control de eventos
$(this.document).ready(function(){
						   																				
	$("ul.dropdown").mouseover(function(){
		displayOptions($(this).find("li"));
	});
	$("ul.dropdown").mouseout(function(){
		hideOptions($(this));
		$(".2008m").hide();
	});	
	
	$(".2008").mouseover(function(){
		$(".2008m").fadeIn("fast");
	});
	$(".2008").mouseout(function(){
		$(".2008m").hide();
	});	
	
	$(".basesVa").click(function(){
		$(".frame").hide();
		$(".framebasesVa").fadeIn("slow");
	});
	$(".basesCM").click(function(){
		$(".frame").hide();
		$(".framebasesCM").fadeIn("slow");
	});
	$(".basesR").click(function(){
		$(".frame").hide();
		$(".framebasesR").fadeIn("slow");
	});
	$(".basesD").click(function(){
		$(".frame").hide();
		$(".framebasesD").fadeIn("slow");
	});
	$(".home").click(function(){
		$(".frame").fadeOut("slow");
		/*$(".home").fadeIn("slow");*/
	});
	$(".contacto").click(function(){
		$(".frame").hide();
		$(".framecontacto").fadeIn("slow");
	});
	$(".organizadores").click(function(){
		$(".frame").hide();
		$(".organizadores").fadeIn("slow");
	});
	$(".ediciones").click(function(){
		$(".frame").hide();
		$(".ediciones").fadeIn("slow");
	});
	$(".edicion2010f").click(function(){
		$(".frame").hide();
		$(".edicion2010f").fadeIn("slow");
	});
	$(".edicion2010c").click(function(){
		$(".frame").hide();
		$(".edicion2010c").fadeIn("slow");
	});
	$(".finalistasVa").click(function(){
		$(".frame").hide();
		$(".finalistasVa").fadeIn("slow");
	});
	$(".finalistasCM").click(function(){
		$(".frame").hide();
		$(".finalistasCM").fadeIn("slow");
	});
	$(".finalistasR").click(function(){
		$(".frame").hide();
		$(".finalistasR").fadeIn("slow");
	});
	$(".finalistasD").click(function(){
		$(".frame").hide();
		$(".finalistasD").fadeIn("slow");
	});

	$(".edicion2010f").click(function(){
		$(".frame").hide();
		$(".edicion2010ff").fadeIn("slow");
	});
	
	$(".ediciones").click(function(){
		$(".frame").hide();
		$(".edicionf").fadeIn("slow");
	});
	
	
	
			
})

//funcion que MUESTRA todos los elementos del menu
function displayOptions(e){
	e.show();
}
//funcion que OCULTA los elementos del menu
function hideOptions(e){
	e.find("li").hide();
	e.find("li.active").show();
}
