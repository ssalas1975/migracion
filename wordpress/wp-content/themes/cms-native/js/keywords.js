jQuery(document).ready(function(){
 jQuery('input.button.keywordadd').click(function() {
		var inserted = jQuery("input#keywordsinput").val();
		
		if(	inserted === undefined || 0 === inserted.length )
			var selected = jQuery("input[name='keywords']").val();
		
		if(!jQuery("input[name='keywords']").val())
			var selected = jQuery("select#keywords").val();			
		
		if(inserted)
			var selected = jQuery("input#keywordsinput").val();
		
		// jQuery( "'<li><a class='ntdelbutton' data-id='"+selected+"'>X</a>&nbsp;"+selected+"</li>'" ).appendTo( "#keywordsDiv" );
		jQuery('<li><a class="ntdelbutton" data-id="'+selected+'">X</a>&nbsp;'+selected+'</li>').appendTo('#keywordsDiv');
		jQuery("input[name='keywords']").val('');	
		
		});
		
		jQuery('body').on('click','a.ntdelbutton',function(){
			jQuery(this).parent().remove();
		});		
		
jQuery('input#publish').click(function() {
	
	var keywordArray ="";
	var count = 1;
	jQuery("div#keywordsDiv li a").each(function(){
			if(jQuery("div#keywordsDiv li a").length==count){
				
				keywordArray += jQuery(this).data("id");
				
			}else{
				
				keywordArray += jQuery(this).data("id")+",";
			}
			count = count + 1;
			});
			
		 jQuery("input[name='keyword-url']").val(keywordArray);
		 
		});
		
});

		
