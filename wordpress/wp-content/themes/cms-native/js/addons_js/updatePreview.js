/**
 * Created by s.salas on 09/08/2016.
 */
jQuery(document).ready(function () {
    jQuery('.previewcontent').click(function (event) {
       
        var $this = jQuery("#post-preview"),
                $form = jQuery('form#post'),
                $previewField = jQuery('input#wp-preview'),
                target = $this.attr('target') || 'wp-preview',
                ua = navigator.userAgent.toLowerCase();

        event.preventDefault();

        if ($this.hasClass('disabled')) {
            return;
        }

        if (wp.autosave) {
            wp.autosave.server.tempBlockSave();
        }

        $previewField.val('dopreview');
        $form.attr('target', target).submit().attr('target', '');

        // Workaround for WebKit bug preventing a form submitting twice to the same action.
        // https://bugs.webkit.org/show_bug.cgi?id=28633
        if (ua.indexOf('safari') !== -1 && ua.indexOf('chrome') === -1) {
            $form.attr('action', function (index, value) {
                return value + '?t=' + (new Date()).getTime();
            });
        }

        $previewField.val('');
       
        var urlMedio = jQuery(this).attr('href');
        var redirectWindow = window.open(urlMedio, 'blank');
        redirectWindow.location;
    });

});
