/**
 * Created by s.salas on 09/03/2020.
 */

document.addEventListener("DOMContentLoaded", function(event) {
    console.log('vamos a crear un podo de elementos con el nuevo javascript');
    
    let cssEditorOculto = document.createElement("textarea")
    cssEditorOculto.setAttribute("id", "ocultoCSS");
    cssEditorOculto.setAttribute("name", "textoCss");
    cssEditorOculto.style.display = "none";
    document.querySelector('#edittag').appendChild(cssEditorOculto);
    
    
    let cssInput = document.createElement("input")
    cssInput.setAttribute("id", "urlCSS");
    cssInput.setAttribute("name", "urlCSS");
    cssInput.setAttribute("type", "hidden");
    document.querySelector('#edittag').appendChild(cssInput);

    
    setTimeout(function(){ 
        const oldhtml = document.querySelector('#acf-css td');
        oldhtml.innerHTML += ' <br><a class="goEditorCSS">Editar CSS</a>';
        oldhtml.classList.add("viewCSSeditor");
        
        document.querySelector('a.goEditorCSS').addEventListener('click', function(){
            const cajaCss = document.querySelector('#editorCSS');
            cajaCss.scrollIntoView();
            cajaCss.focus();
        });
        
        
    }, 3000);

    
    const botonenviar = document.querySelector('input[type=submit]');
    botonenviar.addEventListener("click", function (event) {
        //event.preventDefault();

        // const url = document.querySelector(".acf-file-name").getAttribute("href");
        const url = document.querySelector('[data-name="filename"]').getAttribute("href");


        const antUrl = document.querySelector("#urlCSS").value;
        const textoArea = document.querySelector('#default').value;
        if (textoArea !== "" ||  url == antUrl) {
            const areatexto = document.querySelector('#ocultoCSS');
            areatexto.value = textoArea;
            document.querySelector('#urlCSS').value = url;
        }
    });
});

// jQuery(document).ready(function () {
//     jQuery('#edittag').append("<textarea style='display:none;' id='ocultoCSS' name='textoCss'>");
//     jQuery('#edittag').append("<input type='hidden' id='urlCSS' name='urlCSS'>");

//     function addCSSeditor() {

//                 oldhtml = jQuery('#acf-css td').html();
//                 jQuery('#acf-css td').html(oldhtml + '   <br><a>Editar CSS</a>');
//                 jQuery('#acf-css td a:last-child').addClass('viewCSSeditor');
        
//                 jQuery("#wp-default-editor-tools").hide();
//                 jQuery("#qt_default_toolbar").hide();
//                 jQuery('a.viewCSSeditor').click(function () {
//                     jQuery('#editorCSS').toggle();
//                     jQuery('#editorCSS').focus();
//                     jQuery('html,body').animate({
//                         scrollTop: jQuery('#editorCSS').offset().top
//                     }, 2000);
//                 });
//             }
//     setTimeout(addCSSeditor, 1500);
//     // jQuery('input#submit').click(function () {
//     //     var url = jQuery('a.acf-file-name').attr('href');
//     //     var antUrl = jQuery('#urlCSS').val;
//     //     var textoArea = jQuery('textarea#default').val();
//     //     if (textoArea != "" ||  url == antUrl) {
//     //         jQuery('#ocultoCSS').val(textoArea);
//     //         jQuery('#urlCSS').val(url);
//     //     }
//     // });  
//     document.addEventListener("DOMContentLoaded", function(event) {
//         console.log('ya se ha cargado el documento');
//         const botonenviar = document.querySelector('input[type=submit]');
//         botonenviar.addEventListener("click", function (event) {
//             //event.preventDefault();
//             const url = document.querySelector(".acf-file-name").getAttribute("href");
//             const antUrl = document.querySelector("#urlCSS").value;
//             const textoArea = document.querySelector('#default').value;
//             if (textoArea !== "" ||  url == antUrl) {
//                 const areatexto = document.querySelector('#ocultoCSS');
//                 areatexto.value = textoArea;
//                 document.querySelector('#urlCSS').value = url;
//             }
//         });
//     });
// });
