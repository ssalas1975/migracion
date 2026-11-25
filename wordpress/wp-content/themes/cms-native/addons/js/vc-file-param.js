jQuery(document).ready(function($){
    // Usamos delegación de eventos para asegurarnos de que funcione en modales de WPBakery
    $('body').on('click', '.voc_upload_file_btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $block = $button.closest('.voc_attach_file_block');
        var $input = $block.find('.wpb_vc_param_value');
        var $preview = $block.find('.voc-file-preview span');
        var $removeBtn = $block.find('.voc_remove_file_btn');

        // Si ya existe una instancia de wp.media, la abrimos
        var file_frame = wp.media.frames.voc_file_frame = wp.media({
            title: 'Seleccionar o subir fichero',
            button: {
                text: 'Usar este fichero'
            },
            multiple: false // Cambiar a true si quieres permitir selección múltiple
        });

        // Cuando se selecciona un archivo
        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();

            // Guardamos el ID del archivo en el input oculto
            $input.val(attachment.id).trigger('change');

            // Actualizamos la vista previa con la URL del archivo
            $preview.text(attachment.url);
            $removeBtn.show();
        });

        file_frame.open();
    });

    // Botón para eliminar la selección
    $('body').on('click', '.voc_remove_file_btn', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $block = $button.closest('.voc_attach_file_block');
        var $input = $block.find('.wpb_vc_param_value');
        var $preview = $block.find('.voc-file-preview span');

        $input.val('').trigger('change');
        $preview.text('Ningún archivo seleccionado');
        $button.hide();
    });
});
