/**
 * Created by s.salas on 12/08/2016.
 */
jQuery(document).ready(function(){
	
	jQuery('.play').each(function(){
		jQuery(this).click(function(){
			var id = jQuery(this).data("idplay");
			document.getElementById('track'+id).play();
			
			jQuery('#track'+id).on(
			"timeupdate", 
			function(event){
			  var current = parseInt(this.currentTime);
			  var total = parseInt(this.duration);
			  var val = (100*current)/total;

			  var $circle = $('.svg'+id+' .bar'+id);
			  var r = $circle.attr('r');
			  var c = Math.PI*(r*2);

			  if (val < 0) { val = 0;}
			  if (val > 100) { val = 100;}

			  var pct = ((100-val)/100)*c;
			  $circle.css({ strokeDashoffset: pct });
			  if (pct == 0) {
			      jQuery('.discaudio').removeClass("gira");
			      jQuery('._stop' + id).hide();
			      jQuery('._play' + id).css('display', 'inline-block');
			  }
			  
			});
			jQuery('.discaudio').addClass("gira");
			jQuery('._play'+id).hide();
			jQuery('._stop'+id).css('display', 'inline-block');
		});
	});
	
	jQuery('.stop').each(function(){
		jQuery(this).click(function(){
			var id = jQuery(this).data("idstop");
			document.getElementById('track' + id).pause();
			jQuery('.discaudio').removeClass("gira");
			jQuery('._stop'+id).hide();
			jQuery('._play'+id).css('display', 'inline-block');
		});
	});
	
	
});

