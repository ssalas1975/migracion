$(document).ready(function () {

    
  

    function load_blog_items(page) {

        if (!page)
            page = 0;

        $.get("/wp-content/themes/Moka/blog-loader-ajax.php?page=" + page, function (data) {

            var template = $('#blogitem').html();

            data = $.parseJSON(data);

            Mustache.parse(template);
            var rendered = Mustache.render(template, data.items.posts.cola);
            $('#columm-A').append(rendered);

            var renderedB = Mustache.render(template, data.items.posts.colb);
            $('#columm-B').append(renderedB);

            if (!data.items.more_pages) {
                $("#loadmore").hide();
            } else {
                $("#loadmore").show();
                $("#btn-blog").data("page", page + 1);
            }


        });

    }

    $("#btn-blog").click(function () {
        load_blog_items($(this).data("page"));
    });
    load_blog_items(0);

});