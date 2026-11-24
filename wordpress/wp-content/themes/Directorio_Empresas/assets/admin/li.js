jQuery(document).ready(function () {

    jQuery(ajax_object.li_urls).each(function (i, val) {
        jQuery.ajax({
            url: val,
            dataType: "jsonp",
            success: function (data) {
                console.log(data)
                alert(data);
            }
        });


    });
    

   

});
