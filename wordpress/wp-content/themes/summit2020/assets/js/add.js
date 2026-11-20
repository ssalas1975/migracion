	
	$(document).ready(function(){

		if(secc!=''){ 
			setTimeout(function(){
				  console.log(secc);
		 		$('html,body').animate({
		          scrollTop: $('.section-'+secc).offset().top
		        }, 500);
		 	},100)
		 }

	}) 
	