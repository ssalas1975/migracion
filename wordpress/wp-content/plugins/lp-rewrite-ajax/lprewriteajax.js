/**
 * Created by lp on 10/06/15.
 */


jQuery(document).ajaxSend(function(e) {
    e.handleObj.handler.arguments[2].url=e.handleObj.handler.arguments[2].url.replace(/\/wp-admin\/admin-ajax.php/g,"/wp-content/ajaxrewrite.php");
});

