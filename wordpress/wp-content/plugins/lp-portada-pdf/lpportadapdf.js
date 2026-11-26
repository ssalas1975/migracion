/**
 * Created by lp on 10/06/15.
 */

jQuery(document).ready(function($){


    $("#porDorsal").click(function(e){
        $("#divimprimir").css('display','none');
        $.ajax({
            type: "POST",
            data: { porDorsal:"porDorsal",Dorsal: $("#Dorsal").val(), 'nonce-dorsal' : LPPortadaPDF["nonce-dorsal"], 'action' : 'lpportadapdf_dorsal'},
            //url: "/sites/www.15kvalencia.com/ajaxLoad.php?rand="+Math.random(),
            //url: document.location.protocol+'//'+document.location.host+'/wp-admin/admin-ajax.php',
            url: LPPortadaPDF["ajaxurl"],
            //url: '/wp-admin/admin-ajax.php',
            dataType: "html",
            success: function(data){
                $("#datos").html(data);
                if (data.length>150){
                    $("#divimprimir").css('display','block');
                    //$("#imprimir").attr("disabled",false);
                }
            },
            error: function(){
                $("#datos").html("Ha ocurrido un error. Vuelve a intentarlo dentro de un rato");
            }
        });
        e.preventDefault();
    });


    $("#imprimir").click(function(e){
        $.ajax({
            type: "POST",
            data: { imprimirDorsal:$("#Dorsal").val(),imprimir: $("#Dorsal").val(), 'nonce-imprimir' : LPPortadaPDF["nonce-imprimir"], 'action' : 'lpportadapdf_imprimir'},
            //url: document.location.protocol+'//'+document.location.host+'/wp-admin/admin-ajax.php',
            url: LPPortadaPDF["ajaxurl"],
            dataType: "html",
            success: function(data){
                console.log("creado pdf");
                window.location.href=LPPortadaPDF["baseurl"]+"/carrera-"+$("#Dorsal").val()+".pdf?rand="+Math.random();
                //$("#imprimirlink").attr("href",LPPortadaPDF["baseurl"]+"/15KValencia-"+$("#Dorsal").val()+".pdf?rand="+Math.random());
                //$("#imprimirlink")[0].click();

            }
        });

        //_gaq.push(['_trackEvent', 'Descarga PDF' , 'http://www.15kvalencia.com/sites/www.15kvalencia.com/'+$("#Dorsal").val()+'.pdf']);
        e.preventDefault();
    });
});