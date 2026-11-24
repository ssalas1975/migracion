jQuery(document).ready(function($) {          

	jQuery(document).on('widget-updated', function(e, widget){

		 jQuery(function () {

                 jQuery(".vclm-widget-tabs-list").sortable({
                     stop: function (event, ui) {
                         $sorttabs = "";
                         jQuery("li", this).each(function (i, val) {

                             $sorttabs += jQuery(val).data("name") + ",";

                         });

                         jQuery("#<?php echo $this->get_field_id('vclm_widget_tabs_sort'); ?>").val($sorttabs);
                         console.log($sorttabs);
                     }
                 });

                  jQuery(".vclm-widget-tabs-list").disableSelection();

                  jQuery(".vclm-tabs").tabs();


                  jQuery(".vclm-wd-visibility-tab").off("click");
                  jQuery(".vclm-wd-visibility-tab").on("click",function () {
                      console.log("clicked eye");
                      var relid = jQuery(this).data("rel");

                      if (jQuery(this).hasClass("hide")) {
                          jQuery(this).removeClass("hide");
                          jQuery(relid).val(false);
                      }
                      else {
                          jQuery(this).addClass("hide");
                          jQuery(relid).val(true);
                      }

                  });
              
             });
		
	});

});
	