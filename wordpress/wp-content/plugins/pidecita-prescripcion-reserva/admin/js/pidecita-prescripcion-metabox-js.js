var pidecitaPrescripcionCat = {};
var pidecitaPrescripcionServices = {};
var pidecitaPrescripcionLocations = {};

jQuery(function ($) {
    pidecitaPrescripcionInit();

    jQuery('#pidecita-prescripcion-reserva_reload').click(function(e) {
        e.preventDefault();
        jQuery('.pidecita-prescripcion-reserva').empty();
        jQuery('.pidecita-prescripcion-reserva_name').each(function () {
            jQuery(this).val('');
        });
        pidecitaPrescripcionInit(true);
    });

    function pidecitaPrescripcionInit(reload = false) {
        jQuery.ajax({
            type: "GET",
            dataType: "json",
            url: "https://www.pidecita.com/wrapper/api/pwa?callback=page/home",
            cache: true,
            success: (function (data, textStatus, jqXHR) {
                pidecitaPrescripcionServices = data.filters.services.map(function (elem) {
                    var children = elem.children.map(
                        function (chl) {
                            return {
                                id: chl.id,
                                text: chl.text,
                                parent:this.parent.id
                            };
                        }, {parent: elem});
                    children.unshift({id: elem.id, text: elem.text, parent: elem.id});
                    var array = {
                        id: elem.id,
                        text: elem.text,
                        children: children
                    };
                    return array;
                });
                pidecitaPrescripcionLocations = data.filters.location;
                pidecitaPrescripcionLocations.unshift({id: '0', text: 'Ciudad'});

                pidecitaPrescripcionCat = pidecitaPrescripcionServices.map(function (elem) {
                    return {id: elem.id, text: elem.text};
                });
                pidecitaPrescripcionCat.unshift({id: '0', text: 'Categoría'});
                jQuery("#pidecita-prescripcion-reserva_category_value").select2({
                    data: pidecitaPrescripcionCat,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        }
                    }
                });
                pidecitaPrescripcionServices.unshift({id: '0', text: 'Categoría, Subcategoría'});
                jQuery("#pidecita-prescripcion-reserva_subcategory_value").select2({
                    data: pidecitaPrescripcionServices,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        }
                    }
                });
                jQuery("#pidecita-prescripcion-reserva_city_value").select2({
                    data: pidecitaPrescripcionLocations,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        }
                    }
                });

                if (!reload) {
                    jQuery('.pidecita-prescripcion-reserva').each(function () {
                        jQuery(this).val(jQuery(this).data('value'));
                        jQuery(this).trigger('change');
                    });
                }
            })
        });

        prescripcionDisabled(jQuery('#pidecita-prescripcion-reserva_enabled').is(':checked'));
    }

    jQuery('#pidecita-prescripcion-reserva_enabled').change(function() {
        prescripcionDisabled(this.checked);
    });

    //disabled inputs if mudole is disabled
    function prescripcionDisabled(value) {
        var disabledSelect = !value;
        jQuery('.pidecita-prescripcion-reserva').each(function () {
            jQuery(this).prop( "disabled", disabledSelect );
        });
        jQuery('#pidecita-prescripcion-reserva_title').prop( "readonly", disabledSelect );
    }

    //validation selects
    jQuery('input#publish').click(function(e) {
        var subcat = jQuery('#pidecita-prescripcion-reserva_subcategory_value').val() == null ? '0' : jQuery('#pidecita-prescripcion-reserva_subcategory_value').val();
        var city = jQuery('#pidecita-prescripcion-reserva_city_value').val() == null ? '0' : jQuery('#pidecita-prescripcion-reserva_city_value').val();

        if ((subcat == '0' && city == '0') && jQuery('#pidecita-prescripcion-reserva_enabled').is(':checked')) {
            alert ('Selecione una categoría, subcategoría o ciudad');
            e.preventDefault();
            e.stopPropagation();
        }
    });

    //set values in inputs from selects
    jQuery('.pidecita-prescripcion-reserva').on('select2:select', function (e) {
        var data = e.params.data;
        jQuery('#' + jQuery(this).attr('id').replace('_value', '_name')).val(data.id == '0' ? '' : data.text);
        if (data.id != '') {
            pidecitaPrescripcionRecarga(e);
        }
    });

    //reload categories by subcategories filter
    jQuery('#pidecita-prescripcion-reserva_subcategory_value').on('select2:select', function (e) {
        pidecitaPrescripcionCat = pidecitaPrescripcionServices.map(function (elem) {
            return {id: elem.id, text: elem.text};
        });
        pidecitaPrescripcionCat.unshift({id: '0', text: 'Categoría'});
        jQuery("#pidecita-prescripcion-reserva_category_value").select2({
            data: pidecitaPrescripcionCat,
            language: {
                noResults: function () {
                    return "No se encontraron resultados";
                }
            }
        });
    });

    //reload categories and subcategories by city filter
    jQuery('#pidecita-prescripcion-reserva_city_value').on('select2:select', function (e) {
        var data = e.params.data;
        var oldCat = '';
        var oldSubCat = '';
        if (null !== jQuery("#pidecita-prescripcion-reserva_category_value").val()) {
            oldCat = jQuery("#pidecita-prescripcion-reserva_category_value").select2('data')[0].id;
        }
        if (null !== jQuery("#pidecita-prescripcion-reserva_subcategory_value").val()) {
            oldSubCat = jQuery("#pidecita-prescripcion-reserva_subcategory_value").select2('data')[0].id;
        }
        jQuery('#' + jQuery(this).attr('id').replace('_value', '_name')).val(data.id == '0' ? '' : data.text);
        if (data.id != '0') {
            jQuery.ajax({
                type: "GET",
                dataType: "json",
                url: "https://www.pidecita.com/wrapper/api/pwa?callback=gettreatments/" + e.params.data.id,
                cache: true,
                success: (function (data, textStatus, jqXHR) {
                    pidecitaPrescripcionServices = data.response.map(function (elem) {
                        var children = elem.children.map(
                            function (chl) {
                                return {
                                    id: chl.id,
                                    text: chl.text,
                                    parent:this.parent.id
                                };
                            }, {parent: elem});
                        children.unshift({id: elem.id, text: elem.text, parent: elem.id});
                        var array = {
                            id: elem.id,
                            text: elem.text,
                            children: children
                        };
                        return array;
                    });
                    pidecitaPrescripcionCat = pidecitaPrescripcionServices.map(function (elem) {
                        return {id: elem.id, text: elem.text};
                    });
                    pidecitaPrescripcionCat.unshift({id: '0', text: 'Categoría'});
                    pidecitaPrescripcionServices.unshift({id: '0', text: 'Categoría, Subcategoría'});
                    jQuery("#pidecita-prescripcion-reserva_category_value").empty();
                    jQuery("#pidecita-prescripcion-reserva_category_value").select2({
                        data: pidecitaPrescripcionCat,
                        language: {
                            noResults: function () {
                                return "No se encontraron resultados";
                            }
                        }
                    });
                    jQuery("#pidecita-prescripcion-reserva_subcategory_value").empty();
                    jQuery("#pidecita-prescripcion-reserva_subcategory_value").select2({
                        data: pidecitaPrescripcionServices,
                        language: {
                            noResults: function () {
                                return "No se encontraron resultados";
                            }
                        }
                    });
                    jQuery("#pidecita-prescripcion-reserva_category_value").val(oldCat);
                    jQuery("#pidecita-prescripcion-reserva_category_value").trigger('change');
                    jQuery("#pidecita-prescripcion-reserva_subcategory_value").val(oldSubCat);
                    jQuery("#pidecita-prescripcion-reserva_subcategory_value").trigger('change');
                })
            });
        }
    });

    //reload selects
    function pidecitaPrescripcionRecarga(e) {
        var filtroCatSubCat = '';
        var oldSubCat = '';
        var oldCity = '';
        if (null !== jQuery("#pidecita-prescripcion-reserva_subcategory_value").val()) {
            oldSubCat = jQuery("#pidecita-prescripcion-reserva_subcategory_value").select2('data')[0].id;
        }
        if (null !== jQuery("#pidecita-prescripcion-reserva_city_value").val()) {
            oldCity = jQuery("#pidecita-prescripcion-reserva_city_value").select2('data')[0].id;
        }
        if ((jQuery(e.currentTarget).attr('id').indexOf('subcategory',0) !== -1)) {
            if (e.params.data.parent != e.params.data.id) {
                filtroCatSubCat = '/' + e.params.data.parent + '/' + e.params.data.id;
            } else {
                filtroCatSubCat = '/' + e.params.data.id;
            }
            jQuery("#pidecita-prescripcion-reserva_category_value").val(e.params.data.parent);
            jQuery("#pidecita-prescripcion-reserva_category_value").trigger('change');
        }
        jQuery.ajax({
            type: "GET",
            dataType: "json",
            url: "https://www.pidecita.com/wrapper/api/pwa?callback=getlocations" + filtroCatSubCat,
            cache: true,
            success: (function (data, textStatus, jqXHR) {
                pidecitaPrescripcionLocations = data.response;

                if (pidecitaPrescripcionLocations && oldSubCat != '0') {
                    pidecitaPrescripcionLocations.unshift({id: '0', text: 'Ciudad'});

                    jQuery("#pidecita-prescripcion-reserva_city_value").empty();
                    jQuery("#pidecita-prescripcion-reserva_city_value").select2({
                        data: pidecitaPrescripcionLocations,
                        language: {
                            noResults: function () {
                                return "No se encontraron resultados";
                            }
                        }
                    });
                    if (oldCity == '') { oldCity = 'Ciudad' }
                    jQuery("#pidecita-prescripcion-reserva_city_value").val(oldCity);
                    jQuery("#pidecita-prescripcion-reserva_city_value").trigger('change');
                }
            })
        });
    }
});

