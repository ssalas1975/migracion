!function ($) {
    let ajax_url = $('#adminajaxurl_js_field').val();
    let postid = getParameterByName('post');

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    $('.head_field').change(function () {
        var textjs = $(this).val();
        //console.log(textjs);
        head_javascript(textjs).then(res => {
            //console.log(res);
        });
    });

    var head_javascript = (textojs) => new Promise((resolve, reject) => {
        var data = {
            'action': 'head_javascript',
            'post_type': 'post',
            'textojs': textojs,
            'idpost': postid,
        };
        $.post(ajax_url, data, function (response) {
            resolve(response);
        }, 'json');
    });

    $('.body_field').change(function () {
        var textjs = $(this).val();
        //console.log(textjs);
        body_javascript(textjs).then(res => {
            //console.log(res);
        });
    });

    var body_javascript = (textojs) => new Promise((resolve, reject) => {
        var data = {
            'action': 'body_javascript',
            'post_type': 'post',
            'textojs': textojs,
            'idpost': postid,
        };
        $.post(ajax_url, data, function (response) {
            resolve(response);
        }, 'json');
    });

    var loadHeadScript = (postid) => new Promise((resolve, reject) => {
        var data = {
            'action': 'loadHeadScript',
            'post_type': 'post',
            'idpost': postid
        };
        $.post(ajax_url, data, function (respose) {
            resolve(respose);
        }, 'json');
    });

    var loadBodyScript = (postid) => new Promise((resolve, reject) => {
        var data = {
            'action': 'loadBodyScript',
            'post_type': 'post',
            'idpost': postid
        };
        $.post(ajax_url, data, function (respose) {
            resolve(respose);
        }, 'json');
    });

    const borrarRegistrosBBDD = (postid) => new Promise((resolve, reject) => {
        var data = {
            'action': 'borrarregistrosjavascript',
            'post_type': 'post',
            'idpost': postid
        };

        $.post(ajax_url, data, function (response) {
            resolve(response);
        }, 'json');
    });

    $(document).ready(function () {
        $('.idpostfield').val(postid);
        loadHeadScript(postid).then(res => {
            $('.head_field').val(res);
        });
        loadBodyScript(postid).then(res => {
            $('.body_field').val(res);
        });
        $(document).on('click', '.vc_control-btn-delete', function (e) {
            //console.log('borrado');
            let postid = getParameterByName('post');
            borrarRegistrosBBDD(postid).then(res => {
                //console.log(res);
            });
        });
    });

}(window.jQuery);