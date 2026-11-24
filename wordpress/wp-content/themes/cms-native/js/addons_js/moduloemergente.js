       (function($) {
           $(document).ready(function(){
               function recogeValor() {
                   var textoModuloEmergente = $('.grid .pie-ad p').text();
                   $('.moduloemergente p').text(textoModuloEmergente);
               }
               setTimeout(recogeValor, 100);
           });
       }(jQuery));

